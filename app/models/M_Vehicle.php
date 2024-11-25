<?php
// app/models/M_Vehicle.php
class M_Vehicle {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllVehicles() {
        $this->db->query("SELECT * FROM vehicles");
        return $this->db->resultSet();
    }

    public function getTotalVehicles() {
        $this->db->query("SELECT COUNT(*) as total FROM vehicles");
        $result = $this->db->single();
        return $result->total;
    }

    public function getAvailableVehicles() {
        $this->db->query("SELECT COUNT(*) as available FROM vehicles WHERE status = 'Available'");
        $result = $this->db->single();
        return $result->available;
    }

    public function getUnassignedVehicles() {
        $this->db->query("SELECT * FROM vehicles WHERE vehicle_id NOT IN (SELECT vehicle_id FROM routes)");
        return $this->db->resultSet();
    }

    public function getVehicleDetails() {
        $this->db->query("SELECT 
            v.*,
            vd.file_path
            FROM vehicles v
            LEFT JOIN vehicle_documents vd ON v.vehicle_id = vd.vehicle_id 
            AND vd.document_type = 'Image'");
        
        return $this->db->resultSet();
    }

    public function getVehicleTypeStats() {
        $this->db->query("SELECT 
            vehicle_type,
            COUNT(*) as count
            FROM vehicles
            WHERE status = 'Available'
            GROUP BY vehicle_type");
        
        // Debug output
        $result = $this->db->resultSet();
        // var_dump($result);  // Temporary debug line
        return $result;
    }

    public function createVehicle($data) {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO vehicles (
                license_plate, vehicle_type, engine_number, chassis_number,
                status, `condition`, make, model, manufacturing_year,
                color, fuel_type, mileage, capacity, seating_capacity,
                owner_name, owner_contact, registration_date,
                last_serviced_date, last_maintenance, next_maintenance
            ) VALUES (
                :license_plate, :vehicle_type, :engine_number, :chassis_number,
                :status, :condition, :make, :model, :manufacturing_year,
                :color, :fuel_type, :mileage, :capacity, :seating_capacity,
                :owner_name, :owner_contact, :registration_date,
                :last_serviced_date, :last_maintenance, :next_maintenance
            )";

            $this->db->query($sql);

            // Bind values with correct data types and NULL handling
            $this->db->bind(':license_plate', $data['license_plate']);
            $this->db->bind(':vehicle_type', $data['vehicle_type']);
            $this->db->bind(':engine_number', $data['engine_number']);
            $this->db->bind(':chassis_number', $data['chassis_number']);
            $this->db->bind(':status', $data['status'] ?: 'Available');
            $this->db->bind(':condition', $data['condition'] ?: NULL);
            $this->db->bind(':make', $data['make'] ?: NULL);
            $this->db->bind(':model', $data['model'] ?: NULL);
            $this->db->bind(':manufacturing_year', $data['manufacturing_year'] ?: NULL);
            $this->db->bind(':color', $data['color'] ?: NULL);
            $this->db->bind(':fuel_type', $data['fuel_type'] ?: 'Petrol');
            $this->db->bind(':mileage', $data['mileage'] ?: NULL);
            $this->db->bind(':capacity', $data['capacity'] ?: NULL);
            $this->db->bind(':seating_capacity', $data['seating_capacity'] ?: NULL);
            $this->db->bind(':owner_name', $data['owner_name'] ?: NULL);
            $this->db->bind(':owner_contact', $data['owner_contact'] ?: NULL);
            $this->db->bind(':registration_date', $data['registration_date'] ?: NULL);
            $this->db->bind(':last_serviced_date', $data['last_serviced_date'] ?: NULL);
            $this->db->bind(':last_maintenance', $data['last_maintenance'] ?: NULL);
            $this->db->bind(':next_maintenance', $data['next_maintenance'] ?: NULL);

            $result = $this->db->execute();
            $this->db->commit();

            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating vehicle: " . $e->getMessage());
            return false;
        }
    }

    public function updateVehicle($data) {
        try {
            if (!isset($data->vehicle_id)) {
                return "Vehicle ID is required";
            }

            $this->db->beginTransaction();

            $sql = "UPDATE vehicles SET ";
            $params = [];
            $updates = [];

            $validFields = [
                'license_plate', 'status', 'vehicle_type', 'owner_name', 
                'owner_contact', 'capacity', 'seating_capacity', 
                'insurance_expiry_date', 'road_tax_expiry_date', 'color',
                'engine_number', 'chassis_number', 'condition',
                'last_serviced_date', 'last_maintenance', 'next_maintenance',
                'mileage', 'fuel_type', 'registration_date'
            ];

            foreach ($validFields as $field) {
                if (isset($data->$field) && $data->$field !== '') {
                    $updates[] = "`$field` = :$field";
                    $params[$field] = $data->$field;
                }
            }

            if (empty($updates)) {
                $this->db->rollBack();
                return "No fields to update";
            }

            $sql .= implode(', ', $updates);
            $sql .= " WHERE vehicle_id = :vehicle_id";
            $params['vehicle_id'] = $data->vehicle_id;

            // Log the query for debugging
            error_log("Update Query: $sql");
            error_log("Parameters: " . print_r($params, true));

            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute($params);

            if (!$success) {
                $error = $stmt->errorInfo();
                $this->db->rollBack();
                return "Database error: " . ($error[2] ?? 'Unknown error');
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("PDO Error: " . $e->getMessage());
            return "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("General Error: " . $e->getMessage());
            return "Error: " . $e->getMessage();
        }
    }

    public function getVehicleById($id) {
        $this->db->query("SELECT 
            v.*, 
            vd.file_path
            FROM vehicles v
            LEFT JOIN vehicle_documents vd ON v.vehicle_id = vd.vehicle_id 
            AND vd.document_type = 'Image'
            WHERE v.vehicle_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function checkLicensePlateExists($licensePlate) {
        $this->db->query('SELECT COUNT(*) as count FROM vehicles WHERE license_plate = :license_plate');
        $this->db->bind(':license_plate', $licensePlate);
        $row = $this->db->single();
        return $row->count > 0;
    }

    public function deleteVehicle($id) {
        try {
            $this->db->beginTransaction();

            // Delete vehicle image if exists
            $vehicle = $this->getVehicleById($id);
            if ($vehicle) {
                $imagePath = APPROOT . '/../public/uploads/vehicle_photos/' . $vehicle->license_plate . '.jpg';
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete vehicle record
            $this->db->query('DELETE FROM vehicles WHERE vehicle_id = :id');
            $this->db->bind(':id', $id);
            
            $result = $this->db->execute();
            $this->db->commit();
            
            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error deleting vehicle: " . $e->getMessage());
            return false;
        }
    }

    public function isVehicleInUse($vehicle_id) {
        // Check if vehicle is assigned to any active collection schedules
        $this->db->query('SELECT COUNT(*) as count FROM collection_schedules WHERE vehicle_id = :vehicle_id AND status = "active"');
        $this->db->bind(':vehicle_id', $vehicle_id);
        $result = $this->db->single();
        
        return $result->count > 0;
    }

    public function getVehicleDocuments($vehicleId) {
        $this->db->query("SELECT * FROM vehicle_document WHERE vehicle_id = :vehicle_id");
        $this->db->bind(':vehicle_id', $vehicleId);
        return $this->db->resultSet();
    }

    public function getMaintenanceRecords($vehicleId) {
        $this->db->query("SELECT * FROM maintenance_records WHERE vehicle_id = :vehicle_id ORDER BY maintenance_date DESC");
        $this->db->bind(':vehicle_id', $vehicleId);
        return $this->db->resultSet();
    }

}
?>
