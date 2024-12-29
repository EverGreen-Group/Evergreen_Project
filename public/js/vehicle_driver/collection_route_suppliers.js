let currentSupplierId = null;
let currentSupplierData = null;

// Existing modal handling functions
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
  if (event.target.classList.contains("modal")) {
    event.target.style.display = "none";
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
  const supplier = collections.find((s) => s.id === supplierId);

  if (supplier && supplier.location) {
    const destLat = supplier.location.lat;
    const destLng = supplier.location.lng;

    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const startLat = position.coords.latitude;
          const startLng = position.coords.longitude;
          const mapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
          window.open(mapsUrl, "_blank");
        },
        () => {
          const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=driving`;
          window.open(mapsUrl, "_blank");
        }
      );
    } else {
      const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=driving`;
      window.open(mapsUrl, "_blank");
    }
  }
  closeModal("collectionBagDetailsModal");
}

let collectedBags = [];
let isAddingNewBag = true;

function addCollection(supplierId) {
  currentSupplierId = supplierId;
  currentSupplierData = collections.find((s) => s.id === supplierId);
  collectedBags = []; // Reset local collected bags for this session
  isAddingNewBag = true;

  // Update modal header with supplier info
  document.getElementById("modalSupplierName").textContent =
    currentSupplierData.supplierName;
  document.getElementById(
    "modalExpectedAmount"
  ).textContent = `${currentSupplierData.estimatedCollection}kg expected`;

  // Reset and show initial form
  resetBagForm();
  showBagIdStep();

  // Check for existing assigned bags and update UI
  updateAssignedBagsList();

  // Show modal
  document.getElementById("addCollectionModal").style.display = "block";
}

function resetBagForm() {
  document.getElementById("bagId").value = "";
  document.getElementById("actualWeight").value = "";
  document.getElementById("deductionNotes").value = "";
  document.getElementById("leafType").value = "S";
  document.getElementById("leafAge").value = "Young";
  document.getElementById("moistureLevel").value = "Wet";
}

function showBagIdStep() {
  document.getElementById("bagIdStep").style.display = "block";
  document.getElementById("bagDetailsStep").style.display = "none";
  document.getElementById("confirmCollectionButton").style.display =
    collectedBags.length > 0 ? "block" : "none";
}

function showBagDetailsStep() {
  document.getElementById("bagIdStep").style.display = "none";
  document.getElementById("bagDetailsStep").style.display = "block";
}

async function checkBag() {
  const bagId = document.getElementById("bagId").value.trim();
  if (!bagId) {
    alert("Please scan or enter a bag ID");
    return;
  }

  try {
    const response = await fetch(`${URLROOT}/vehicledriver/checkBag/${bagId}`);
    const data = await response.json();

    if (data.success) {
      document.getElementById("selectedBagId").textContent = bagId;
      document.getElementById("bagCapacity").textContent = data.capacity_kg;
      showBagDetailsStep();
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error("Error checking bag:", error);
    alert("Failed to verify bag");
  }
}

async function addBagToCollection() {
  const formData = {
    supplier_id: currentSupplierId,
    bag_id: document.getElementById("bagId").value,
    actual_weight_kg: document.getElementById("actualWeight").value,
    leaf_type: document.getElementById("leafType").value,
    leaf_age: document.getElementById("leafAge").value,
    moisture_level: document.getElementById("moistureLevel").value,
    notes: document.getElementById("deductionNotes").value,
    timestamp: new Date().toISOString(),
    action: "added",
    collection_id: collectionId,
  };

  try {
    const response = await fetch(
      `${URLROOT}/vehicledriver/addBagToCollection`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formData),
      }
    );

    const data = await response.json();

    if (data.success) {
      collectedBags.push(formData);
      updateAssignedBagsList();
      resetBagForm();
      showBagIdStep();

      // Show success message
      alert(
        "Bag added successfully. Scan another bag or click Finalize Collection to complete."
      );
    } else {
      alert(data.message || "Failed to add bag");
    }
  } catch (error) {
    console.error("Error adding bag:", error);
    alert("Failed to add bag");
  }
}

function updateAssignedBagsList() {
  const assignedBagsSection = document.getElementById("assignedBagsSection");
  const assignedBagsList = document.getElementById("assignedBagsList");
  const confirmCollectionButton = document.getElementById(
    "confirmCollectionButton"
  );

  // Get assigned bags from server
  fetch(`${URLROOT}/vehicledriver/getAssignedBags/${currentSupplierId}`, {
    method: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      let totalBags =
        (data.success && data.bags ? data.bags.length : 0) +
        collectedBags.length;

      // Show existing assigned bags
      if (data.success && data.bags && data.bags.length > 0) {
        const existingBagsHtml = data.bags
          .map(
            (bag) => `
              <div class="assigned-bag">
                  <span>Bag #${bag.bag_id}</span>
                  <span class="assigned-bag-info">
                      ${
                        bag.actual_weight_kg
                          ? bag.actual_weight_kg + "kg"
                          : "Pending"
                      }
                      ${bag.status === "Added" ? "(Added)" : ""}
                  </span>
              </div>
          `
          )
          .join("");

        // Show newly added bags in this session
        const newBagsHtml = collectedBags
          .map(
            (bag) => `
              <div class="assigned-bag">
                  <span>Bag #${bag.bag_id}</span>
                  <span class="assigned-bag-info">
                      ${bag.actual_weight_kg}kg
                      (Just Added)
                  </span>
              </div>
          `
          )
          .join("");

        assignedBagsList.innerHTML = existingBagsHtml + newBagsHtml;
      } else if (collectedBags.length > 0) {
        // Only show newly added bags if no existing bags
        assignedBagsList.innerHTML = collectedBags
          .map(
            (bag) => `
              <div class="assigned-bag">
                  <span>Bag #${bag.bag_id}</span>
                  <span class="assigned-bag-info">
                      ${bag.actual_weight_kg}kg
                      (Just Added)
                  </span>
              </div>
          `
          )
          .join("");
      } else {
        assignedBagsList.innerHTML = "<p>No bags added yet.</p>";
      }

      // Show assigned bags section
      assignedBagsSection.style.display = "block";

      // Show confirm button if there are any bags (either existing or newly added)
      confirmCollectionButton.style.display = totalBags > 0 ? "block" : "none";
    })
    .catch((error) => {
      console.error("Error fetching assigned bags:", error);
      assignedBagsList.innerHTML =
        "<p>Error fetching bags. Please try again.</p>";
      // Still show newly added bags if any
      if (collectedBags.length > 0) {
        const newBagsHtml = collectedBags
          .map(
            (bag) => `
              <div class="assigned-bag">
                  <span>Bag #${bag.bag_id}</span>
                  <span class="assigned-bag-info">
                      ${bag.actual_weight_kg}kg
                      (Just Added)
                  </span>
              </div>
          `
          )
          .join("");
        assignedBagsList.innerHTML += newBagsHtml;
        confirmCollectionButton.style.display = "block";
      } else {
        confirmCollectionButton.style.display = "none";
      }
    });
}

async function finalizeSupplierCollection() {
  // if (collectedBags.length === 0) { ILL RECHECK LATER
  //   alert("Please add at least one bag before finalizing the collection.");
  //   return;
  // }

  try {
    const response = await fetch(
      `${URLROOT}/vehicledriver/finalizeCollection`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          supplier_id: currentSupplierId,
          collection_id: collectionId,
          collection_time: new Date().toISOString(),
          status: "Added",
          bags: collectedBags,
        }),
      }
    );

    const data = await response.json();

    if (data.success) {
      alert("Collection finalized successfully");
      closeModal("addCollectionModal");
      // Optionally refresh the main supplier list/view
      location.reload();
    } else {
      alert(data.message || "Failed to finalize collection");
    }
  } catch (error) {
    console.error("Error finalizing collection:", error);
    alert("Failed to finalize collection");
  }
}
