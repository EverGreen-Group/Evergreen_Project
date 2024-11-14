<?php

class ComplaintController {
    public function complaint() {
        // Load the complaint form view
        $title = 'Complaint Form';
        $content = BASE_PATH . 'views/pages/Complaint.php';
        include BASE_PATH . 'views/layouts/main.php';
    }

    public function submit() {
        // Handle the form submission logic
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $description = $_POST['description'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            // Validate and save the data
            if ($type && $description && $email && $phone) {
                // Assuming you have a Complaint model to handle data saving
                $complaintModel = new Complaint();
                $complaintModel->create($type, $description, $email, $phone);
                header('Location: /complaint/success');
            } else {
                echo "Please fill in all required fields.";
            }
        }
    }
}
