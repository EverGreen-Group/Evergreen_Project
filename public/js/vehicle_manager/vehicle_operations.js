// Vehicle CRUD operations
const VehicleManager = {
  deleteVehicle: async function (vehicleId) {
    if (confirm("Are you sure you want to delete this vehicle?")) {
      try {
        const formData = new FormData();
        formData.append("vehicle_id", vehicleId);

        const response = await fetch(
          `${URLROOT}/vehiclemanager/deleteVehicle/${vehicleId}`,
          {
            method: "POST",
            body: formData,
          }
        );

        if (response.ok) {
          window.location.reload();
        } else {
          alert("Failed to delete vehicle. Please try again.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("An error occurred while deleting the vehicle.");
      }
    }
  },

  // ... other vehicle operations ...
};

// Initialize event listeners
document.addEventListener("DOMContentLoaded", function () {
  // ... your initialization code ...
});
