<?php
class Contact extends Controller {
    public function __construct() {
        $this->contactModel = $this->model('M_Contact');
    }

    public function index() {
        $data = [
            'title' => 'Contact Us',
            'description' => 'Get in touch with Evergreen Tea Factory'
        ];

        $this->view('contact/index', $data);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'message_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter message';
            }

            // Make sure no errors
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['message_err'])) {
                // Validated
                if ($this->contactModel->saveMessage($data)) {
                    flash('contact_message', 'Message sent successfully');
                    redirect('contact');
                } else {
                    flash('contact_message', 'Something went wrong', 'alert alert-danger');
                    redirect('contact');
                }
            } else {
                // Load view with errors
                $this->view('contact/index', $data);
            }
        } else {
            redirect('contact');
        }
    }
} 