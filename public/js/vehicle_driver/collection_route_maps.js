let map;
let driverMarker;
const factoryLocation = { lat: 6.2173037, lng: 80.2564385 };
let currentInfoWindow = null;

function initMap() {
  // Initialize map centered on driver (currently using factory location)
  map = new google.maps.Map(document.getElementById("map"), {
    center: factoryLocation,
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

  // Add driver marker with distinct icon
  driverMarker = new google.maps.Marker({
    position: factoryLocation, // Will be updated with real GPS
    map: map,
    icon: {
      path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
      scale: 6,
      fillColor: "#2196F3",
      fillOpacity: 1,
      strokeWeight: 2,
      strokeColor: "#FFFFFF",
      rotation: 0, // Can be updated based on heading
    },
    title: "Your Location",
    zIndex: 999, // Keep driver marker on top
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
}

// Function to update driver's position (can be called when GPS updates)
function updateDriverPosition(position) {
  if (driverMarker && position) {
    driverMarker.setPosition(position);
    map.panTo(position);
  }
}

// Example of how to update driver position (can be called by GPS updates)
// setInterval(() => {
//     // This would be replaced with real GPS coordinates
//     updateDriverPosition(factoryLocation);
// }, 5000);

function markArrived() {
  // Handle mark arrived action
  console.log("Marked as arrived");
}

function viewCollection() {
  // Handle view collection action
  window.location.href = `${URLROOT}/vehicledriver/viewCollection/<?php echo $data['collection']->collection_id; ?>`;
}
