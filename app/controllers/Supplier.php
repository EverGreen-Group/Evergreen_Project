<?php
require_once '../app/models/M_Fertilizer_Order.php';
require_once '../app/helpers/auth_middleware.php';

class Supplier extends Controller {
    private $fertilizerModel;

    public function __construct() {
        // Check if user is logged in
        requireAuth();
        
        // Check if user has Supplier role
        // if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::SUPPLIER])) {
        //     flash('message', 'Unauthorized access', 'alert alert-danger');
        //     redirect('');
        //     exit();
        // }
    }

    // Dashboard method
    public function index() {
        $data = [
            // Add any dashboard data here
        ];
        $this->view('supplier/v_supply_dashboard', $data);
    }

    // Fertilizer request method (previously showRequests)
    public function requestFertilizer() {
        // okata adala models tika mekata load krla data athule initalize krnna, ekt passe ara view ekt pass krnna

        $data = [
        ];

        $this->view('supplier/v_fertilizer_request', $data);
    }


}
?> 