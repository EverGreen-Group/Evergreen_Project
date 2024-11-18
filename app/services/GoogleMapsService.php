<?php

class GoogleMapsService {
    private $apiKey;

    public function __construct() {
        $this->apiKey = 'AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc';
    }

    public function getMapScript() {
        return "https://maps.googleapis.com/maps/api/js?key={$this->apiKey}";
    }

    public function getDistanceMatrix($origin, $destinations) {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?";
        $url .= http_build_query([
            'origins' => $origin,
            'destinations' => implode('|', $destinations),
            'mode' => 'driving',
            'key' => $this->apiKey
        ]);

        $response = json_decode(file_get_contents($url));
        return $response;
    }
} 