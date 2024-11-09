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
            v.vehicle_id, 
            v.license_plate, 
            v.status, 
            v.owner_name, 
            v.owner_contact,
            v.capacity, 
            v.vehicle_type, 
            v.insurance_expiry_date, 
            v.road_tax_expiry_date,
            v.color, 
            v.engine_number, 
            v.chassis_number, 
            v.seating_capacity, 
            v.`condition`, 
            v.last_serviced_date, 
            v.last_maintenance, 
            v.next_maintenance, 
            v.mileage,
            v.fuel_type, 
            v.registration_date,
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
            // Check if license plate already exists
            if ($this->checkLicensePlateExists($data['license_plate'])) {
                return "Error: License plate '" . $data['license_plate'] . "' already exists";
            }

            $this->db->beginTransaction();

            // First insert the vehicle
            $this->db->query('INSERT INTO vehicles (
                license_plate, status, vehicle_type, owner_name, 
                owner_contact, capacity, seating_capacity, 
                insurance_expiry_date, road_tax_expiry_date, 
                color, engine_number, chassis_number, 
                `condition`, last_serviced_date, last_maintenance, 
                next_maintenance, mileage, fuel_type, 
                registration_date
            ) VALUES (
                :license_plate, :status, :vehicle_type, :owner_name,
                :owner_contact, :capacity, :seating_capacity,
                :insurance_expiry_date, :road_tax_expiry_date,
                :color, :engine_number, :chassis_number,
                :condition, :last_serviced_date, :last_maintenance,
                :next_maintenance, :mileage, :fuel_type,
                :registration_date
            )');

            // Bind values
            $this->db->bind(':license_plate', $data['license_plate']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':vehicle_type', $data['vehicle_type']);
            $this->db->bind(':owner_name', $data['owner_name']);
            $this->db->bind(':owner_contact', $data['owner_contact']);
            $this->db->bind(':capacity', $data['capacity']);
            $this->db->bind(':seating_capacity', $data['seating_capacity']);
            $this->db->bind(':insurance_expiry_date', $data['insurance_expiry_date']);
            $this->db->bind(':road_tax_expiry_date', $data['road_tax_expiry_date']);
            $this->db->bind(':color', $data['color']);
            $this->db->bind(':engine_number', $data['engine_number']);
            $this->db->bind(':chassis_number', $data['chassis_number']);
            $this->db->bind(':condition', $data['condition']);
            $this->db->bind(':last_serviced_date', $data['last_serviced_date']);
            $this->db->bind(':last_maintenance', $data['last_maintenance']);
            $this->db->bind(':next_maintenance', $data['next_maintenance']);
            $this->db->bind(':mileage', $data['mileage']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':registration_date', $data['registration_date']);

            // Execute vehicle insert
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert vehicle record");
            }

            // Get the newly created vehicle ID
            $vehicleId = $this->db->lastInsertId();

            // Now insert the document record
            $this->db->query('INSERT INTO vehicle_documents (
                vehicle_id, document_type, file_path
            ) VALUES (
                :vehicle_id, :document_type, :file_path
            )');

            $this->db->bind(':vehicle_id', $vehicleId);
            $this->db->bind(':document_type', 'Image');
            $this->db->bind(':file_path', 'https://i.ikman-st.com/isuzu-elf-freezer-105-feet-2014-for-sale-kalutara/e1f96b60-f1f5-488a-9cbc-620cba3f5f77/620/466/fitted.jpg');

            if (!$this->db->execute()) {
                throw new Exception("Failed to create vehicle document");
            }

            $this->db->commit();
            error_log("Vehicle and document created successfully");
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Vehicle Creation Error: " . $e->getMessage());
            return "Error: " . $e->getMessage();
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

    public function deleteVehicle($vehicleId) {
        try {
            $this->db->beginTransaction();

            // First delete related documents
            $this->db->query('DELETE FROM vehicle_documents WHERE vehicle_id = :vehicle_id');
            $this->db->bind(':vehicle_id', $vehicleId);
            $this->db->execute();

            // Then delete the vehicle
            $this->db->query('DELETE FROM vehicles WHERE vehicle_id = :vehicle_id');
            $this->db->bind(':vehicle_id', $vehicleId);
            
            if ($this->db->execute()) {
                $this->db->commit();
                return true;
            }
            
            throw new Exception("Failed to delete vehicle");

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

}
?>
