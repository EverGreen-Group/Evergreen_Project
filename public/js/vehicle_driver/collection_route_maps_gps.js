let map;
let driverMarker;
const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };
let currentInfoWindow = null;
let watchId; // For tracking the geolocation watcher

function initMap() {
  // Start with vehicle's last known location instead of factory location
  const initialPosition = vehicleLocation || factoryLocation; // vehicleLocation should be passed from PHP

  map = new google.maps.Map(document.getElementById("map"), {
    center: initialPosition,
    zoom: 13,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  });

  // Add factory marker
  new google.maps.Marker({
    position: factoryLocation,
    map: map,
    icon: {
      path: google.maps.SymbolPath.CIRCLE,
      scale: 10,
      fillColor: "#FF0000",
      fillOpacity: 1,
      strokeWeight: 2,
      strokeColor: "#FFFFFF",
    },
    title: "Factory Location",
  });

  // Initialize driver marker with vehicle's location
  driverMarker = new google.maps.Marker({
    position: initialPosition,
    map: map,
    icon: {
      path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
      scale: 6,
      fillColor: "#2196F3",
      fillOpacity: 1,
      strokeWeight: 2,
      strokeColor: "#FFFFFF",
      rotation: 0,
    },
    title: "Vehicle Location",
    zIndex: 999,
  });

  // Add supplier markers
  if (collections && collections.length > 0) {
    collections.forEach((supplier) => {
      const marker = new google.maps.Marker({
        position: supplier.location,
        map: map,
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 8,
          fillColor: "#4CAF50",
          fillOpacity: 1,
          strokeWeight: 2,
          strokeColor: "#FFFFFF",
        },
      });

      const infowindow = new google.maps.InfoWindow({
        content: `
                    <div style="
                        padding: 3px 6px;
                        font-size: 12px;
                        line-height: 1.3;
                        max-width: 150px;
                    ">
                        <div style="font-weight: 600;">${supplier.supplierName}</div>
                        <div style="color: #666;">${supplier.estimatedCollection}kg</div>
                    </div>
                `,
        pixelOffset: new google.maps.Size(0, -10),
        disableAutoPan: true,
      });

      marker.addListener("click", () => {
        if (currentInfoWindow) {
          currentInfoWindow.close();
        }
        infowindow.open(map, marker);
        currentInfoWindow = infowindow;
      });
    });
  }

  // Start GPS tracking with database updates
  startGPSTracking();
}

function startGPSTracking() {
  if ("geolocation" in navigator) {
    // Request permission and start tracking
    const options = {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 0,
    };

    watchId = navigator.geolocation.watchPosition(
      // Success callback
      (position) => {
        const currentPosition = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        // Update both map and database
        updateDriverPosition(currentPosition);
        updateVehicleLocationInDB(currentPosition);

        // Optionally update driver marker rotation based on heading
        if (position.coords.heading) {
          updateDriverRotation(position.coords.heading);
        }
      },
      // Error callback
      (error) => {
        console.error("Error getting location:", error);
        // Fallback to factory location if GPS fails
        updateDriverPosition(factoryLocation);
      },
      options
    );
  } else {
    console.error("Geolocation is not supported by this browser");
    // Fallback to factory location if geolocation not supported
    updateDriverPosition(factoryLocation);
  }
}

function updateDriverPosition(position) {
  if (driverMarker && position) {
    driverMarker.setPosition(position);
    map.panTo(position);
  }
}

function updateDriverRotation(heading) {
  if (driverMarker) {
    const icon = driverMarker.getIcon();
    icon.rotation = heading;
    driverMarker.setIcon(icon);
  }
}

function updateVehicleLocationInDB(position) {
  fetch(`${URLROOT}/vehicledriver/updateVehicleLocation`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({
      collection_id: collectionId, // This should be passed from PHP
      latitude: position.lat,
      longitude: position.lng,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.success) {
        console.error("Failed to update vehicle location:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error updating vehicle location:", error);
    });
}

// Clean up when page is unloaded
window.onbeforeunload = function () {
  if (watchId) {
    navigator.geolocation.clearWatch(watchId);
  }
};

function markArrived() {
  // Handle mark arrived action
  console.log("Marked as arrived");
}

function viewCollection() {
  // Handle view collection action
  window.location.href = `${URLROOT}/vehicledriver/viewCollection/<?php echo $data['collection']->collection_id; ?>`;
}
