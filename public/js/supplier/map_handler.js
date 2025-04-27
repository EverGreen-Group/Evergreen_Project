function initMap() {
  // Default center (Sri Lanka)
  const defaultCenter = { lat: 7.8731, lng: 80.7718 };

  // Create map
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 8,
    center: defaultCenter,
    mapTypeId: "roadmap", // Plain map view
    streetViewControl: false,
    mapTypeControl: false,
    zoomControl: true,
    fullscreenControl: false,
  });

  // Create marker
  let marker = new google.maps.Marker({
    position: defaultCenter,
    map: map,
    draggable: true,
  });

  // Try to get user's location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        // Check if position is within Sri Lanka bounds
        const sriLankaBounds = {
          north: 9.9,
          south: 5.9,
          west: 79.5,
          east: 81.9,
        };

        if (
          pos.lat >= sriLankaBounds.south &&
          pos.lat <= sriLankaBounds.north &&
          pos.lng >= sriLankaBounds.west &&
          pos.lng <= sriLankaBounds.east
        ) {
          map.setCenter(pos);
          map.setZoom(15);
          marker.setPosition(pos);
          updateFormValues(pos);
        }
      },
      () => {
        // Handle location error silently
        console.log("Location access denied or error occurred");
      }
    );
  }

  // Allow both click and drag
  map.addListener("click", (e) => {
    marker.setPosition(e.latLng);
    updateFormValues(e.latLng);
  });

  marker.addListener("dragend", () => {
    const position = marker.getPosition();
    updateFormValues(position);
  });

  // Helper function to update form values
  function updateFormValues(position) {
    document.getElementById("latitude").value = position.lat();
    document.getElementById("longitude").value = position.lng();
  }

  // Set initial form values
  updateFormValues(defaultCenter);
}

// Initialize map when page loads
document.addEventListener("DOMContentLoaded", initMap);
