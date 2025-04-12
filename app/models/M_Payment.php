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
    public function generateMonthlyPayment($year, $month) {
        // Fetch configuration values for deductions, transport cost, and leaf type rates
        $moistureDeductions = $this->getDeductionRates('moisture');
        $ageDeductions = $this->getDeductionRates('leaf_age');
        $transportCost = $this->getTransportCost();  // cost per unique collection
        $leafTypeRates = $this->getLeafTypeRates();  // Get payment rates for different leaf types

        // Fetch bag usage history rows that are finalized for the given month and year
        $this->db->query("
            SELECT 
                supplier_id,
                collection_id,
                leaf_type_id,
                actual_weight_kg,
                leaf_age,
                moisture_level
            FROM bag_usage_history
            WHERE is_finalized = 1
              AND YEAR(finalized_at) = :year
              AND MONTH(finalized_at) = :month
        ");
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        $bags = $this->db->resultSet();

        // Group data per supplier
        $suppliers = [];
        foreach ($bags as $bag) {
            $sid = $bag->supplier_id;

            if (!isset($suppliers[$sid])) {
                $suppliers[$sid] = [
                    'collections'   => [],  // unique collection_ids
                    'normal_kg'     => 0,
                    'super_kg'      => 0,
                    'total_payment' => 0
                ];
            }

            // Get deduction rates for moisture and age;
            // if no config is found, default to 0%
            $moistureDeduct = isset($moistureDeductions[$bag->moisture_level]) ? $moistureDeductions[$bag->moisture_level] : 0;
            $ageDeduct      = isset($ageDeductions[$bag->leaf_age])      ? $ageDeductions[$bag->leaf_age]      : 0;
            $totalDeductionPercent = $moistureDeduct + $ageDeduct;

            // Get the rate for this leaf type
            $ratePerKg = isset($leafTypeRates[$bag->leaf_type_id]) ? $leafTypeRates[$bag->leaf_type_id] : 0;

            // Calculate adjusted weight and payment
            $originalWeight = $bag->actual_weight_kg;
            $adjustedWeight = $originalWeight * (1 - $totalDeductionPercent / 100);
            
            // Calculate payment based on rate per kg and adjusted weight
            $payment = $adjustedWeight * $ratePerKg;

            // Sum up according to leaf type
            if ($bag->leaf_type_id == 1) {
                $suppliers[$sid]['normal_kg'] += $adjustedWeight;
            } elseif ($bag->leaf_type_id == 2) {
                $suppliers[$sid]['super_kg'] += $adjustedWeight;
            }
            $suppliers[$sid]['total_payment'] += $payment;

            // Record unique collection IDs to later compute transport cost per supplier
            $suppliers[$sid]['collections'][$bag->collection_id] = true;
        }

        // Compute overall totals for the summary report
        $total_suppliers = count($suppliers);
        $totalKg = 0;
        $totalPayment = 0;
        foreach ($suppliers as $sid => $data) {
            $totalKg += ($data['normal_kg'] + $data['super_kg']);
            $totalPayment += $data['total_payment'];
        }

        // Insert into factory_payments (the report summary)
        $this->db->query("
            INSERT INTO factory_payments (year, month, total_suppliers, total_kg, total_payment)
            VALUES (:year, :month, :total_suppliers, :total_kg, :total_payment)
        ");
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        $this->db->bind(':total_suppliers', $total_suppliers);
        $this->db->bind(':total_kg', $totalKg);
        $this->db->bind(':total_payment', $totalPayment);
        $this->db->execute();

        // Get the generated payment_id
        $payment_id = $this->db->lastInsertId();

        // Insert a detail row for each supplier
        foreach ($suppliers as $sid => $data) {
            $collectionCount = count($data['collections']);
            $transportCharge = $transportCost * $collectionCount;
            $finalPayment = $data['total_payment'] + $transportCharge; // add transport cost to base payment

            $this->db->query("
                INSERT INTO factory_payment_details (
                    payment_id, supplier_id, total_collections, 
                    normal_kg, super_kg, total_kg, payment_amount, transport_charge
                ) VALUES (
                    :payment_id, :supplier_id, :collections, 
                    :normal_kg, :super_kg, :total_kg, :payment_amount, :transport_charge
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
            $this->db->execute();
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


} 