<?php
//require_once APPROOT . '/models/';
//require_once APPROOT . '/models/';
require_once '../app/helpers/auth_middleware.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class Suppliermanager extends Controller {

    private $Model;

    public function __construct() {

        // Check if the user is logged in
        requireAuth();

       // $this->Model = new M_Fertilizer_Order();
    }

    public function index() {
        $data = [ ];
        $this->view('supplier_manager/v_supplement_dashboard', $data);
    }
    
    public function allnotifications()
    {
        $data = [];

        $this->view('supplier_manager/v_all_notifications', $data);
    }

    public function allcomplaints()
    {
        $data = [];

        $this->view('supplier_manager/v_all_complaints', $data);
    }

    public function changepassword()
    {
        $data = [];

        $this->view('supplier_manager/v_change_password', $data);
    }

    public function complaints()
    {
        $data = [];

        $this->view('supplier_manager/v_complaints', $data);
    }

    public function fertilizerorders()
    {
        $data = [];

        $this->view('supplier_manager/v_fertilizer_orders', $data);
    }

    public function leafsupply()
    {
        $data = [];

        $this->view('supplier_manager/v_leaf_supply', $data);
    }

    public function notifications()
    {
        $data = [];

        $this->view('supplier_manager/v_notifications', $data);
    }

    public function payments()
    {
        $data = [];

        $this->view('supplier_manager/v_payments', $data);
    }

    public function profile()
    {
        $data = [];

        $this->view('supplier_manager/v_profile', $data);
    }

    public function requeststatus()
    {
        $data = [];

        $this->view('supplier_manager/v_request_status', $data);
    }

    public function routeschedule()
    {
        $data = [];

        $this->view('supplier_manager/v_route_schedule', $data);
    }

    public function settings()
    {
        $data = [];

        $this->view('supplier_manager/v_settings', $data);
    }

    
}
?>
