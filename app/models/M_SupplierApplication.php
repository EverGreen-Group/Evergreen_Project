<?php
class M_SupplierApplication {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createApplication($data, $documents) {
        $this->db->beginTransaction();
        
        try {
            // 1. Insert main application
            $this->db->query('INSERT INTO supplier_applications (user_id, status) 
                             VALUES (:user_id, :status)');
            
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':status', 'pending'); // default is pending!! 
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert main application");
            }
            
            $applicationId = $this->db->lastInsertId();
            error_log("Application ID generated: " . $applicationId);

            // 2. Insert location data
            $this->db->query('UPDATE supplier_applications 
                             SET latitude = :latitude, longitude = :longitude,
                                 tea_cultivation_area = :tea_cultivation_area,
                                 plant_age = :plant_age,
                                 monthly_production = :monthly_production
                             WHERE application_id = :application_id');
            
            $this->db->bind(':application_id', $applicationId);
            $this->db->bind(':latitude', $data['location']['latitude']);
            $this->db->bind(':longitude', $data['location']['longitude']);
            $this->db->bind(':tea_cultivation_area', $data['cultivation']['tea_cultivation_area']);
            $this->db->bind(':plant_age', $data['cultivation']['plant_age']);
            $this->db->bind(':monthly_production', $data['cultivation']['monthly_production']);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert location and cultivation data");
            }

            // 5. Handle document uploads
            foreach ($documents as $type => $file) {
                try {
                    // Log the document processing
                    error_log("Processing document: " . $type);
                    
                    // Upload the document
                    $filePath = $this->uploadDocument($file, $type);
                    error_log("File uploaded successfully to: " . $filePath);
                    
                    // Insert document record
                    $this->db->query('INSERT INTO application_documents 
                        (application_id, document_type, file_path) 
                        VALUES 
                        (:application_id, :document_type, :file_path)');
                    
                    $this->db->bind(':application_id', $applicationId);
                    $this->db->bind(':document_type', $type);
                    $this->db->bind(':file_path', $filePath);
                    
                    if (!$this->db->execute()) {
                        throw new Exception("Failed to insert document record for: " . $type);
                    }
                    
                } catch (Exception $e) {
                    error_log("Error processing document {$type}: " . $e->getMessage());
                    throw $e;
                }
            }

            $this->db->commit();
            error_log("Transaction committed successfully");
            return true;

        } catch (Exception $e) {
            error_log("Error in createApplication: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Upload document and return the file path
     */
    private function uploadDocument($file, $type) {
        $uploadDir = APPROOT . '/../public/uploads/documents/' . $type . '/';
        
        error_log("Document upload directory: " . $uploadDir);
        
        if (!file_exists($uploadDir)) {
            $success = mkdir($uploadDir, 0777, true);
            if (!$success) {
                error_log("Failed to create document directory: " . $uploadDir);
                throw new Exception("Failed to create document upload directory");
            }
            chmod($uploadDir, 0777);
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            error_log("Failed to move uploaded document. Error: " . error_get_last()['message']);
            throw new Exception("Failed to upload document");
        }
        
        return 'uploads/documents/' . $type . '/' . $fileName;
    }

    // discontinued,, must ask thisarani to imprve it later
    public function getApplicationByUserId($userId) {
        $this->db->query('
            SELECT 
                sa.*
            FROM supplier_applications sa

            WHERE sa.user_id = :user_id
            GROUP BY sa.application_id
            ORDER BY sa.created_at DESC
            LIMIT 1
        ');

        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();


        return $result;
    }

    private function getStatusText($status) {
        switch ($status) {
            case 'pending':
                return 'Your application is under review';
            case 'approved':
                return 'Your application has been approved';
            case 'rejected':
                return 'Your application has been rejected';
            default:
                return 'Unknown status';
        }
    }

    // Optional: Add a method to get application documents
    public function getApplicationDocuments($applicationId) {
        $this->db->query('
            SELECT *
            FROM application_documents
            WHERE application_id = :application_id
        ');
        
        $this->db->bind(':application_id', $applicationId);
        
        return $this->db->resultSet();
    }

    // Add method to retrieve bank details
    public function getBankInfo($applicationId) {
        $this->db->query('
            SELECT * FROM supplier_bank_info 
            WHERE application_id = :application_id
        ');
        
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function getProfileInfo($userId) {
        $this->db->query('
            SELECT * FROM profiles 
            WHERE user_id = :user_id
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }


    public function hasApplied($user_id) {
        // Add debug logging
        error_log("Checking hasApplied for user_id: " . $user_id);
        
        $this->db->query('SELECT COUNT(*) as count FROM supplier_applications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        $hasApplied = ($result->count > 0);
        
        // Add debug logging
        error_log("hasApplied result: " . ($hasApplied ? 'true' : 'false'));
        
        return $hasApplied;
    }

    // Add this method to your existing class
    public function getAllApplications() {
        $this->db->query('
        SELECT supplier_applications.*, users.email, managers.manager_id, 
               CONCAT(profiles.first_name, \' \', profiles.last_name) AS manager_name,
               profiles.image_path AS manager_image
        FROM supplier_applications
        LEFT JOIN users ON supplier_applications.user_id = users.user_id
        LEFT JOIN managers ON managers.manager_id = supplier_applications.reviewed_by
        LEFT JOIN profiles ON profiles.profile_id = managers.profile_id
        ');
        
        return $this->db->resultSet();
    }

    public function getApprovedPendingRoleApplications() {
        $this->db->query('SELECT 
            sa.application_id,
            CONCAT(u.first_name, " ", u.last_name) as user_name
            FROM supplier_applications sa
            JOIN users u ON sa.user_id = u.user_id
            WHERE sa.status = "approved" 
            AND u.role_id = 7
            ORDER BY sa.created_at DESC');
        
        return $this->db->resultSet();
    }


    public function getWaterSources($applicationId) {
        $this->db->query('SELECT * FROM application_water_sources WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->resultSet();
    }

    public function getTeaVarieties($applicationId) {
        $this->db->query('SELECT * FROM application_tea_varieties WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->resultSet();
    }

    public function getTeaDetails($applicationId) {
        $this->db->query('SELECT * FROM application_tea_details WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function getInfrastructure($applicationId) {
        $this->db->query('SELECT * FROM application_infrastructure 
                          WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function getStructures($applicationId) {
        $this->db->query('SELECT * FROM application_structures 
                          WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->resultSet();
    }

    public function getPropertyDetails($applicationId) {
        $this->db->query('SELECT * FROM application_property_details 
                          WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function getApplicationById($applicationId) {
        $this->db->query("
        SELECT supplier_applications.*, users.email, managers.manager_id, CONCAT(profiles.first_name, ' ', profiles.last_name) AS manager_name 
        FROM supplier_applications
        LEFT JOIN users ON supplier_applications.user_id = users.user_id
        LEFT JOIN managers ON managers.manager_id = supplier_applications.reviewed_by
        LEFT JOIN profiles ON profiles.profile_id = managers.profile_id
        WHERE application_id = :application_id
        ");
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function getBankInfoByApplicationId($applicationId) {
        $this->db->query("SELECT * FROM supplier_bank_info WHERE application_id = :application_id");
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function updateApplicationStatus($applicationId, $reviewedBy, $status) {
        $this->db->query('
            UPDATE supplier_applications 
            SET reviewed_by = :reviewed_by, status = :status 
            WHERE application_id = :application_id
        ');
        $this->db->bind(':reviewed_by', $reviewedBy);
        $this->db->bind(':status', $status);
        $this->db->bind(':application_id', $applicationId);
        
        return $this->db->execute();
    }

    public function approveApplication($applicationId) {
        $supplierModel = new M_SupplierApplication();
        if ($supplierModel->updateApplicationStatus($applicationId, 'approved')) {
            flash('application_message', 'Application has been approved successfully');
        } else {
            flash('application_message', 'Something went wrong while approving the application', 'alert alert-danger');
        }
        redirect('suppliermanager/applications');
    }
    
    public function rejectApplication($applicationId) {
        $supplierModel = new M_SupplierApplication();
        if ($supplierModel->updateApplicationStatus($applicationId, 'rejected')) {
            flash('application_message', 'Application has been rejected successfully');
        } else {
            flash('application_message', 'Something went wrong while rejecting the application', 'alert alert-danger');
        }
        redirect('suppliermanager/applications');
    }

    public function createProfileAndApplication($profileData, $applicationData, $profilePhoto, $documents) {
        $this->db->beginTransaction();
        
        try {
            
            // Uploading the photo
            $profilePhotoPath = null;
            if (isset($profilePhoto) && $profilePhoto['error'] === UPLOAD_ERR_OK) {
                $profilePhotoPath = $this->uploadProfilePhoto($profilePhoto);
            }
            
            // Inserting the path to the application with address
            $this->db->query('INSERT INTO supplier_applications (user_id, status, profile_photo, address) 
                             VALUES (:user_id, :status, :profile_photo, :address)');
            
            $this->db->bind(':user_id', $applicationData['user_id']);
            $this->db->bind(':status', 'pending');
            $this->db->bind(':profile_photo', $profilePhotoPath);
            $this->db->bind(':address', $profileData['address']); 
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert main application");
            }
            
            $applicationId = $this->db->lastInsertId();
            
            // Tea cultivation and location
            $this->db->query('UPDATE supplier_applications 
                             SET latitude = :latitude, longitude = :longitude,
                                 tea_cultivation_area = :tea_cultivation_area,
                                 plant_age = :plant_age,
                                 monthly_production = :monthly_production
                             WHERE application_id = :application_id');
            
            $this->db->bind(':application_id', $applicationId);
            $this->db->bind(':latitude', $applicationData['location']['latitude']);
            $this->db->bind(':longitude', $applicationData['location']['longitude']);
            $this->db->bind(':tea_cultivation_area', $applicationData['cultivation']['tea_cultivation_area']);
            $this->db->bind(':plant_age', $applicationData['cultivation']['plant_age']);
            $this->db->bind(':monthly_production', $applicationData['cultivation']['monthly_production']);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to update application with location and cultivation data");
            }

            // Document uploads
            foreach ($documents as $type => $file) {
                try {
                    $filePath = $this->uploadDocument($file, $type);
                    error_log("Document uploaded successfully to: " . $filePath);
                    
                    $safeApplicationId = (int)$applicationId; 
                    $safeType = "'" . addslashes($type) . "'"; 
                    $safeFilePath = "'" . addslashes($filePath) . "'";
                    
                    $sql = "INSERT INTO application_documents 
                            (application_id, document_type, file_path) 
                            VALUES 
                            ($safeApplicationId, $safeType, $safeFilePath)";
                    
                    $result = $this->db->executeRawQuery($sql);
                    
                    if (!$result) {
                        error_log("Failed to insert document with raw SQL");
                        throw new Exception("Failed to insert document record for: " . $type);
                    }
                    
                    error_log("Document record inserted successfully with raw SQL for: " . $type);
                } catch (Exception $e) {
                    error_log("Error processing document " . $type . ": " . $e->getMessage());
                    throw $e;
                }
            }
        
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in createProfileAndApplication: " . $e->getMessage());
            throw $e;
        }
    }

    // Helper method to upload profile photo
    private function uploadProfilePhoto($file) {
        // Use absolute path for uploads
        $uploadDir = APPROOT . '/../public/uploads/profile_photos/';
        
        error_log("Upload directory: " . $uploadDir);
        
        // Create directory with proper permissions if it doesn't exist
        if (!file_exists($uploadDir)) {
            $success = mkdir($uploadDir, 0777, true);
            if (!$success) {
                error_log("Failed to create directory: " . $uploadDir);
                throw new Exception("Failed to create upload directory");
            }
            // Set permissions explicitly after creation
            chmod($uploadDir, 0777);
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        error_log("Attempting to move uploaded file to: " . $targetPath);
        
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
            throw new Exception("Failed to upload profile photo");
        }
        
        // Return relative path for database storage
        return 'uploads/profile_photos/' . $fileName;
    }

}
