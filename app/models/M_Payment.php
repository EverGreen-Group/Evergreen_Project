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
    public function generateMonthlyPayment($year, $month, $normalLeafRate, $superLeafRate) { // Updated method signature
        // Fetch configuration values for deductions, transport cost, and leaf type rates
        $moistureDeductions = $this->getDeductionRates('moisture');
        $ageDeductions = $this->getDeductionRates('leaf_age');
        $transportCost = $this->getTransportCost();  // cost per unique collection

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
            $ratePerKg = 0; // Default to 0
            if ($bag->leaf_type_id == 1) {
                $ratePerKg = $normalLeafRate; // Use normal leaf rate
            } elseif ($bag->leaf_type_id == 2) {
                $ratePerKg = $superLeafRate; // Use super leaf rate
            }

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


        $this->db->query("
            INSERT INTO factory_payments (year, month, total_suppliers, total_kg, total_payment, normal_leaf_rate, super_leaf_rate)
            VALUES (:year, :month, :total_suppliers, :total_kg, :total_payment, :normal_leaf_rate, :super_leaf_rate)
        ");
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        $this->db->bind(':total_suppliers', $total_suppliers);
        $this->db->bind(':total_kg', $totalKg);
        $this->db->bind(':total_payment', $totalPayment);
        $this->db->bind(':normal_leaf_rate', $normalLeafRate);
        $this->db->bind(':super_leaf_rate', $superLeafRate);
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
            DELETE FROM factory_payments
            WHERE id = :payment_id
        ");
        $this->db->bind(':payment_id', $payment_id);
        return $this->db->execute();
    }


} 