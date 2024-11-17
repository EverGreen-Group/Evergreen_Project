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
            $this->db->query('INSERT INTO supplier_applications (user_id, primary_phone, secondary_phone, whatsapp_number) 
                             VALUES (:user_id, :primary_phone, :secondary_phone, :whatsapp_number)');
            
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':primary_phone', $data['primary_phone']);
            $this->db->bind(':secondary_phone', $data['secondary_phone']);
            $this->db->bind(':whatsapp_number', $data['whatsapp_number']);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert main application");
            }
            
            $applicationId = $this->db->lastInsertId();
            error_log("Application ID generated: " . $applicationId);

            // 2. Insert address
            $this->db->query('INSERT INTO application_addresses 
                (application_id, line1, line2, city, district, postal_code, latitude, longitude) 
                VALUES 
                (:application_id, :line1, :line2, :city, :district, :postal_code, :latitude, :longitude)');
            
            $this->db->bind(':application_id', $applicationId);
            $this->db->bind(':line1', $data['address']['line1']);
            $this->db->bind(':line2', $data['address']['line2']);
            $this->db->bind(':city', $data['address']['city']);
            $this->db->bind(':district', $data['address']['district']);
            $this->db->bind(':postal_code', $data['address']['postal_code']);
            $this->db->bind(':latitude', $data['address']['latitude']);
            $this->db->bind(':longitude', $data['address']['longitude']);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert address");
            }

            // 3. Insert tea varieties (if present in the form data)
            if (!empty($data['teaVarieties'])) {
                $this->db->query('INSERT INTO application_tea_varieties (application_id, variety_name) 
                                 VALUES (:application_id, :variety_name)');
                
                foreach ($data['teaVarieties'] as $variety) {
                    $this->db->bind(':application_id', $applicationId);
                    $this->db->bind(':variety_name', $variety);
                    if (!$this->db->execute()) {
                        throw new Exception("Failed to insert tea variety: " . $variety);
                    }
                }
            }

            // 4. Insert property details
            if (!empty($data['property'])) {
                $this->db->query('INSERT INTO application_property_details 
                    (application_id, total_land_area, tea_cultivation_area, elevation, slope) 
                    VALUES 
                    (:application_id, :total_land_area, :tea_cultivation_area, :elevation, :slope)');
                
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':total_land_area', $data['property']['total_land_area']);
                $this->db->bind(':tea_cultivation_area', $data['property']['tea_cultivation_area']);
                $this->db->bind(':elevation', $data['property']['elevation']);
                $this->db->bind(':slope', $data['property']['slope']);
                
                if (!$this->db->execute()) {
                    throw new Exception("Failed to insert property details");
                }
            }

            // 5. Handle document uploads with better error handling
            foreach ($documents as $type => $file) {
                try {
                    // Log the document processing
                    error_log("Processing document: " . $type);
                    error_log("File details: " . print_r($file, true));
                    
                    // Upload the document
                    $filePath = $this->uploadDocument($file, $type);
                    error_log("File uploaded successfully to: " . $filePath);
                    
                    // Insert document record
                    $this->db->query('INSERT INTO application_documents 
                        (application_id, document_type, file_path) 
                        VALUES 
                        (:application_id, :document_type, :file_path)');
                    
                    $params = [
                        'application_id' => $applicationId,
                        'document_type' => $type,
                        'file_path' => $filePath
                    ];
                    
                    // Log the SQL parameters
                    error_log("SQL parameters: " . print_r($params, true));
                    
                    $this->db->bind(':application_id', $params['application_id']);
                    $this->db->bind(':document_type', $params['document_type']);
                    $this->db->bind(':file_path', $params['file_path']);
                    
                    if (!$this->db->execute()) {
                        $error = $this->db->getError();
                        error_log("Database error while inserting document: " . print_r($error, true));
                        throw new Exception("Failed to insert document record for: " . $type . 
                                          " - DB Error: " . ($error ? json_encode($error) : 'Unknown error'));
                    }
                    
                    error_log("Document record inserted successfully for: " . $type);
                    
                } catch (Exception $e) {
                    error_log("Error processing document {$type}: " . $e->getMessage());
                    throw $e;
                }
            }

            // 6. Insert ownership details (if present in form data)
            if (!empty($data['ownership'])) {
                $this->db->query('INSERT INTO application_ownership_details (application_id, ownership_type, ownership_duration) 
                                 VALUES (:application_id, :ownership_type, :ownership_duration)');
                
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':ownership_type', $data['ownership']['ownership_type']);
                $this->db->bind(':ownership_duration', $data['ownership']['ownership_duration']);
                
                if (!$this->db->execute()) {
                    throw new Exception("Failed to insert ownership details");
                }
            }

            // 7. Insert tea details (if present in form data)
            if (!empty($data['tea_details'])) {
                $this->db->query('INSERT INTO application_tea_details (application_id, plant_age, monthly_production) 
                                 VALUES (:application_id, :plant_age, :monthly_production)');
                
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':plant_age', $data['tea_details']['plant_age']);
                $this->db->bind(':monthly_production', $data['tea_details']['monthly_production']);
                
                if (!$this->db->execute()) {
                    throw new Exception("Failed to insert tea details");
                }
            }

            // Insert infrastructure details
            if (!empty($data['infrastructure'])) {
                // Insert main infrastructure details
                $this->db->query('INSERT INTO application_infrastructure 
                    (application_id, access_road, vehicle_access) 
                    VALUES (:application_id, :access_road, :vehicle_access)');
                
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':access_road', $data['infrastructure']['access_road']);
                $this->db->bind(':vehicle_access', $data['infrastructure']['vehicle_access']);
                
                if (!$this->db->execute()) {
                    throw new Exception("Failed to insert infrastructure details");
                }

                // Insert water sources
                if (!empty($data['infrastructure']['water_source'])) {
                    $this->db->query('INSERT INTO application_water_sources 
                        (application_id, source_type) VALUES (:application_id, :source_type)');
                    
                    foreach ($data['infrastructure']['water_source'] as $source) {
                        $this->db->bind(':application_id', $applicationId);
                        $this->db->bind(':source_type', $source);
                        if (!$this->db->execute()) {
                            throw new Exception("Failed to insert water source: " . $source);
                        }
                    }
                }

                // Insert structures
                if (!empty($data['infrastructure']['structures'])) {
                    $this->db->query('INSERT INTO application_structures 
                        (application_id, structure_type) VALUES (:application_id, :structure_type)');
                    
                    foreach ($data['infrastructure']['structures'] as $structure) {
                        $this->db->bind(':application_id', $applicationId);
                        $this->db->bind(':structure_type', $structure);
                        if (!$this->db->execute()) {
                            throw new Exception("Failed to insert structure: " . $structure);
                        }
                    }
                }
            }

            // Insert bank details
            if (!empty($data['bank_info'])) {
                $this->db->query('INSERT INTO supplier_bank_info 
                    (application_id, account_holder_name, bank_name, branch_name, account_number, account_type) 
                    VALUES 
                    (:application_id, :account_holder_name, :bank_name, :branch_name, :account_number, :account_type)');
                
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':account_holder_name', $data['bank_info']['account_holder_name']);
                $this->db->bind(':bank_name', $data['bank_info']['bank_name']);
                $this->db->bind(':branch_name', $data['bank_info']['branch_name']);
                $this->db->bind(':account_number', $data['bank_info']['account_number']);
                $this->db->bind(':account_type', $data['bank_info']['account_type']);
                
                if (!$this->db->execute()) {
                    throw new Exception("Failed to insert bank details");
                }
            }

            // Store documents
            foreach ($documents as $docType => $docData) {
                $sql = "INSERT INTO supplier_documents 
                        (supplier_id, document_type, encrypted_data, original_name) 
                        VALUES (:supplier_id, :doc_type, :encrypted_data, :original_name)";
                
                $this->db->query($sql);
                $this->db->bind(':supplier_id', $applicationId);
                $this->db->bind(':doc_type', $docType);
                $this->db->bind(':encrypted_data', $docData['encrypted_data']);
                $this->db->bind(':original_name', $docData['original_name']);
                $this->db->execute();
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

    private function uploadDocument($file, $type) {
        try {
            // Define upload directory relative to public folder
            $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/supplier_documents/';
            error_log("Upload directory: " . $uploadDir);
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                error_log("Creating directory: " . $uploadDir);
                if (!mkdir($uploadDir, 0777, true)) {
                    $error = error_get_last();
                    error_log("Failed to create directory: " . ($error ? json_encode($error) : 'Unknown error'));
                    throw new Exception("Failed to create upload directory");
                }
                chmod($uploadDir, 0777);
            }
            
            // Validate directory is writable
            if (!is_writable($uploadDir)) {
                error_log("Directory not writable: " . $uploadDir);
                throw new Exception("Upload directory is not writable");
            }

            // Sanitize filename
            $originalName = basename($file['name']);
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $safeFileName = time() . '_' . preg_replace('/[^a-z0-9]/', '_', strtolower($type)) . '_' . uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $safeFileName;
            
            error_log("Attempting to upload file: " . $originalName);
            error_log("Target path: " . $targetPath);

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                $uploadError = error_get_last();
                error_log("Move upload failed. Error: " . ($uploadError ? json_encode($uploadError) : 'Unknown error'));
                throw new Exception("Failed to move uploaded file");
            }

            // Verify file exists after upload
            if (!file_exists($targetPath)) {
                error_log("File not found after upload: " . $targetPath);
                throw new Exception("File not found after upload");
            }

            // Return relative path for database
            $relativePath = 'uploads/supplier_documents/' . $safeFileName;
            error_log("Returning relative path: " . $relativePath);
            return $relativePath;

        } catch (Exception $e) {
            error_log("Document upload error for {$type}: " . $e->getMessage());
            throw new Exception("Failed to upload document: {$type} - " . $e->getMessage());
        }
    }

    public function getApplicationByUserId($userId) {
        $this->db->query('
            SELECT 
                sa.*,
                aa.line1, aa.line2, aa.city, aa.district, aa.postal_code,
                ai.access_road, ai.vehicle_access,
                GROUP_CONCAT(DISTINCT aws.source_type) as water_sources,
                GROUP_CONCAT(DISTINCT ast.structure_type) as structures,
                sbi.account_holder_name, sbi.bank_name, sbi.branch_name, 
                sbi.account_number, sbi.account_type
            FROM supplier_applications sa
            LEFT JOIN application_addresses aa ON sa.application_id = aa.application_id
            LEFT JOIN application_infrastructure ai ON sa.application_id = ai.application_id
            LEFT JOIN application_water_sources aws ON sa.application_id = aws.application_id
            LEFT JOIN application_structures ast ON sa.application_id = ast.application_id
            LEFT JOIN supplier_bank_info sbi ON sa.application_id = sbi.application_id
            WHERE sa.user_id = :user_id
            GROUP BY sa.application_id
            ORDER BY sa.created_at DESC
            LIMIT 1
        ');

        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();

        if ($result) {
            // Convert comma-separated strings to arrays
            if ($result->water_sources) {
                $result->water_sources = explode(',', $result->water_sources);
            }
            if ($result->structures) {
                $result->structures = explode(',', $result->structures);
            }
        }

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
        $this->db->query('SELECT 
            sa.application_id,
            sa.user_id,
            sa.status,
            sa.created_at,
            CONCAT(u.first_name, " ", u.last_name) as user_name
            FROM supplier_applications sa
            LEFT JOIN users u ON sa.user_id = u.user_id
            ORDER BY sa.created_at DESC');
        
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

    public function getAddress($applicationId) {
        $this->db->query('SELECT * FROM application_addresses 
                          WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function getApplicationById($applicationId) {
        $this->db->query('SELECT * FROM supplier_applications WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        return $this->db->single();
    }

    public function updateApplicationStatus($applicationId, $status) {
        $this->db->query('UPDATE supplier_applications 
                          SET status = :status 
                          WHERE application_id = :application_id');
                          
        $this->db->bind(':status', $status);
        $this->db->bind(':application_id', $applicationId);
        
        try {
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Error updating application status: " . $e->getMessage());
            return false;
        }
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

}
