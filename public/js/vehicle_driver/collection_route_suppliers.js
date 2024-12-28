// Modal handling functions
function viewCollection() {
  const modal = document.getElementById("collectionBagDetailsModal");
  modal.style.display = "block";
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function (event) {
  const modal = document.getElementById("collectionBagDetailsModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

// Handle supplier actions
function callSupplier(phone) {
  if (phone) {
    window.location.href = `tel:${phone}`;
  } else {
    alert("No phone number available");
  }
}

function getDirections(supplierId) {
  // Find the supplier data from collections array
  const supplier = collections.find((s) => s.id === supplierId);

  if (supplier && supplier.location) {
    // Get destination coordinates
    const destLat = supplier.location.lat;
    const destLng = supplier.location.lng;

    // Check if browser supports geolocation
    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(
        // Success callback
        (position) => {
          const startLat = position.coords.latitude;
          const startLng = position.coords.longitude;

          // Create Google Maps URL with current location and destination
          const mapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;

          // Open in new tab
          window.open(mapsUrl, "_blank");
        },
        // Error callback - fallback to just destination
        () => {
          const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=driving`;
          window.open(mapsUrl, "_blank");
        }
      );
    } else {
      // Fallback if geolocation is not supported
      const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=driving`;
      window.open(mapsUrl, "_blank");
    }
  }

  closeModal("collectionBagDetailsModal");
}

function addCollection(supplierId) {
  window.location.href = `${URLROOT}/vehicledriver/addCollection/${supplierId}`;
}
