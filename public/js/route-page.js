document.addEventListener("DOMContentLoaded", function () {
  const dayFilter = document.getElementById("day-filter");
  const suppliersTable = document.getElementById("suppliers-table");

  dayFilter.addEventListener("change", function () {
    const selectedDay = this.value;

    const tbody = suppliersTable.getElementsByTagName("tbody")[0];
    const rows = tbody.getElementsByTagName("tr");

    for (let row of rows) {
      const preferredDayElement = row.querySelector(".preferred-day");

      const preferredDay = preferredDayElement.textContent.trim();

      if (selectedDay === "" || preferredDay === selectedDay) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  // Get modal elements
  const modal = document.getElementById("routeModal");
  const createRouteButton = document.getElementById("createRouteButton");

  // Initialize currentRoute
  let currentRoute = null;

  // Create route button click handler
  createRouteButton.addEventListener("click", () => {
    currentRoute = {
      id: `R${Date.now()}`, // Generate a unique ID
      name: "",
      status: "Active",
      stops: [],
    };
    document.getElementById("modalTitle").textContent = "Create Route";
    document.getElementById("routeName").value = "";
    document.getElementById("status").value = "Active";
    updateStopList();
    modal.style.display = "block";

    // Initialize map if needed
    setTimeout(() => {
      if (typeof initMap === "function") {
        initMap();
        updateMap();
      }
    }, 100);
  });

  // Close button handler
  document.querySelector(".close").addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Click outside modal to close
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  let map;
  let directionsService;
  let directionsRenderer;

  const routeForm = document.getElementById("routeForm");
  const supplierSelect = document.getElementById("supplierSelect");
  const stopList = document.getElementById("stopList");
  const routesContainer = document.getElementById("routesContainer");

  // Add this at the top with other global variables
  let markers = []; // Array to store all markers

  function clearMarkers() {
    // Remove all markers from the map
    markers.forEach((marker) => marker.setMap(null));
    markers = []; // Clear the array
  }

  function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: { lat: 6.2173037, lng: 80.2538636 }, // Center of Sri Lanka
    });
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
  }

  function updateMap() {
    if (!map) {
      console.error("Map not initialized");
      return;
    }

    clearMarkers(); // Clear existing markers

    // Add factory marker
    const factoryMarker = new google.maps.Marker({
      position: factoryLocation,
      map: map,
      label: {
        text: "F",
        color: "white",
      },
      title: "Factory (Start)",
    });
    markers.push(factoryMarker);

    if (currentRoute.stops.length > 0) {
      currentRoute.stops.forEach((stop, index) => {
        const supplierMarker = new google.maps.Marker({
          position: stop.location,
          map: map,
          label: {
            text: (index + 1).toString(),
            color: "white",
          },
          title: `Stop ${index + 1}: ${stop.name}`,
        });
        markers.push(supplierMarker);
      });
    }
  }

  function populateSupplierDropdown(selectedDay) {
    const supplierSelect = document.getElementById("supplierSelect");
    supplierSelect.innerHTML =
      '<option value="" disabled selected>Select a supplier</option>'; // Reset the dropdown

    // Filter suppliers based on the selected day
    const filteredSuppliers = suppliers.filter(
      (supplier) => supplier.preferred_day === selectedDay
    );
    console.log("Filtered Suppliers:", filteredSuppliers); // Debugging output

    // Populate the supplier dropdown with filtered suppliers
    filteredSuppliers.forEach((supplier) => {
      const option = document.createElement("option");
      option.value = supplier.id;
      option.textContent = `${supplier.name} - ${supplier.average_collection} kg`;
      supplierSelect.appendChild(option);
    });
  }

  // Event listener for the daySelect dropdown
  document.getElementById("daySelect").addEventListener("change", function () {
    const selectedDay = this.value;
    console.log("Selected Day:", selectedDay); // Log the selected day
    console.log("Available Suppliers:", suppliers); // Log the suppliers array

    populateSupplierDropdown(selectedDay); // Call the function to populate suppliers
  });

  function updateStopList() {
    stopList.innerHTML = "";
    currentRoute.stops.forEach((stop, index) => {
      const li = document.createElement("li");
      li.innerHTML = `${stop.name} - ${stop.average_collection} kg <span class="remove-stop" data-id="${stop.id}">Remove</span>`;
      stopList.appendChild(li);
    });

    // Update Used and Remaining Capacity
    updateCapacity();

    document.querySelectorAll(".remove-stop").forEach((removeButton) => {
      removeButton.addEventListener("click", function () {
        const stopId = this.getAttribute("data-id");
        const removedStop = currentRoute.stops.find(
          (stop) => stop.id === stopId
        );

        // Remove from current stops
        currentRoute.stops = currentRoute.stops.filter(
          (stop) => stop.id !== stopId
        );

        // Add back to dropdown
        if (removedStop) {
          const option = document.createElement("option");
          option.value = removedStop.id;
          option.textContent = removedStop.name;
          supplierSelect.appendChild(option);
        }

        updateStopList();
        updateMap();
      });
    });
  }

  function updateCapacity() {
    const totalCapacity = currentRoute.stops.reduce(
      (total, stop) => total + stop.average_collection,
      0
    );
    document.getElementById("usedCapacity").textContent = `${Number(
      totalCapacity
    )} kg`;

    // Get vehicle capacity and ensure it's a valid number
    const vehicleCapacityText = document.getElementById(
      "vehicleCapacityDisplay"
    ).textContent;
    const vehicleCapacity = parseInt(vehicleCapacityText);

    // Check if vehicleCapacity is a valid number
    if (isNaN(vehicleCapacity)) {
      document.getElementById("remainingCapacity").textContent = "0 kg"; // Set to 0 if invalid
    } else {
      document.getElementById("remainingCapacity").textContent = `${
        vehicleCapacity - totalCapacity
      } kg`;
    }
  }

  function openRouteModal(route = null) {
    currentRoute = route
      ? JSON.parse(JSON.stringify(route))
      : { id: `R${routes.length + 1}`, name: "", status: "Active", stops: [] };
    document.getElementById("modalTitle").textContent = route
      ? "Edit Route"
      : "Create Route";
    document.getElementById("routeId").value = currentRoute.id;
    document.getElementById("routeName").value = currentRoute.name;
    document.getElementById("status").value = currentRoute.status;
    updateStopList();
    modal.style.display = "block";
    setTimeout(() => {
      initMap();
      updateMap();
    }, 100);
  }

  document.getElementById("addSupplierButton").addEventListener("click", () => {
    const selectedSupplierId = supplierSelect.value;
    const selectedSupplier = suppliers.find(
      (supplier) => supplier.id === selectedSupplierId
    );

    if (
      selectedSupplier &&
      !currentRoute.stops.find((stop) => stop.id === selectedSupplier.id)
    ) {
      currentRoute.stops.push({
        id: selectedSupplier.id,
        name: selectedSupplier.name,
        location: selectedSupplier.location,
        average_collection: selectedSupplier.average_collection,
      });

      // Remove from dropdown
      const option = supplierSelect.querySelector(
        `option[value="${selectedSupplierId}"]`
      );
      if (option) {
        option.remove();
      }

      updateStopList();
      updateMap(); // Have to implement an algorithm later
    }
  });

  routeForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const routeData = {
        name: document.getElementById("routeName").value,
        status: document.getElementById("status").value,
        day: document.getElementById("daySelect").value,
        vehicle_id: document.getElementById("vehicleSelect").value,
        stops: currentRoute.stops.map((stop) => ({
          id: parseInt(stop.id),
        })),
      };

      console.log("Sending data:", routeData); // Debug log

      const response = await fetch(`${URLROOT}/vehiclemanager/createRoute`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(routeData),
      });

      // Log the raw response for debugging
      const rawResponse = await response.text();
      console.log("Raw response:", rawResponse);

      // Try to parse the response
      let result;
      try {
        result = JSON.parse(rawResponse);
        console.log("Parsed response:", result);
      } catch (parseError) {
        console.error("Failed to parse response:", parseError);
        throw new Error("Invalid server response");
      }

      if (result.success) {
        alert(result.message);
        modal.style.display = "none";
        window.location.reload();
      } else {
        throw new Error(result.message || "Failed to create route");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Error creating route: " + error.message);
    }
  });
});

// FOR THE VEHICLE PART
document.getElementById("daySelect").addEventListener("change", function () {
  const selectedDay = this.value;
  const vehicleSelect = document.getElementById("vehicleSelect");

  console.log("Day selected:", selectedDay);
  console.log("Vehicle select element:", vehicleSelect);

  // Clear current vehicle options but one
  while (vehicleSelect.options.length > 1) {
    vehicleSelect.remove(1);
  }

  fetch(`${URLROOT}/vehiclemanager/getAvailableVehicles/${selectedDay}`)
    .then((response) => response.json())
    .then((response) => {
      console.log("Received response:", response);

      if (response.status === "success" && response.data) {
        response.data.forEach((vehicle) => {
          console.log("Creating option for vehicle:", vehicle);

          const option = new Option(
            `${vehicle.license_plate} (${vehicle.capacity}kg)`,
            vehicle.vehicle_id
          );
          option.dataset.capacity = vehicle.capacity;

          console.log("Created option:", option);
          vehicleSelect.add(option);
        });

        console.log("Final vehicle select options:", vehicleSelect.options);
      } else {
        console.error("Error loading vehicles:", response.message);
      }
    })
    .catch((error) => console.error("Error:", error));
});

// Existing day select event listener...

// Vehicle select event listener
supplierSelect.disabled = true;

document
  .getElementById("vehicleSelect")
  .addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const vehicleId = this.value;

    // Enable supplier select only if a vehicle is selected
    if (vehicleId) {
      supplierSelect.disabled = false; // Enable supplier select

      fetch(`${URLROOT}/vehiclemanager/getVehicleDetails/${vehicleId}`)
        .then((response) => response.json())
        .then((response) => {
          if (response.status === "success" && response.data) {
            const vehicle = response.data;

            // Update vehicle details section
            document.getElementById("vehicleNumberDisplay").textContent =
              vehicle.license_plate;
            document.getElementById(
              "vehicleCapacityDisplay"
            ).textContent = `${vehicle.capacity}kg`;
            document.getElementById("vehicleTypeDisplay").textContent =
              vehicle.vehicle_type;

            // Update capacity info
            document.getElementById("usedCapacity").textContent = "0 kg"; // Initialize used capacity
            document.getElementById(
              "remainingCapacity"
            ).textContent = `${vehicle.capacity} kg`; // Set remaining capacity based on vehicle capacity

            // Update vehicle image with correct path
            if (vehicle.license_plate) {
              document.getElementById(
                "vehicleImage"
              ).src = `${URLROOT}/public/uploads/vehicle_photos/${vehicle.license_plate}.jpg`;
            } else {
              document.getElementById(
                "vehicleImage"
              ).src = `${URLROOT}/public/uploads/vehicle_photos/default-vehicle.jpg`;
            }
          }
        })
        .catch((error) => console.error("Error:", error));
    } else {
      document.getElementById("usedCapacity").textContent = "0 kg"; // Reset used capacity
      document.getElementById("remainingCapacity").textContent = "0 kg"; // Reset remaining capacity
    }
  });

///////////////////////////////////////////////////////////////////////
///// UPDATE SECTION NEEDS SOME CORRECTION
////////////////////////////////////////////////////////////////////////

// Add update route functionality to existing route-page.js
document.addEventListener("DOMContentLoaded", function () {
  // Get update modal elements
  const updateModal = document.getElementById("updateRouteModal");
  const updateForm = document.getElementById("updateRouteForm");
  const updateStopList = document.getElementById("updateStopList");

  // Global variable to store current route being updated
  let currentUpdateRoute = null;

  // Add click handler to all update buttons
  document.querySelectorAll(".route-row").forEach((row) => {
    const updateBtn = row.querySelector(".btn-secondary");
    updateBtn.addEventListener("click", async (e) => {
      e.preventDefault();
      const routeId = row.getAttribute("data-route-id");
      await loadRouteDetails(routeId);
    });
  });

  // Function to load route details
  async function loadRouteDetails(routeId) {
    try {
      const response = await fetch(
        `${URLROOT}/vehiclemanager/getRouteDetails/${routeId}`
      );
      const result = await response.json();

      console.log("Route details response:", result); // Debug logging

      if (result.success) {
        // Changed from result.status === "success"
        currentUpdateRoute = {
          route_id: result.route.id,
          route_name: result.route.name,
          status: result.route.status,
          stops: result.route.suppliers.map((supplier) => ({
            supplier_id: supplier.id,
            supplier_name: supplier.name,
            coordinates: supplier.coordinates,
            // If average_collection isn't available in your response, you might want to add a default value
            average_collection: supplier.average_collection || 0,
          })),
        };

        populateUpdateForm(currentUpdateRoute);
        updateModal.style.display = "block";
      } else {
        throw new Error(result.message || "Failed to load route details");
      }
    } catch (error) {
      console.error("Error loading route details:", error);
      alert("Error loading route details: " + error.message);
    }
  }

  // Function to populate update form
  function populateUpdateForm(route) {
    console.log("Populating form with route:", route); // Debug logging

    document.getElementById("updateRouteId").value = route.route_id;
    document.getElementById("updateRouteName").value = route.route_name;
    // Only set these values if they exist in your route data
    if (route.day) document.getElementById("updateDaySelect").value = route.day;
    if (route.vehicle_id)
      document.getElementById("updateVehicleSelect").value = route.vehicle_id;
    document.getElementById("updateStatus").value = route.status;

    // Load vehicle details if vehicle_id exists
    if (route.vehicle_id) {
      loadVehicleDetails(route.vehicle_id);
    }

    // Populate stops
    updateStopList.innerHTML = "";
    if (route.stops && route.stops.length > 0) {
      route.stops.forEach((stop) => {
        const li = document.createElement("li");
        li.innerHTML = `
                ${stop.supplier_name} - ${stop.average_collection || "0"} kg 
                <span class="remove-stop" data-id="${
                  stop.supplier_id
                }">Remove</span>
            `;
        updateStopList.appendChild(li);
      });
    }
  }

  // Function to load vehicle details
  async function loadVehicleDetails(vehicleId) {
    try {
      const response = await fetch(
        `${URLROOT}/vehiclemanager/getVehicleDetails/${vehicleId}`
      );
      const result = await response.json();

      if (result.status === "success" && result.data) {
        const vehicle = result.data;

        // Update vehicle details display
        document.getElementById("updateVehicleNumberDisplay").textContent =
          vehicle.license_plate;
        document.getElementById(
          "updateVehicleCapacityDisplay"
        ).textContent = `${vehicle.capacity}kg`;
        document.getElementById("updateVehicleTypeDisplay").textContent =
          vehicle.vehicle_type;

        // Update vehicle image
        const imagePath = vehicle.license_plate
          ? `${URLROOT}/public/uploads/vehicle_photos/${vehicle.license_plate}.jpg`
          : `${URLROOT}/public/uploads/vehicle_photos/default-vehicle.jpg`;
        document.getElementById("updateVehicleImage").src = imagePath;
      }
    } catch (error) {
      console.error("Error loading vehicle details:", error);
    }
  }

  // Handle form submission
  updateForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const routeData = {
        route_id: document.getElementById("updateRouteId").value,
        route_name: document.getElementById("updateRouteName").value,
        day: document.getElementById("updateDaySelect").value,
        vehicle_id: document.getElementById("updateVehicleSelect").value,
        status: document.getElementById("updateStatus").value,
        stops: Array.from(updateStopList.children).map((li) => ({
          supplier_id: li.querySelector(".remove-stop").dataset.id,
        })),
      };

      const response = await fetch(`${URLROOT}/vehiclemanager/updateRoute`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(routeData),
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        updateModal.style.display = "none";
        window.location.reload();
      } else {
        throw new Error(result.message || "Failed to update route");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Error updating route: " + error.message);
    }
  });

  // Close modal handlers
  document
    .querySelector("#updateRouteModal .close")
    .addEventListener("click", () => {
      updateModal.style.display = "none";
    });

  window.addEventListener("click", (event) => {
    if (event.target === updateModal) {
      updateModal.style.display = "none";
    }
  });
});

//////////////////////////////////////////////////
////// UPDATE MAP SECTION
