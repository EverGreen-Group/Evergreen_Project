<?php
class Products extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = $this->model('M_Products');
    }
    public function index() {
        // Get leaf types and gradings from the model
        $leafTypes = $this->productModel->getLeafTypes();
        $leafGradings = $this->productModel->getLeafGradings();
        
        $data = [
            'leafTypes' => $leafTypes,
            'leafGradings' => $leafGradings
        ];
        
        $this->view('inventory/v_products', $data);
    }


    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if ($this->productModel->createProduct($data)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }
    
    public function uploadImage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['product_image'])) {
            $file = $_FILES['product_image'];
            
            // Generate unique filename
            $fileName = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploadDir = '/uploads/products/';
            $fullUploadPath = PUBLICPATH . $uploadDir;
            
            if (!file_exists($fullUploadPath)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Upload directory does not exist'
                ]);
                return;
            }
            
            $serverPath = $fullUploadPath . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $serverPath)) {
                chmod($serverPath, 0644);
                
                echo json_encode([
                    'success' => true,
                    'image_path' => $fileName  // Just return the filename
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to upload image'
                ]);
            }
        }
    }

    public function getLeafTypes() {
        $types = $this->productModel->getLeafTypes();
        header('Content-Type: application/json');
        echo json_encode($types);
    }

    public function getLeafGrades() {
        $grades = $this->productModel->getLeafGradings();
        header('Content-Type: application/json');
        echo json_encode($grades);
    }

    public function getAllProducts() {
        $products = $this->productModel->getAllProducts();
        
        // Prepare the data to include tea type and grade names
        foreach ($products as &$product) {
            $product->tea_type = $this->productModel->getTeaTypeById($product->leaf_type_id); // Fetch tea type name
            $product->grade = $this->productModel->getGradeById($product->grading_id); // Fetch grade name
        }

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function getProductDetails($productId) {
        $product = $this->productModel->getProductById($productId);
        
        header('Content-Type: application/json');
        echo json_encode($product);
    }

    public function updateProduct($productId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            
            if ($this->productModel->updateProduct($productId, $data)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function deleteProduct($productId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->productModel->deleteProduct($productId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

}