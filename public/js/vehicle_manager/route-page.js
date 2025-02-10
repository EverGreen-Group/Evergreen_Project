// Global variables
let currentRoute = null;
let map;
let directionsService;
let directionsRenderer;
let markers = [];

// Map related functions
function clearMarkers() {
  markers.forEach((marker) => marker.setMap(null));
  markers = [];
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

  clearMarkers();

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

  if (currentRoute?.stops?.length > 0) {
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

// Supplier and stop management functions
function populateSupplierDropdown(selectedDay) {
  const supplierSelect = document.getElementById("supplierSelect");
  supplierSelect.innerHTML =
    '<option value="" disabled selected>Select a supplier</option>';

  const filteredSuppliers = suppliers.filter((supplier) => {
    const isInCurrentRoute = currentRoute.stops.some(
      (stop) => stop.id === supplier.id
    );
    return supplier.preferred_day === selectedDay && !isInCurrentRoute;
  });

  filteredSuppliers.forEach((supplier) => {
    const option = document.createElement("option");
    option.value = supplier.id;
    option.textContent = `${supplier.name} - ${supplier.average_collection} kg`;
    supplierSelect.appendChild(option);
  });
}

function updateStopList() {
  const stopList = document.getElementById("stopList");
  stopList.innerHTML = "";

  currentRoute.stops.forEach((stop) => {
    const li = document.createElement("li");
    li.innerHTML = `${stop.name} - ${stop.average_collection} kg <span class="remove-stop" data-id="${stop.id}">Remove</span>`;
    stopList.appendChild(li);
  });

  updateCapacity();

  // Add remove stop event listeners
  document.querySelectorAll(".remove-stop").forEach((removeButton) => {
    removeButton.removeEventListener("click", handleRemoveStop);
    removeButton.addEventListener("click", handleRemoveStop);
  });
}

function handleRemoveStop() {
  const stopId = this.getAttribute("data-id");
  const removedStop = currentRoute.stops.find((stop) => stop.id === stopId);

  currentRoute.stops = currentRoute.stops.filter((stop) => stop.id !== stopId);

  if (removedStop) {
    const supplierSelect = document.getElementById("supplierSelect");
    const existingOption = supplierSelect.querySelector(
      `option[value="${removedStop.id}"]`
    );
    if (!existingOption) {
      const option = document.createElement("option");
      option.value = removedStop.id;
      option.textContent = `${removedStop.name} - ${removedStop.average_collection} kg`;
      supplierSelect.appendChild(option);
    }
  }

  updateStopList();
  updateMap();
}

// Capacity management
function updateCapacity() {
  const totalCapacity = currentRoute.stops.reduce(
    (total, stop) => total + parseFloat(stop.average_collection),
    0
  );

  document.getElementById("usedCapacity").textContent = `${Number(
    totalCapacity
  )} kg`;

  const vehicleCapacityText = document.getElementById(
    "vehicleCapacityDisplay"
  ).textContent;
  const vehicleCapacity = parseInt(vehicleCapacityText);

  if (isNaN(vehicleCapacity)) {
    document.getElementById("remainingCapacity").textContent = "0 kg";
  } else {
    document.getElementById("remainingCapacity").textContent = `${
      vehicleCapacity - totalCapacity
    } kg`;
  }
}

// Vehicle management
async function handleVehicleSelect() {
  const selectedOption = this.options[this.selectedIndex];
  const vehicleId = this.value;
  const supplierSelect = document.getElementById("supplierSelect");

  supplierSelect.disabled = !vehicleId;

  if (vehicleId) {
    try {
      const response = await fetch(
        `${URLROOT}/vehiclemanager/getVehicleDetails/${vehicleId}`
      );
      const result = await response.json();

      if (result.status === "success" && result.data) {
        const vehicle = result.data;

        document.getElementById("vehicleNumberDisplay").textContent =
          vehicle.license_plate;
        document.getElementById(
          "vehicleCapacityDisplay"
        ).textContent = `${vehicle.capacity}kg`;
        document.getElementById("vehicleTypeDisplay").textContent =
          vehicle.vehicle_type;

        document.getElementById("usedCapacity").textContent = "0 kg";
        document.getElementById(
          "remainingCapacity"
        ).textContent = `${vehicle.capacity} kg`;

        const imagePath = vehicle.license_plate
          ? `${URLROOT}/public/uploads/vehicle_photos/${vehicle.license_plate}.jpg`
          : `${URLROOT}/public/uploads/vehicle_photos/default-vehicle.jpg`;
        document.getElementById("vehicleImage").src = imagePath;
      }
    } catch (error) {
      console.error("Error loading vehicle details:", error);
    }
  } else {
    document.getElementById("usedCapacity").textContent = "0 kg";
    document.getElementById("remainingCapacity").textContent = "0 kg";
  }
}

// Form submission handlers
async function handleRouteFormSubmit(e) {
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

    const response = await fetch(`${URLROOT}/vehiclemanager/createRoute`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(routeData),
    });

    const rawResponse = await response.text();
    let result;

    try {
      result = JSON.parse(rawResponse);
    } catch (parseError) {
      console.error("Failed to parse response:", parseError);
      throw new Error("Invalid server response");
    }

    if (result.success) {
      alert(result.message);
      document.getElementById("routeModal").style.display = "none";
      window.location.reload();
    } else {
      throw new Error(result.message || "Failed to create route");
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error creating route: " + error.message);
  }
}

// Main initialization
document.addEventListener("DOMContentLoaded", function () {
  // Initialize DOM elements
  const modal = document.getElementById("routeModal");
  const createRouteButton = document.getElementById("createRouteButton");
  const routeForm = document.getElementById("routeForm");
  const supplierSelect = document.getElementById("supplierSelect");
  const dayFilter = document.getElementById("day-filter");
  const suppliersTable = document.getElementById("suppliers-table");
  const updateModal = document.getElementById("updateRouteModal");

  // Day filter functionality
  if (dayFilter && suppliersTable) {
    dayFilter.addEventListener("change", function () {
      const selectedDay = this.value;
      const tbody = suppliersTable.getElementsByTagName("tbody")[0];
      const rows = tbody.getElementsByTagName("tr");

      for (let row of rows) {
        const preferredDayElement = row.querySelector(".preferred-day");
        const preferredDay = preferredDayElement.textContent.trim();
        row.style.display =
          selectedDay === "" || preferredDay === selectedDay ? "" : "none";
      }
    });
  }

  // Create route button handler
  if (createRouteButton) {
    createRouteButton.addEventListener("click", () => {
      currentRoute = {
        id: `R${Date.now()}`,
        name: "",
        status: "Active",
        stops: [],
      };
      document.getElementById("modalTitle").textContent = "Create Route";
      document.getElementById("routeName").value = "";
      document.getElementById("status").value = "Active";
      modal.style.display = "block";

      setTimeout(() => {
        if (typeof initMap === "function") {
          initMap();
          updateMap();
        }
      }, 100);
    });
  }

  // Add supplier button handler
  const addSupplierButton = document.getElementById("addSupplierButton");
  if (addSupplierButton) {
    addSupplierButton.addEventListener("click", (event) => {
      event.preventDefault();
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

        const option = supplierSelect.querySelector(
          `option[value="${selectedSupplierId}"]`
        );
        if (option) option.remove();

        updateStopList();
        updateMap();
      }
    });
  }

  // Day select handler
  const daySelect = document.getElementById("daySelect");
  if (daySelect) {
    daySelect.addEventListener("change", function () {
      const selectedDay = this.value;
      populateSupplierDropdown(selectedDay);
    });
  }

  // Vehicle select handler
  const vehicleSelect = document.getElementById("vehicleSelect");
  if (vehicleSelect) {
    vehicleSelect.addEventListener("change", handleVehicleSelect);
  }

  // Form submission handler
  if (routeForm) {
    routeForm.addEventListener("submit", handleRouteFormSubmit);
  }

  // Modal close handlers
  document.querySelector(".close")?.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // Initialize route update functionality
  initializeRouteUpdate();
});

// Route update functionality
function initializeRouteUpdate() {
  const updateModal = document.getElementById("updateRouteModal");
  const updateForm = document.getElementById("updateRouteForm");
  let currentUpdateRoute = null;

  // Add click handlers to update buttons
  document.querySelectorAll(".route-row").forEach((row) => {
    const updateBtn = row.querySelector(".btn-secondary");
    updateBtn?.addEventListener("click", async (e) => {
      e.preventDefault();
      const routeId = row.getAttribute("data-route-id");
      await loadRouteDetails(routeId);
    });
  });

  async function loadRouteDetails(routeId) {
    try {
      const response = await fetch(
        `${URLROOT}/vehiclemanager/getRouteDetails/${routeId}`
      );
      const result = await response.json();

      if (result.success) {
        currentUpdateRoute = {
          route_id: result.route.id,
          route_name: result.route.name,
          status: result.route.status,
          stops: result.route.suppliers.map((supplier) => ({
            supplier_id: supplier.id,
            supplier_name: supplier.name,
            coordinates: supplier.coordinates,
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
}
