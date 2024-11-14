<?php

class ProfileController {
    public function profileData() {
        
        $profileModel = new Profile;
        $result = $profileModel->getProfileData();

        // Check if $requests is empty before loading the view
        if (empty($result)) {
            echo "Data is unavailable!";
            exit;
        }
        global $row;
        $row = $result[0];
    }
}