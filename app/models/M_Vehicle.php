<?php
// app/models/M_Vehicle.php
class M_Vehicle {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllAvailableVehicles() {
        $this->db->query("SELECT v.* FROM vehicles v where status = 'Available' AND is_deleted = 0");
        return $this->db->resultSet();
    }

    public function getTotalVehicles() {
        $this->db->query("SELECT COUNT(*) as total FROM vehicles");
        $result = $this->db->single();
        return $result->total;
    }

    public function getAvailableVehicles() {
        $this->db->query("SELECT COUNT(*) as available FROM vehicles WHERE status = 'Available' AND is_deleted = 0");
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
                color, capacity, image_path
            ) VALUES (
                :license_plate, :vehicle_type,
                :status, :make, :model, :manufacturing_year,
                :color, :capacity, :image_path
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
            $this->db->bind(':image_path', $data['image_path'] ?: NULL);

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
        // Prepare the SQL statement
        $this->db->query("UPDATE vehicles SET 
            vehicle_type = :vehicle_type,
            make = :make,
            model = :model,
            manufacturing_year = :manufacturing_year,
            color = :color,
            capacity = :capacity,
            image_path = :image_path
            WHERE license_plate = :license_plate");

        // Bind the parameters
        $this->db->bind(':license_plate', $data['license_plate']); // Use the existing license plate
        $this->db->bind(':vehicle_type', $data['vehicle_type']);
        $this->db->bind(':make', $data['make']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':manufacturing_year', $data['manufacturing_year']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':capacity', $data['capacity']);
        
        // Bind the image path if it exists
        if (isset($data['image_path'])) {
            $this->db->bind(':image_path', $data['image_path']);
        } else {
            // If no new image is uploaded, bind a default value or keep it unchanged
            $this->db->bind(':image_path', $data['current_image_path']); // Assuming you have the current image path
        }

        // Execute the query
        return $this->db->execute(); // Return the result of the execution
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

    public function getVehicleByLicensePlate($license_plate) {
        // Prepare the SQL statement
        $this->db->query("SELECT * FROM vehicles WHERE license_plate = :license_plate");
        $this->db->bind(':license_plate', $license_plate);
    
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
    
    public function getVehicleIdByScheduleId($id) {
        $this->db->query('
        SELECT v.* FROM collection_schedules cs 
        JOIN routes r ON cs.route_id = r.route_id
        JOIN vehicles v ON r.vehicle_id = v.vehicle_id
        WHERE cs.schedule_id = :schedule_id
        ');
        $this->db->bind(':schedule_id', $id);
        return $this->db->single();
    }  
    public function deleteVehicle($license_plate) {
        // First, fetch the vehicle details to get the image path
        $this->db->query("SELECT image_path FROM vehicles WHERE license_plate = :license_plate");
        $this->db->bind(':license_plate', $license_plate);
        $vehicle = $this->db->single();

        // Check if the vehicle exists and has an image
        if ($vehicle && !empty($vehicle->image_path)) {
            // Define the full path to the image
            $imagePath = dirname(APPROOT) . '/public/uploads/' . $vehicle->image_path;

            // Delete the image file from the server
            if (file_exists($imagePath)) {
                unlink($imagePath); // Remove the image file
            }
        }

        // Now delete the vehicle record from the database
        $this->db->query("DELETE FROM vehicles WHERE license_plate = :license_plate");
        $this->db->bind(':license_plate', $license_plate);

        return $this->db->execute();
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
            LEFT JOIN routes r ON v.vehicle_id = r.vehicle_id AND r.day = :day AND r.is_deleted = 0
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

    public function getVehicleLocation($vehicleId) { // TESTED
        $this->db->query('
            SELECT latitude, longitude 
            FROM vehicles 
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        return $this->db->single();

    }

    public function getFilteredVehicles($license_plate = null, $vehicle_type = null, $capacity = null, $make = null, $model = null, $manufacturing_year = null) {
        $sql = "SELECT * FROM vehicles WHERE 1=1 AND is_deleted = 0"; // Start with a base query

        // Build the query based on provided filters
        if (!empty($license_plate)) {
            $sql .= " AND license_plate = :license_plate";
        }
        if (!empty($vehicle_type)) {
            $sql .= " AND vehicle_type = :vehicle_type";
        }
        if (!empty($capacity)) {
            $sql .= " AND capacity >= :capacity";
        }
        if (!empty($make)) {
            $sql .= " AND make = :make";
        }
        if (!empty($model)) {
            $sql .= " AND model = :model";
        }
        if (!empty($manufacturing_year)) {
            $sql .= " AND manufacturing_year = :manufacturing_year";
        }

        // Prepare the statement
        $this->db->query($sql);

        // Bind parameters if they were set
        if (!empty($license_plate)) {
            $this->db->bind(':license_plate', $license_plate);
        }
        if (!empty($vehicle_type)) {
            $this->db->bind(':vehicle_type', $vehicle_type);
        }
        if (!empty($capacity)) {
            $this->db->bind(':capacity', $capacity);
        }
        if (!empty($make)) {
            $this->db->bind(':make', $make);
        }
        if (!empty($model)) {
            $this->db->bind(':model', $model);
        }
        if (!empty($manufacturing_year)) {
            $this->db->bind(':manufacturing_year', $manufacturing_year);
        }

        // Execute the query and return the results
        return $this->db->resultSet();
    }

    public function addVehicle($data) {
        // Prepare the SQL statement
        $this->db->query("INSERT INTO vehicles (license_plate, status, capacity, vehicle_type, make, model, manufacturing_year, color) 
                          VALUES (:license_plate, :status, :capacity, :vehicle_type, :make, :model, :manufacturing_year, :color)");

        // Bind the parameters
        $this->db->bind(':license_plate', $data['license_plate']);
        $this->db->bind(':status', 'Available'); // Default status
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':vehicle_type', $data['vehicle_type']);
        $this->db->bind(':make', $data['make']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':manufacturing_year', $data['manufacturing_year']);
        $this->db->bind(':color', $data['color']);

        // Execute the query
        if ($this->db->execute()) {
            return true; // Insertion successful
        } else {
            return false; // Insertion failed
        }
    }

    public function isLicensePlateTaken($license_plate) {
        $this->db->query("SELECT COUNT(*) as count FROM vehicles WHERE license_plate = :license_plate AND is_deleted = 0");
        $this->db->bind(':license_plate', $license_plate);
        
        $result = $this->db->single();
        
        // Return true if the count is greater than 0, meaning the license plate is taken
        return $result->count > 0;
    }

   // FOR VEHICLE HISTORY

    public function getVehicleCollectionHistory($vehicle_id) {
        $this->db->query("
            SELECT 
                c.collection_id, 
                c.status, 
                c.start_time, 
                c.end_time, 
                c.total_quantity, 
                c.bags,
                cs.day,
                cs.start_time AS schedule_start_time,
                cs.end_time AS schedule_end_time,
                r.route_name,
                d.first_name AS driver_first_name,
                d.last_name AS driver_last_name
            FROM 
                collections c
            LEFT JOIN 
                collection_schedules cs ON c.schedule_id = cs.schedule_id
            LEFT JOIN 
                routes r ON cs.route_id = r.route_id
            LEFT JOIN 
                drivers d ON cs.driver_id = d.driver_id
            WHERE 
                c.vehicle_id = :vehicle_id
            ORDER BY 
                c.start_time DESC
        ");
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        return $this->db->resultSet();
    }

    public function getUpcomingSchedulesForVehicle($vehicle_id) {
        $this->db->query("
            SELECT 
                cs.schedule_id, 
                cs.day, 
                cs.start_time, 
                cs.end_time,
                r.route_name,
                d.first_name AS driver_first_name,
                d.last_name AS driver_last_name
            FROM 
                collection_schedules cs
            LEFT JOIN 
                routes r ON cs.route_id = r.route_id
            LEFT JOIN 
                drivers d ON cs.driver_id = d.driver_id
            WHERE 
                cs.is_active = 1 
                AND cs.is_deleted = 0
                AND cs.schedule_id IN (
                    SELECT DISTINCT schedule_id 
                    FROM collections 
                    WHERE vehicle_id = :vehicle_id
                )
            ORDER BY 
                CASE cs.day
                    WHEN 'Monday' THEN 1
                    WHEN 'Tuesday' THEN 2
                    WHEN 'Wednesday' THEN 3
                    WHEN 'Thursday' THEN 4
                    WHEN 'Friday' THEN 5
                    WHEN 'Saturday' THEN 6
                    WHEN 'Sunday' THEN 7
                END
        ");
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        return $this->db->resultSet();
    }

    public function isVehicleInSchedule($vehicle_id) {
        $this->db->query("
        SELECT COUNT(*) as count 
            FROM collection_schedules cs
            LEFT JOIN routes r ON cs.route_id = r.route_id
            LEFT JOIN vehicles v ON v.vehicle_id = r.vehicle_id
            WHERE v.vehicle_id = :vehicle_id
        ");
        $this->db->bind(':vehicle_id', $vehicle_id);
        $result = $this->db->single();
        return $result->count > 0;
    }

    public function markAsDeleted($vehicle_id) {
        $this->db->query("UPDATE vehicles SET is_deleted = 1 WHERE vehicle_id = :vehicle_id");
        $this->db->bind(':vehicle_id', $vehicle_id);
        return $this->db->execute();
    }

}
?>