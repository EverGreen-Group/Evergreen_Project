<?php

class Admin {
    public function uploadProductImage() {
        if (!isAdmin()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $isPrimary = isset($_POST['is_primary']) ? 1 : 0;

            // Handle file upload
            $file = $_FILES['image'];
            $fileName = time() . '_' . $file['name'];
            $targetPath = PUBLICPATH . '/img/products/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $productModel = $this->model('M_Product');
                
                // If this is primary, reset other primary images
                if ($isPrimary) {
                    $productModel->resetPrimaryImages($productId);
                }

                // Save image record to database
                if ($productModel->addProductImage($productId, $fileName, $isPrimary)) {
                    flash('product_message', 'Image uploaded successfully');
                } else {
                    flash('product_message', 'Failed to save image record', 'alert alert-danger');
                }
            } else {
                flash('product_message', 'Failed to upload image', 'alert alert-danger');
            }

            redirect('shop/product/' . $productId);
        }
    }
} 