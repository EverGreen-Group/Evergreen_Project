<?php

class M_Batch {

    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }

    public function addBatch($batchData) {
        $this->db->query('INSERT INTO batches (start_time, end_time, total_output_kg, total_wastage_kg, created_at) VALUES (:start_time, :end_time, :total_output, :total_wastage, NOW())');

        // Bind parameters
        $this->db->bind(':start_time', $batchData['start_time']);
        $this->db->bind(':end_time', $batchData['end_time']);
        $this->db->bind(':total_output', $batchData['total_output']);
        $this->db->bind(':total_wastage', $batchData['total_wastage']);

        // Execute the query and return the result
        return $this->db->execute();
    }

    public function getBatchesWithoutEndTime() {
        $this->db->query('SELECT * FROM batches WHERE end_time IS NULL');
        return $this->db->resultSet();
    }

    public function addIngredient($ingredientData) {
        $this->db->query('INSERT INTO batch_ingredients (batch_id, leaf_type_id, quantity_used_kg, added_at) VALUES (:batch_id, :leaf_type_id, :quantity_used_kg, NOW())');
        $this->db->bind(':batch_id', $ingredientData['batch_id']);
        $this->db->bind(':leaf_type_id', $ingredientData['leaf_type_id']);
        $this->db->bind(':quantity_used_kg', $ingredientData['quantity_used_kg']);
        return $this->db->execute();
    }

    public function addOutput($outputData) {
        $this->db->query('INSERT INTO processed_tea (batch_id, leaf_type_id, grading_id, output_kg, processed_at) VALUES (:batch_id, :leaf_type_id, :grading_id, :output_kg, NOW())');
        $this->db->bind(':batch_id', $outputData['batch_id']);
        $this->db->bind(':leaf_type_id', $outputData['leaf_type_id']);
        $this->db->bind(':grading_id', $outputData['grading_id']);
        $this->db->bind(':output_kg', $outputData['output_kg']);
        return $this->db->execute();
    }

    public function addMachineUsage($machineUsageData) {
        $this->db->query('INSERT INTO machine_usage (batch_id, machine_id, operator_id, start_time, end_time, notes) VALUES (:batch_id, :machine_id, :operator_id, NOW(), NULL, :notes)');
        $this->db->bind(':batch_id', $machineUsageData['batch_id']);
        $this->db->bind(':machine_id', $machineUsageData['machine_id']);
        $this->db->bind(':operator_id', $machineUsageData['operator_id']);
        $this->db->bind(':notes', $machineUsageData['notes']);
        return $this->db->execute();
    }

    public function getBatchDetails($batchId) {
        // Fetch batch information
        $this->db->query('SELECT * FROM batches WHERE batch_id = :batch_id');
        $this->db->bind(':batch_id', $batchId);
        $batch = $this->db->single();

        // Fetch ingredients
        $this->db->query('SELECT * FROM batch_ingredients WHERE batch_id = :batch_id');
        $this->db->bind(':batch_id', $batchId);
        $ingredients = $this->db->resultSet();

        // Fetch outputs
        $this->db->query('SELECT * FROM processed_tea WHERE batch_id = :batch_id');
        $this->db->bind(':batch_id', $batchId);
        $outputs = $this->db->resultSet();

        // Fetch machine usage
        $this->db->query('SELECT * FROM machine_usage WHERE batch_id = :batch_id');
        $this->db->bind(':batch_id', $batchId);
        $machineUsage = $this->db->resultSet();

        return [
            'batch' => $batch,
            'ingredients' => $ingredients,
            'outputs' => $outputs,
            'machineUsage' => $machineUsage
        ];
    }
} 