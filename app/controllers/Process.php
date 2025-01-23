<?php
class Process extends Controller {
    private $rawLeafModel;

    public function __construct() {
        $this->rawLeafModel = $this->model('M_RawLeaf');
    }
    public function index() {
        // Get leaf types and gradings from the model

        $data = [

        ];
        
        $this->view('inventory/v_process', $data);
    }


    public function getAvailableRawLeafStock() {
        // Fetch available raw leaf stock from the model
        $availableStock = $this->rawLeafModel->getAvailableRawLeafStock(); 
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($availableStock);
    }
    

}