<?php
// app/models/M_Vehicle.php
class M_Vehicle {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllVehicles() {
        $this->db->query("SELECT vehicle_id, license_plate, vehicle_type, capacity, status, owner_name, last_maintenance, next_maintenance, fuel_type FROM vehicles");
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

    public function getVehicleDetails() {
        $this->db->query("SELECT 
            v.*
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
                license_plate, vehicle_type,
                status, make, model, manufacturing_year,
                color, capacity
            ) VALUES (
                :license_plate, :vehicle_type,
                :status, :make, :model, :manufacturing_year,
                :color, :capacity
            )";

            $this->db->query($sql);

            // Bind values with correct data types and NULL handling
            $this->db->bind(':license_plate', $data['license_plate']);
            $this->db->bind(':vehicle_type', $data['vehicle_type']);
            $this->db->bind(':status', $data['status'] ?: 'Available');
            $this->db->bind(':make', $data['make'] ?: NULL);
            $this->db->bind(':model', $data['model'] ?: NULL);
            $this->db->bind(':manufacturing_year', $data['manufacturing_year'] ?: NULL);
            $this->db->bind(':color', $data['color'] ?: NULL);
            $this->db->bind(':capacity', $data['capacity'] ?: NULL);

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

    public function getVehicleByRouteId($id) {
        $this->db->query('SELECT * FROM vehicles v JOIN routes r ON r.vehicle_id = v.vehicle_id WHERE route_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getVehicleByVehicleId($id) {
        $this->db->query('SELECT * FROM vehicles WHERE vehicle_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function checkLicensePlateExists($licensePlate) {
        $this->db->query('SELECT COUNT(*) as count FROM vehicles WHERE license_plate = :license_plate');
        $this->db->bind(':license_plate', $licensePlate);
        $row = $this->db->single();
        return $row->count > 0;
    }

    public function getVehicleById($id) {
        $this->db->query('SELECT * FROM vehicles WHERE vehicle_id = :vehicle_id');
        $this->db->bind(':vehicle_id', $id);
        return $this->db->single();
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

    public function getAvailableVehiclesByDay($day) {
        $this->db->query('
            SELECT v.* 
            FROM vehicles v
            LEFT JOIN routes r ON v.vehicle_id = r.vehicle_id AND r.day = :day
            WHERE r.vehicle_id IS NULL
        ');
        $this->db->bind(':day', $day);
        
        return $this->db->resultSet();
    }

    public function updateLocation($vehicleId, $latitude, $longitude) {
        $this->db->query('
            UPDATE vehicles 
            SET 
                latitude = :latitude,
                longitude = :longitude
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        $this->db->bind(':latitude', $latitude);
        $this->db->bind(':longitude', $longitude);
        
        return $this->db->execute();
    }

    public function getVehicleLocation($vehicleId) {
        $this->db->query('
            SELECT latitude, longitude 
            FROM vehicles 
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        $result = $this->db->single();
        
        return $result ? [
            'lat' => (float)$result->latitude,
            'lng' => (float)$result->longitude
        ] : null;
    }

}
?>
