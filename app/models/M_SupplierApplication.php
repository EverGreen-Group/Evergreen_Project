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
            $this->db->query('INSERT INTO supplier_applications 
                (user_id, primary_phone, secondary_phone, preferred_days) 
                VALUES 
                (:user_id, :primary_phone, :secondary_phone, :preferred_days)');
            
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':primary_phone', $data['primary_phone']);
            $this->db->bind(':secondary_phone', $data['secondary_phone']);
            $this->db->bind(':preferred_days', $data['preferred_days']);
            
            // Add debug logging
            error_log("Inserting application data: " . print_r($data, true));
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert main application");
            }
            
            $applicationId = $this->db->lastInsertId();

            // 2. Insert address
            $this->db->query('INSERT INTO application_addresses 
                (application_id, line1, line2, city, district, postal_code, latitude, longitude) 
                VALUES 
                (:application_id, :line1, :line2, :city, :district, :postal_code, :latitude, :longitude)');
            
            $this->db->bind(':application_id', $applicationId);
            $this->db->bind(':line1', $data['address']['line1']);
            $this->db->bind(':line2', $data['address']['line2'] ?? null);
            $this->db->bind(':city', $data['address']['city']);
            $this->db->bind(':district', $data['address']['district']);
            $this->db->bind(':postal_code', $data['address']['postal_code']);
            $this->db->bind(':latitude', $data['address']['latitude']);
            $this->db->bind(':longitude', $data['address']['longitude']);
            
            // Add debug logging
            error_log("Inserting address data: " . print_r($data['address'], true));
            
            if (!$this->db->execute()) {
                $error = $this->db->error();
                error_log("Database error: " . print_r($error, true));
                throw new Exception("Failed to insert address: " . ($error ? json_encode($error) : 'Unknown error'));
            }

            // 3. Insert property details
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

            // 4. Insert infrastructure details
            // Map form values to database enum values
            $vehicleAccessMap = [
                'full' => 'All Weather Access',
                'partial' => 'Fair Weather Only',
                'limited' => 'Limited Access',
                'none' => 'No Vehicle Access'
            ];

            $this->db->query('INSERT INTO application_infrastructure 
                (application_id, vehicle_access) 
                VALUES (:application_id, :vehicle_access)');
            
            $this->db->bind(':application_id', $applicationId);
            $vehicleAccess = $vehicleAccessMap[$data['infrastructure']['vehicle_access']] ?? null;
            
            if (!$vehicleAccess) {
                throw new Exception("Invalid vehicle access value: " . $data['infrastructure']['vehicle_access']);
            }
            
            $this->db->bind(':vehicle_access', $vehicleAccess);
            
            // Add debug logging
            error_log("Vehicle access value: " . $vehicleAccess);
            
            if (!$this->db->execute()) {
                $error = $this->db->error();
                error_log("Database error: " . print_r($error, true));
                throw new Exception("Failed to insert infrastructure details: " . ($error ? json_encode($error) : 'Unknown error'));
            }

            // 5. Insert water sources
            if (!empty($data['infrastructure']['water_sources'])) {
                // Map form values to database enum values
                $waterSourceMap = [
                    'river' => 'Stream/River',
                    'well' => 'Well',
                    'stream' => 'Stream/River',
                    'rainwater' => 'Rain Water',
                    'other' => 'Public Water Supply'
                ];

                $this->db->query('INSERT INTO application_water_sources 
                    (application_id, source_type) VALUES (:application_id, :source_type)');
                
                foreach ($data['infrastructure']['water_sources'] as $source) {
                    // Map the source type to database enum
                    $dbSourceType = $waterSourceMap[$source] ?? null;
                    
                    if (!$dbSourceType) {
                        error_log("Invalid water source type: " . $source);
                        continue; // Skip invalid sources
                    }

                    $this->db->bind(':application_id', $applicationId);
                    $this->db->bind(':source_type', $dbSourceType);
                    
                    // Add debug logging
                    error_log("Inserting water source: " . $dbSourceType);
                    
                    if (!$this->db->execute()) {
                        $error = $this->db->error();
                        error_log("Database error: " . print_r($error, true));
                        throw new Exception("Failed to insert water source: " . ($error ? json_encode($error) : 'Unknown error'));
                    }
                }
            }

            // 6. Insert structures
            if (!empty($data['infrastructure']['structures'])) {
                // Map form values to exact database enum values
                $structures = [
                    'storage' => 'Storage Facility',
                    'processing' => 'Equipment Storage',
                    'office' => 'Worker Rest Area',
                    'residence' => 'Living Quarters',
                    'other' => 'None'
                ];

                $this->db->query('INSERT INTO application_structures 
                    (application_id, structure_type) VALUES (:application_id, :structure_type)');
                
                foreach ($data['infrastructure']['structures'] as $structure) {
                    $dbStructureType = $structures[$structure] ?? null;
                    
                    if (!$dbStructureType) {
                        error_log("Invalid structure type: " . $structure);
                        continue; // Skip invalid structures
                    }

                    $this->db->bind(':application_id', $applicationId);
                    $this->db->bind(':structure_type', $dbStructureType);
                    
                    // Add debug logging
                    error_log("Inserting structure type: " . $dbStructureType);
                    
                    if (!$this->db->execute()) {
                        $error = $this->db->error();
                        error_log("Database error: " . print_r($error, true));
                        throw new Exception("Failed to insert structure: " . ($error ? json_encode($error) : 'Unknown error'));
                    }
                }
            }

            // 7. Insert bank details
            $this->db->query('INSERT INTO supplier_bank_info 
                (application_id, account_holder_name, bank_name, branch_name, account_number, account_type) 
                VALUES 
                (:application_id, :account_holder_name, :bank_name, :branch_name, :account_number, :account_type)');
            
            // Debug: Log the values before binding
            error_log("Application ID: " . $applicationId);
            error_log("Bank Info Data: " . print_r($data['bank_info'], true));
            
            try {
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':account_holder_name', $data['bank_info']['account_holder_name']);
                $this->db->bind(':bank_name', $data['bank_info']['bank_name']);
                $this->db->bind(':branch_name', $data['bank_info']['branch_name']);
                $this->db->bind(':account_number', $data['bank_info']['account_number']);
                $this->db->bind(':account_type', $data['bank_info']['account_type']);
                
                if (!$this->db->execute()) {
                    $pdoError = $this->db->getPDO()->errorInfo();
                    error_log("PDO Error: " . print_r($pdoError, true));
                    throw new Exception("Failed to insert bank details: " . ($pdoError[2] ?? 'Unknown error'));
                }
            } catch (PDOException $e) {
                error_log("PDO Exception: " . $e->getMessage());
                if ($e->getCode() == 23000) { // Duplicate entry error
                    throw new Exception("This account number is already registered");
                }
                throw $e;
            }

            // Define required documents first
            $requiredDocuments = [
                'land_deed',
                'tax_receipt', 
                'tea_cultivation_certificate',
                'id_proof',
                'bank_statement'
            ];

            // Define document type mapping
            $documentTypeMap = [
                'land_deed' => 'ownership_proof',
                'tax_receipt' => 'tax_receipts',
                'tea_cultivation_certificate' => 'grama_cert',
                'id_proof' => 'nic',
                'bank_statement' => 'bank_passbook'
            ];

            // Then use both arrays in the foreach loops that follow
            foreach ($requiredDocuments as $docType) {
                if (!isset($_FILES['documents']['name'][$docType]) || 
                    empty($_FILES['documents']['name'][$docType])) {
                    throw new Exception("Missing upload for: " . $docType);
                }
            }

            // Process each document
            foreach ($requiredDocuments as $docType) {
                $file = [
                    'name' => $_FILES['documents']['name'][$docType],
                    'type' => $_FILES['documents']['type'][$docType],
                    'tmp_name' => $_FILES['documents']['tmp_name'][$docType],
                    'error' => $_FILES['documents']['error'][$docType],
                    'size' => $_FILES['documents']['size'][$docType]
                ];

                $filePath = $this->uploadDocument($file, $docType);
                
                // Map the document type to database enum value
                $dbDocType = $documentTypeMap[$docType] ?? null;
                
                if (!$dbDocType) {
                    error_log("Invalid document type mapping for: " . $docType);
                    throw new Exception("Invalid document type: " . $docType);
                }

                $this->db->query('INSERT INTO application_documents 
                    (application_id, document_type, file_path) 
                    VALUES 
                    (:application_id, :document_type, :file_path)');
                
                $this->db->bind(':application_id', $applicationId);
                $this->db->bind(':document_type', $dbDocType);
                $this->db->bind(':file_path', $filePath);
                
                // Add debug logging
                error_log("Inserting document: Type=" . $dbDocType . ", Path=" . $filePath);
                
                if (!$this->db->execute()) {
                    $error = $this->db->error();
                    error_log("Database error: " . print_r($error, true));
                    throw new Exception("Failed to insert document record for: " . $docType);
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            error_log("Error in createApplication: " . $e->getMessage());
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
        $this->db->query('SELECT * FROM application_tea_details 
                         WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        
        $result = $this->db->single();
        
        // Add debug logging
        if (!$result) {
            error_log("No tea details found for application ID: " . $applicationId);
        }
        
        return $result;
    }

    public function getInfrastructure($applicationId) {
        $this->db->query('SELECT * FROM application_infrastructure 
                         WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        
        $result = $this->db->single();
        
        // Add debug logging
        if (!$result) {
            error_log("No infrastructure found for application ID: " . $applicationId);
        }
        
        return $result;
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
        $this->db->query('SELECT 
            application_id,
            user_id,
            status,
            primary_phone,
            secondary_phone,
            preferred_days,
            created_at,
            updated_at 
        FROM supplier_applications 
        WHERE application_id = :application_id');
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

    public function getOwnershipDetails($applicationId) {
        $this->db->query('SELECT * FROM application_ownership_details 
                         WHERE application_id = :application_id');
        $this->db->bind(':application_id', $applicationId);
        
        $result = $this->db->single();
        
        if (!$result) {
            error_log("No ownership details found for application ID: " . $applicationId);
        }
        
        return $result;
    }

    public function confirmSupplierRole($applicationId) {
        $sql = "UPDATE supplier_applications sa 
                JOIN users u ON sa.user_id = u.user_id 
                SET sa.status = 'approved', 
                    u.role_id = 5,
                    u.approval_status = 'Approved'
                WHERE sa.application_id = :application_id";
        
        $this->db->query($sql);
        $this->db->bind(':application_id', $applicationId);
        
        return $this->db->execute();
    }
    public function insertSupplier($applicationId, $userId, $contactNumber, $latitude, $longitude, $isActive, $isDeleted, $numberOfCollections, $avgCollectionAmount, $totalCollections) {
        $sql = "INSERT INTO suppliers (
            user_id,
            contact_number,
            application_id,
            latitude,
            longitude,
            is_active,
            is_deleted,
            number_of_collections,
            avg_collection,
            total_collection
        ) VALUES (
            :user_id,
            :contact_number,
            :application_id,
            :latitude,
            :longitude,
            :is_active,
            :is_deleted,
            :number_of_collections,
            :avg_collection,
            :total_collection
        )";

        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':contact_number', $contactNumber);
        $this->db->bind(':application_id', $applicationId);
        $this->db->bind(':latitude', $latitude);
        $this->db->bind(':longitude', $longitude);
        $this->db->bind(':is_active', $isActive);
        $this->db->bind(':is_deleted', $isDeleted);
        $this->db->bind(':number_of_collections', $numberOfCollections);
        $this->db->bind(':avg_collection', $avgCollectionAmount);
        $this->db->bind(':total_collection', $totalCollections);
        
        return $this->db->execute();
    }

}
