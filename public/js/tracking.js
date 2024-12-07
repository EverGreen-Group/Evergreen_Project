let map;
let marker;

function initMap(lat, lng) {
    const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
    
    map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 15,
        center: position,
    });

    marker = new google.maps.Marker({
        position: position,
        map: map,
        title: "Current Location"
    });
}

function updateMarkerPosition(lat, lng) {
    const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
    marker.setPosition(position);
    map.setCenter(position);
}

// Poll for updates every 30 seconds
setInterval(() => {
    fetch(`${URLROOT}/shopdelivery/getUpdates/${trackingCode}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateMarkerPosition(data.latitude, data.longitude);
                updateTrackingInfo(data);
            }
        });
}, 30000); 