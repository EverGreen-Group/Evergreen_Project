<?php

class M_Payment {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPaymentSummary() {
        $this->db->query("
            SELECT 
                *,
                MONTHNAME(STR_TO_DATE(CONCAT(year, '-', month, '-01'), '%Y-%m-%d')) AS month_name
            FROM factory_payments
            ORDER BY year DESC, month DESC
        ");
    
        return $this->db->resultSet();
    }

    public function checkIfAlreadyExists($year, $month) {
        $this->db->query("SELECT * FROM factory_payments WHERE year = :year AND month= :month");
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        return $this->db->single();
    }
//////////////////////////////////////////////////////////////////////////////// FOR ISHAN

    // Main method to generate the monthly payment report and supplier details
    public function generateMonthlyPayment($year, $month, $normalLeafRate, $superLeafRate) {
        // Fetch configuration values for deductions, transport cost, etc.
        $moistureDeductions = $this->getDeductionRates('moisture');
        $ageDeductions = $this->getDeductionRates('leaf_age');
        $transportCost = $this->getTransportCost();  // cost per unique collection
    
        // Fetch bag usage history rows that are finalized for the given month and year.
        // We now also select 'finalized_at' so we can group daily.
        $this->db->query("
            SELECT 
                supplier_id,
                collection_id,
                leaf_type_id,
                actual_weight_kg,
                leaf_age,
                moisture_level,
                finalized_at
            FROM bag_usage_history
            WHERE is_finalized = 1
              AND YEAR(finalized_at) = :year
              AND MONTH(finalized_at) = :month
        ");
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        $bags = $this->db->resultSet();
    
        // Initialize arrays to hold aggregated data.
        $suppliers = [];       // For monthly aggregation.
        $dailyBreakdown = [];  // For supplier daily aggregation, keyed by supplier and date.
    
        foreach ($bags as $bag) {
            $sid = $bag->supplier_id;
            // Extract the date portion for daily grouping.
            $date = date('Y-m-d', strtotime($bag->finalized_at));
    
            // Calculate deductions
            $moistureDeduct = isset($moistureDeductions[$bag->moisture_level]) ? $moistureDeductions[$bag->moisture_level] : 0;
            $ageDeduct      = isset($ageDeductions[$bag->leaf_age])      ? $ageDeductions[$bag->leaf_age]      : 0;
            $totalDeductionPercent = $moistureDeduct + $ageDeduct;
    
            // Determine rate per kg for this leaf type
            $ratePerKg = 0;
            if ($bag->leaf_type_id == 1) {
                $ratePerKg = $normalLeafRate;
            } elseif ($bag->leaf_type_id == 2) {
                $ratePerKg = $superLeafRate;
            }
    
            // Compute weights and payment
            $originalWeight = $bag->actual_weight_kg;
            $adjustedWeight = $originalWeight * (1 - $totalDeductionPercent / 100);
            // Deduction amount: difference in weight * rate (assuming linear rate)
            $deductionWeight = $originalWeight - $adjustedWeight;
            $payment = $adjustedWeight * $ratePerKg;
            $deductionAmount = $originalWeight * $ratePerKg - $payment;
    
            // -------------------------
            // Monthly Aggregation (for factory reports)
            // -------------------------
            if (!isset($suppliers[$sid])) {
                $suppliers[$sid] = [
                    'collections'   => [],  // unique collection_ids
                    'normal_kg'     => 0,
                    'super_kg'      => 0,
                    'total_payment' => 0,
                    'deduct_kg'     => 0,
                    'deduct_amount' => 0
                ];
            }
    
            // Sum adjusted weight by leaf type and aggregate payment & deductions
            if ($bag->leaf_type_id == 1) {
                $suppliers[$sid]['normal_kg'] += $adjustedWeight;
            } elseif ($bag->leaf_type_id == 2) {
                $suppliers[$sid]['super_kg'] += $adjustedWeight;
            }
            $suppliers[$sid]['total_payment'] += $payment;
            $suppliers[$sid]['deduct_kg'] += $deductionWeight;
            $suppliers[$sid]['deduct_amount'] += $deductionAmount;
            $suppliers[$sid]['collections'][$bag->collection_id] = true;
    
            // -------------------------
            // Daily Aggregation (for supplier daily earnings)
            // -------------------------
            if (!isset($dailyBreakdown[$sid])) {
                $dailyBreakdown[$sid] = [];
            }
            if (!isset($dailyBreakdown[$sid][$date])) {
                $dailyBreakdown[$sid][$date] = [
                    'normal_kg' => 0,
                    'super_kg' => 0,
                    'payment' => 0,
                    'deduct_kg' => 0,
                    'deduct_amount' => 0,
                    'collections' => []
                ];
            }
            if ($bag->leaf_type_id == 1) {
                $dailyBreakdown[$sid][$date]['normal_kg'] += $adjustedWeight;
            } elseif ($bag->leaf_type_id == 2) {
                $dailyBreakdown[$sid][$date]['super_kg'] += $adjustedWeight;
            }
            $dailyBreakdown[$sid][$date]['payment'] += $payment;
            $dailyBreakdown[$sid][$date]['deduct_kg'] += $deductionWeight;
            $dailyBreakdown[$sid][$date]['deduct_amount'] += $deductionAmount;
            $dailyBreakdown[$sid][$date]['collections'][$bag->collection_id] = true;
        }
    
        // Compute overall monthly totals for summary report
        $total_suppliers = count($suppliers);
        $totalKg = 0;
        $totalPayment = 0;
        $totalTransportCost = 0;
        
        foreach ($suppliers as $sid => $data) {
            $totalKg += ($data['normal_kg'] + $data['super_kg']);
            $collectionCount = count($data['collections']);
            $transportCharge = $transportCost * $collectionCount;
            $totalTransportCost += $transportCharge;
            $totalPayment += ($data['total_payment'] - $transportCharge); // Subtract transport charge
        }
    
        // Insert summary row into factory_payments
        $this->db->query("
            INSERT INTO factory_payments (
                year, month, total_suppliers, total_kg, total_payment, 
                normal_leaf_rate, super_leaf_rate
            )
            VALUES (
                :year, :month, :total_suppliers, :total_kg, :total_payment, 
                :normal_leaf_rate, :super_leaf_rate
            )
        ");
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        $this->db->bind(':total_suppliers', $total_suppliers);
        $this->db->bind(':total_kg', $totalKg);
        $this->db->bind(':total_payment', $totalPayment);
        $this->db->bind(':normal_leaf_rate', $normalLeafRate);
        $this->db->bind(':super_leaf_rate', $superLeafRate);
        $this->db->execute();
    
        // Retrieve the generated payment_id
        $payment_id = $this->db->lastInsertId();
    
        // Insert detailed monthly rows for each supplier into factory_payment_details
        foreach ($suppliers as $sid => $data) {
            $collectionCount = count($data['collections']);
            $transportCharge = $transportCost * $collectionCount;
            $finalPayment = $data['total_payment'] - $transportCharge; // Subtracting transport charge from base payment
    
            $this->db->query("
                INSERT INTO factory_payment_details (
                    payment_id, supplier_id, total_collections, 
                    normal_kg, super_kg, total_kg, payment_amount, transport_charge,
                    total_deduction_kg, total_deduction_amount
                ) VALUES (
                    :payment_id, :supplier_id, :collections, 
                    :normal_kg, :super_kg, :total_kg, :payment_amount, :transport_charge,
                    :deduct_kg, :deduct_amount
                )
            ");
            $this->db->bind(':payment_id', $payment_id);
            $this->db->bind(':supplier_id', $sid);
            $this->db->bind(':collections', $collectionCount);
            $this->db->bind(':normal_kg', $data['normal_kg']);
            $this->db->bind(':super_kg', $data['super_kg']);
            $this->db->bind(':total_kg', $data['normal_kg'] + $data['super_kg']);
            $this->db->bind(':payment_amount', $finalPayment);
            $this->db->bind(':transport_charge', $transportCharge);
            $this->db->bind(':deduct_kg', $data['deduct_kg']);
            $this->db->bind(':deduct_amount', $data['deduct_amount']);
            $this->db->execute();
        }
    
        // Insert daily breakdown rows for each supplier into supplier_daily_earnings
        foreach ($dailyBreakdown as $sid => $dates) {
            foreach ($dates as $date => $data) {
                $normal = $data['normal_kg'];
                $super = $data['super_kg'];
                $basePayment = $data['payment'];
                $dailyCollections = count($data['collections']);
                $transport = $transportCost * $dailyCollections;
                $totalPaymentDaily = $basePayment - $transport; // Subtracting transport charge
                $deductKg = $data['deduct_kg'];
                $deductAmount = $data['deduct_amount'];
    
                $this->db->query("
                    INSERT INTO supplier_daily_earnings (
                        supplier_id, payment_id, active, collection_date, normal_kg, super_kg, 
                        total_kg, base_payment, transport_charge, total_payment,
                        total_deduction_kg, total_deduction_amount
                    ) VALUES (
                        :supplier_id, :payment_id, :active, :collection_date, :normal_kg, :super_kg, 
                        :total_kg, :base_payment, :transport_charge, :total_payment,
                        :deduct_kg, :deduct_amount
                    )
                ");
                $this->db->bind(':supplier_id', $sid);
                $this->db->bind(':payment_id', $payment_id);
                $this->db->bind(':active', 0);
                $this->db->bind(':collection_date', $date);
                $this->db->bind(':normal_kg', $normal);
                $this->db->bind(':super_kg', $super);
                $this->db->bind(':total_kg', $normal + $super);
                $this->db->bind(':base_payment', $basePayment);
                $this->db->bind(':transport_charge', $transport);
                $this->db->bind(':total_payment', $totalPaymentDaily);
                $this->db->bind(':deduct_kg', $deductKg);
                $this->db->bind(':deduct_amount', $deductAmount);
                $this->db->execute();
            }
        }
    
        return $payment_id;
    }
    

    // Helper to fetch deduction rates for a given category (moisture or leaf_age)
    private function getDeductionRates($category) {
        $this->db->query("
            SELECT value, deduction_percent
            FROM deduction_configurations
            WHERE category = :category
        ");
        $this->db->bind(':category', $category);
        $results = $this->db->resultSet();

        $rates = [];
        foreach ($results as $row) {
            $rates[$row->value] = $row->deduction_percent;
        }
        return $rates;
    }

    // Helper to get the payment rates for different leaf types
    private function getLeafTypeRates() {
        $this->db->query("SELECT leaf_type_id, rate FROM leaf_type_rates");
        $results = $this->db->resultSet();

        $rates = [];
        foreach ($results as $row) {
            $rates[$row->leaf_type_id] = $row->rate;
        }
        return $rates;
    }

    // Helper to get the transport cost per unique collection
    private function getTransportCost() {
        $this->db->query("SELECT cost_per_collection FROM transport_cost_configuration LIMIT 1");
        $result = $this->db->single();
        return ($result) ? $result->cost_per_collection : 0;
    }


    // Get payment by ID
    public function getPaymentById($payment_id) {
        $this->db->query("
            SELECT * FROM factory_payments
            WHERE id = :payment_id
        ");
        $this->db->bind(':payment_id', $payment_id);
        return $this->db->single();
    }

    // Get payment details with supplier names
    public function getPaymentDetailsByPaymentId($payment_id) {
        $this->db->query("
            SELECT fpd.*, p.first_name, p.last_name, CONCAT(p.first_name, ' ' , p.last_name) as supplier_name
            FROM factory_payment_details fpd
            JOIN suppliers s ON fpd.supplier_id = s.supplier_id
            JOIN profiles p on s.profile_id = p.profile_id
            WHERE fpd.payment_id = :payment_id
            ORDER BY fpd.total_kg DESC
        ");
        $this->db->bind(':payment_id', $payment_id);
        return $this->db->resultSet();
    }


    // Delete payment by ID
    public function deletePayment($payment_id) {

        $this->db->query("
            DELETE FROM supplier_daily_earnings
            WHERE payment_id = :payment_id
        ");
        $this->db->bind(':payment_id', $payment_id);
        $this->db->execute();


        $this->db->query("
            DELETE FROM factory_payments
            WHERE id = :payment_id
        ");
        $this->db->bind(':payment_id', $payment_id);
        return $this->db->execute();
    }

    public function publishPaymentReport($paymentId) {

        $this->db->query("
            SELECT * FROM supplier_daily_earnings WHERE active = 1 AND payment_id = :payment_id
        ");
        $this->db->bind(':payment_id', $paymentId);
        $result1 = $this->db->resultSet();
        if(count($result1) > 1) {
            return 0;
        }


        $this->db->query("
            UPDATE supplier_daily_earnings SET active = 1 WHERE payment_id = :payment_id
        ");
        $this->db->bind(':payment_id', $paymentId);
        $result2 = $this->db->execute();
        if($result2) {
            return 1;
        } else {
            return 3;
        }
    }


} 