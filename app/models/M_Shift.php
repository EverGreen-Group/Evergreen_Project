<?php
// app/models/M_Shift.php
class M_Shift {
    private $db;
    private $error;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllShifts() {
        $this->db->query('SELECT * FROM collection_shifts ORDER BY start_time ASC');
        return $this->db->resultSet();
    }

    public function addShift($data) {
        try {
            // Check if shift name already exists
            $this->db->query('SELECT COUNT(*) as count FROM collection_shifts WHERE shift_name = :shift_name');
            $this->db->bind(':shift_name', $data['shift_name']);
            $result = $this->db->single();
            if ($result->count > 0) {
                $this->error = "Shift name already exists";
                return false;
            }

            // Calculate shift duration
            $startTime = strtotime($data['start_time']);
            $endTime = strtotime($data['end_time']);
            $duration = ($endTime - $startTime) / 3600; // Duration in hours

            // Validate shift duration
            if ($duration > 6) {
                $this->error = "Shift duration cannot exceed 6 hours";
                return false;
            }
            if ($duration <= 0) {
                $this->error = "End time must be after start time";
                return false;
            }

            // Format times for database
            $startTimeFormatted = date('H:i:s', $startTime);
            $endTimeFormatted = date('H:i:s', $endTime);

            $this->db->query('INSERT INTO collection_shifts (shift_name, start_time, end_time) 
                             VALUES (:shift_name, :start_time, :end_time)');
            
            $this->db->bind(':shift_name', $data['shift_name']);
            $this->db->bind(':start_time', $startTimeFormatted);
            $this->db->bind(':end_time', $endTimeFormatted);

            return $this->db->execute();

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getError() {
        return $this->error;
    }

    public function updateShift($data) {
        try {
            // Check if new shift name conflicts with existing ones (excluding current shift)
            $this->db->query('SELECT COUNT(*) as count FROM collection_shifts 
                             WHERE shift_name = :shift_name AND shift_id != :shift_id');
            $this->db->bind(':shift_name', $data['shift_name']);
            $this->db->bind(':shift_id', $data['shift_id']);
            $result = $this->db->single();
            if ($result->count > 0) {
                $this->error = "Shift name already exists";
                return false;
            }

            // Validate shift duration
            $startTime = strtotime($data['start_time']);
            $endTime = strtotime($data['end_time']);
            $duration = ($endTime - $startTime) / 3600;

            if ($duration > 6) {
                $this->error = "Shift duration cannot exceed 6 hours";
                return false;
            }
            if ($duration <= 0) {
                $this->error = "End time must be after start time";
                return false;
            }

            $this->db->query('UPDATE collection_shifts 
                             SET shift_name = :shift_name, 
                                 start_time = :start_time, 
                                 end_time = :end_time 
                             WHERE shift_id = :shift_id');
            
            $this->db->bind(':shift_id', $data['shift_id']);
            $this->db->bind(':shift_name', $data['shift_name']);
            $this->db->bind(':start_time', date('H:i:s', $startTime));
            $this->db->bind(':end_time', date('H:i:s', $endTime));

            return $this->db->execute();

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function deleteShift($shift_id) {
        $this->db->query('DELETE FROM collection_shifts WHERE shift_id = :shift_id');
        $this->db->bind(':shift_id', $shift_id);
        return $this->db->execute();
    }

    public function getShiftById($shift_id) {
        $this->db->query('SELECT * FROM collection_shifts WHERE shift_id = :shift_id');
        $this->db->bind(':shift_id', $shift_id);
        return $this->db->single();
    }

    public function getTotalShifts() {
        $this->db->query('SELECT COUNT(*) as total_shifts FROM collection_shifts');
        return $this->db->single()->total_shifts;
    }


    public function isShiftNameDuplicate($shiftName) {
        $this->db->query("SELECT * FROM shifts WHERE shift_name = :shift_name");
        $this->db->bind(':shift_name', $shiftName);
        return $this->db->rowCount() > 0; // Returns true if a duplicate exists
    }

}
?>
