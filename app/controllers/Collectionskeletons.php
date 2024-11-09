<?php
class Collectionskeletons extends Controller {
    private $collectionSkeletonModel;

    public function __construct() {
        $this->collectionSkeletonModel = $this->model('M_CollectionSkeleton');
    }

    public function create() {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/vehiclemanager/dashboard');
            exit();
        }

        // Get and sanitize POST data
        $data = [
            'route_id' => trim($_POST['route_id']),
            'team_id' => trim($_POST['team_id']),
            'vehicle_id' => trim($_POST['vehicle_id']),
            'shift_id' => trim($_POST['shift_id'])
        ];

        // Check for duplicate
        if ($this->collectionSkeletonModel->checkDuplicate(
            $data['route_id'],
            $data['team_id'],
            $data['vehicle_id'],
            $data['shift_id']
        )) {
            $_SESSION['skeleton_error'] = 'This combination already exists!';
            header('Location: ' . URLROOT . '/vehiclemanager/dashboard');
            exit();
        }

        // Attempt to create skeleton
        if ($this->collectionSkeletonModel->create($data)) {
            flash('skeleton_success', 'Collection skeleton created successfully!', 'alert alert-success');
        } else {
            flash('skeleton_error', 'Failed to create collection skeleton', 'alert alert-danger');
        }

        redirect('vehiclemanager/dashboard');
    }

    public function toggleActive() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $skeleton_id = $_POST['skeleton_id'];
            
            if ($this->collectionSkeletonModel->toggleActive($skeleton_id)) {
                flash('skeleton_success', 'Status updated successfully');
            } else {
                flash('skeleton_error', 'Failed to update status');
            }
            
            redirect('vehiclemanager/dashboard');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $skeleton_id = $_POST['skeleton_id'];
            
            if ($this->collectionSkeletonModel->delete($skeleton_id)) {
                flash('skeleton_success', 'Skeleton deleted successfully');
            } else {
                flash('skeleton_error', 'Failed to delete skeleton');
            }
            
            redirect('vehiclemanager/dashboard');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'skeleton_id' => $_POST['skeleton_id'],
                'route_id' => $_POST['route_id'],
                'team_id' => $_POST['team_id'],
                'vehicle_id' => $_POST['vehicle_id'],
                'shift_id' => $_POST['shift_id']
            ];

            if ($this->collectionSkeletonModel->update($data)) {
                flash('skeleton_success', 'Collection skeleton updated successfully');
            } else {
                flash('skeleton_error', 'Failed to update collection skeleton');
            }

            redirect('vehiclemanager/dashboard');
        }
    }
}