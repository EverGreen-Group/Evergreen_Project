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
      option.textContent = supplier.name;
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
      li.innerHTML = `${stop.name} <span class="remove-stop" data-id="${stop.id}">Remove</span>`;
      stopList.appendChild(li);
    });

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

  function displayRouteCards() {
    routesContainer.innerHTML = "";
    routes.forEach((route) => {
      const card = document.createElement("div");
      card.className = "route-card";
      card.innerHTML = `
                <h3>${route.route_name}</h3>
                <p>Status: ${route.status}</p>
                <p>Stops: ${route.number_of_suppliers}</p>
            `;
      card.addEventListener("click", () => openRouteModal(route));
      routesContainer.appendChild(card);
    });
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
      });

      // Remove from dropdown
      const option = supplierSelect.querySelector(
        `option[value="${selectedSupplierId}"]`
      );
      if (option) {
        option.remove();
      }

      updateStopList();
      updateMap(); // This will recalculate Dijkstra's and update markers
    }
  });

  routeForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const routeData = {
        name: document.getElementById("routeName").value,
        status: document.getElementById("status").value,
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

  // Initialize
  populateSupplierDropdown();
  displayRouteCards();

  // Add these variables with your other global variables
  const editModal = document.getElementById("editRouteModal");
  const editRouteForm = document.getElementById("editRouteForm");
  const editSupplierSelect = document.getElementById("editSupplierSelect");
  const editStopList = document.getElementById("editStopList");
  let editingRoute = null;
  let editMap = null;
  let editDirectionsService = null;
  let editDirectionsRenderer = null;

  function displayRouteCards() {
    routesContainer.innerHTML = "";
    routes.forEach((route) => {
      const card = document.createElement("div");
      card.className = "route-card";
      card.innerHTML = `
                <h3>${route.route_name}</h3>
                <p>Status: ${route.status}</p>
                <p>Stops: ${route.number_of_suppliers}</p>
            `;
      card.addEventListener("click", () => openEditRouteModal(route));
      routesContainer.appendChild(card);
    });
  }

  async function openEditRouteModal(route) {
    console.log("Opening edit modal with route:", route); // Debug log

    editingRoute = {
      id: route.route_id,
      name: route.route_name,
      status: route.status,
      start_location: {
        lat: parseFloat(route.start_location_lat),
        lng: parseFloat(route.start_location_long),
      },
      end_location: {
        lat: parseFloat(route.end_location_lat),
        lng: parseFloat(route.end_location_long),
      },
      stops: [],
    };

    try {
      const url = `${URLROOT}/vehiclemanager/getRouteSuppliers/${route.route_id}`;
      console.log("Fetching suppliers for route:", route.route_id); // Debug log

      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const routeSuppliers = await response.json();
      console.log("Received suppliers:", routeSuppliers); // Debug log

      // Update the stops array with the received suppliers
      if (routeSuppliers.success) {
        editingRoute.stops = routeSuppliers.data.suppliers; // Access the suppliers correctly
      } else {
        throw new Error(routeSuppliers.message || "Failed to load suppliers");
      }

      // Update the UI
      updateEditStopList();
    } catch (error) {
      console.error("Error fetching route suppliers:", error);
      alert("Error loading route details: " + error.message);
    }
  }

  function populateEditSupplierDropdown() {
    editSupplierSelect.innerHTML =
      '<option value="" disabled selected>Select a supplier</option>';
    suppliers.forEach((supplier) => {
      // Only add suppliers not already in the route
      if (!editingRoute.stops.find((stop) => stop.id === supplier.id)) {
        const option = document.createElement("option");
        option.value = supplier.id;
        option.textContent = supplier.name;
        editSupplierSelect.appendChild(option);
      }
    });
  }

  function updateEditStopList() {
    const stopList = document.getElementById("editStopList");
    stopList.innerHTML = ""; // Clear existing stops

    if (Array.isArray(editingRoute.stops) && editingRoute.stops.length > 0) {
      editingRoute.stops.forEach((stop, index) => {
        const li = document.createElement("li");
        li.innerHTML = `
                    <span class="stop-number">${index + 1}</span>
                    <span class="supplier-name">${stop.name}</span>
                    <button type="button" class="remove-stop" onclick="removeStop(this)">×</button>
                `;
        stopList.appendChild(li);
      });
    } else {
      const li = document.createElement("li");
      li.textContent = "No stops available";
      stopList.appendChild(li);
    }
  }

  // Add event listeners
  document.querySelector(".close-edit").addEventListener("click", () => {
    editModal.style.display = "none";
  });

  document
    .getElementById("editAddSupplierButton")
    .addEventListener("click", () => {
      const selectedSupplierId = editSupplierSelect.value;
      const selectedSupplier = suppliers.find(
        (supplier) => supplier.id === selectedSupplierId
      );

      if (
        selectedSupplier &&
        !editingRoute.stops.find((stop) => stop.id === selectedSupplier.id)
      ) {
        editingRoute.stops.push({
          id: selectedSupplier.id,
          name: selectedSupplier.name,
          location: selectedSupplier.location,
        });
        updateEditStopList();
        updateEditMap();

        const option = editSupplierSelect.querySelector(
          `option[value="${selectedSupplierId}"]`
        );
        if (option) {
          option.remove();
        }
      }
    });

  editRouteForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const routeData = {
      id: editingRoute.id,
      name: document.getElementById("editRouteName").value,
      status: document.getElementById("editStatus").value,
      stops: editingRoute.stops.map((stop) => ({
        id: parseInt(stop.id.replace("S", "")),
      })),
    };

    try {
      const response = await fetch(`${URLROOT}/vehiclemanager/updateRoute`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(routeData),
      });

      const result = await response.json();

      if (result.success) {
        alert("Route updated successfully!");
        editModal.style.display = "none";
        window.location.reload();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while updating the route");
    }
  });

  // Helper function to add markers
  function addRouteMarkers(map, routePoints) {
    // Add factory marker
    new google.maps.Marker({
      position: routePoints[0].location,
      map: map,
      label: {
        text: "F",
        color: "white",
      },
      title: "Factory (Start)",
    });

    // Add numbered markers for suppliers
    routePoints.slice(1).forEach((stop, index) => {
      new google.maps.Marker({
        position: stop.location,
        map: map,
        label: {
          text: (index + 1).toString(),
          color: "white",
        },
        title: `Stop ${index + 1}: ${stop.name}`,
      });
    });
  }

  function updateSupplierList(expandedRow, suppliers) {
    const listContent = expandedRow.querySelector(".supplier-list-content");
    listContent.innerHTML = "";

    suppliers.forEach((supplier, index) => {
      const supplierItem = document.createElement("div");
      supplierItem.className = "supplier-item";
      supplierItem.innerHTML = `
                <div>${index + 1}</div>
                <div>${supplier.supplier_name}</div>
                <div>${supplier.contact_number}</div>
                <div>${supplier.address}</div>
                <div>${supplier.daily_capacity} kg</div>
            `;
      listContent.appendChild(supplierItem);
    });
  }
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

// Add vehicle select event listener
document
  .getElementById("vehicleSelect")
  .addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const vehicleId = this.value;

    // Fetch detailed vehicle information
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
          document.getElementById("usedCapacity").textContent = "0 kg"; // Initial value
          document.getElementById(
            "remainingCapacity"
          ).textContent = `${vehicle.capacity} kg`;

          // Update vehicle image if available
          if (vehicle.image_url) {
            document.getElementById("vehicleImage").src = vehicle.image_url;
          }
        }
      })
      .catch((error) => console.error("Error:", error));
  });
