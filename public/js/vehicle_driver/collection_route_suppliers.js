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
  // Add debugging
  console.log("Current collections:", collections);
  console.log("Supplier ID:", supplierId);

  // Ensure collections is an array
  let collectionsArray = Array.isArray(collections)
    ? collections
    : Object.values(collections);

  if (!Array.isArray(collectionsArray)) {
    console.error("Unable to process collections as array:", collections);
    return;
  }

  currentSupplierId = supplierId;
  currentSupplierData = collectionsArray.find((s) => s.id === supplierId);

  // Debug if supplier was found
  if (!currentSupplierData) {
    console.error("Supplier not found:", supplierId);
    return;
  }

  collectedBags = []; // Reset local collected bags for this session
  isAddingNewBag = true;

  // Update modal header with supplier info
  const supplierNameEl = document.getElementById("modalSupplierName");
  const expectedAmountEl = document.getElementById("modalExpectedAmount");

  if (supplierNameEl && currentSupplierData.supplierName) {
    supplierNameEl.textContent = currentSupplierData.supplierName;
  }

  if (expectedAmountEl && currentSupplierData.estimatedCollection) {
    expectedAmountEl.textContent = `${currentSupplierData.estimatedCollection}kg expected`;
  }

  // Reset and show initial form
  resetBagForm();
  showBagIdStep();

  // Check for existing assigned bags and update UI
  updateAssignedBagsList();
  fetchAndDisplayFertilizerItems();

  // Show modal
  const modal = document.getElementById("addCollectionModal");
  if (modal) {
    modal.style.display = "block";
  }
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
  document.getElementById("confirmCollectionButton").style.display = "block";
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

// QR Code Scanning Functionality
function startQRCodeScanner() {
  // Check if the scanner is already running
  const existingVideo = document.getElementById("video");
  if (existingVideo) {
    alert("Scanner is already running.");
    return; // Exit if the scanner is already running
  }

  const videoElement = document.createElement("video");
  videoElement.id = "video";
  videoElement.style.width = "340px";
  videoElement.style.height = "340px";
  videoElement.style.marginTop = "5px";
  // videoElement.style.maxWidth = "300px";

  // Clear and add video element to reader div
  const reader = document.getElementById("reader");
  reader.innerHTML = "";
  reader.appendChild(videoElement);

  const codeReader = new ZXing.BrowserMultiFormatReader();

  codeReader
    .listVideoInputDevices()
    .then((videoInputDevices) => {
      // Use the first available camera
      const selectedDeviceId = videoInputDevices[0].deviceId;

      codeReader.decodeFromVideoDevice(
        selectedDeviceId,
        "video",
        (result, err) => {
          if (result) {
            // Stop scanning after successful scan
            codeReader.reset();

            // Handle the scanned bag ID
            document.getElementById("bagId").value = result.text;
            checkBag();
          }
          if (err && !(err instanceof ZXing.NotFoundException)) {
            console.error(err);
          }
        }
      );
    })
    .catch((err) => {
      console.error(err);
      alert(
        "Failed to start camera. Please ensure camera permissions are granted."
      );
    });

  // Create controls div if it doesn't exist
  let controlsDiv = document.getElementById("controls");
  if (!controlsDiv) {
    controlsDiv = document.createElement("div");
    controlsDiv.id = "controls"; // Set an ID for easy reference
    reader.parentNode.insertBefore(controlsDiv, reader.nextSibling);
  } else {
    // Clear existing buttons if controlsDiv already exists
    controlsDiv.innerHTML = "";
  }

  // Add stop button
  const stopButton = document.createElement("button");
  stopButton.textContent = "Stop Scanner";
  stopButton.className = "action-btn primary";
  stopButton.style.marginTop = "10px";
  stopButton.style.width = "100%";
  stopButton.onclick = () => {
    codeReader.reset();
    controlsDiv.innerHTML = ""; // Clear buttons when stopped
    startQRCodeScanner(); // Optionally, you can show the start button again
  };

  // Add start button
  const startButton = document.createElement("button");
  startButton.textContent = "Start Scanner";
  startButton.className = "action-btn primary";
  startButton.style.marginTop = "10px";
  startButton.style.width = "100%";
  startButton.style.display = "none";
  startButton.onclick = startQRCodeScanner;

  // controlsDiv.appendChild(stopButton);
  // controlsDiv.appendChild(startButton);
}

// Initialize the scanner when the page loads
document.addEventListener("DOMContentLoaded", function () {
  if (typeof ZXing === "undefined") {
    console.error("ZXing library not loaded properly");
    alert("QR Scanner library failed to load. Please refresh the page.");
    return;
  }
});

// Start the QR code scanner when the page loads
window.onload = function () {
  startQRCodeScanner();
};

async function addBagToCollection() {
  // Get form values
  const actualWeight = document.getElementById("actualWeight").value;
  const leafType = document.getElementById("leafType").value;
  const leafAge = document.getElementById("leafAge").value;
  const moistureLevel = document.getElementById("moistureLevel").value;
  const notes = document.getElementById("deductionNotes").value;

  // Perform validation
  if (!validateForm(actualWeight, leafType, leafAge, moistureLevel, notes)) {
    return; // Stop execution if validation fails
  }

  const formData = {
    supplier_id: currentSupplierId,
    bag_id: document.getElementById("bagId").value,
    actual_weight_kg: actualWeight,
    leaf_type_id: leafType,
    leaf_age: leafAge,
    moisture_level: moistureLevel,
    notes: notes,
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
      fetchAndDisplayFertilizerItems();
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

// Validation function
function validateForm(actualWeight, leafType, leafAge, moistureLevel, notes) {
  if (!actualWeight || isNaN(actualWeight) || actualWeight <= 0) {
    alert("Please enter a valid positive weight for the bag.");
    return false;
  }
  if (!leafType) {
    alert("Please select a leaf type.");
    return false;
  }
  if (!leafAge) {
    alert("Please enter the leaf age.");
    return false;
  }
  if (!moistureLevel) {
    alert("Please enter the moisture level.");
    return false;
  }
  if (notes.length > 200) {
    alert("Notes should not exceed 200 characters.");
    return false;
  }
  return true; // All validations passed
}

function updateAssignedBagsList() {
  const assignedBagsSection = document.getElementById("assignedBagsSection");
  const assignedBagsList = document.getElementById("assignedBagsList");
  const confirmCollectionButton = document.getElementById(
    "confirmCollectionButton"
  );

  // Get assigned bags from server
  fetch(
    `${URLROOT}/vehicledriver/getAssignedBags/${currentSupplierId}/${collectionId}`,
    {
      method: "GET",
    }
  )
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
                </span>
                <span class="action-icons">
                    <i class='bx bx-edit' onclick="updateBag('${
                      bag.bag_id
                    }')" title="Update Bag"></i>
                    <i class='bx bx-trash' onclick="deleteBag('${
                      bag.bag_id
                    }')" title="Delete Bag"></i>
                </span>
            </div>
        `
          )
          .join("");

        // Show existing bags and newly added bags
        assignedBagsList.innerHTML = existingBagsHtml;

        // Append newly added bags directly
        if (collectedBags.length > 0) {
          assignedBagsList.innerHTML += collectedBags
            .map(
              (bag) => `
              <div class="assigned-bag">
                  <span>Bag #${bag.bag_id}</span>
                  <span class="assigned-bag-info">
                      ${bag.actual_weight_kg}kg
                      (Just Added)
                  </span>
                  <span class="action-icons">
                      <i class='bx bx-edit' onclick="updateBag('${bag.bag_id}')" title="Update Bag"></i>
                      <i class='bx bx-trash' onclick="deleteBag('${bag.bag_id}')" title="Delete Bag"></i>
                  </span>
              </div>
          `
            )
            .join("");
        }
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
                  <span class="action-icons">
                      <i class='bx bx-edit' onclick="updateBag('${bag.bag_id}')" title="Update Bag"></i>
                      <i class='bx bx-trash' onclick="deleteBag('${bag.bag_id}')" title="Delete Bag"></i>
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
      // Always show the confirm button.
      confirmCollectionButton.style.display = "block";
    })
    .catch((error) => {
      console.error("Error fetching assigned bags:", error);
      assignedBagsList.innerHTML =
        "<p>Error fetching bags. Please try again.</p>";
      // Still show newly added bags if any
      if (collectedBags.length > 0) {
        assignedBagsList.innerHTML += collectedBags
          .map(
            (bag) => `
              <div class="assigned-bag">
                  <span>Bag #${bag.bag_id}</span>
                  <span class="assigned-bag-info">
                      ${bag.actual_weight_kg}kg
                      (Just Added)
                  </span>
                  <span class="action-icons">
                      <i class='bx bx-edit' onclick="updateBag('${bag.bag_id}')" title="Update Bag"></i>
                      <i class='bx bx-trash' onclick="deleteBag('${bag.bag_id}')" title="Delete Bag"></i>
                  </span>
              </div>
          `
          )
          .join("");
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

  const collectionData = {
    supplier_id: currentSupplierId,
    collection_id: collectionId,
    collection_time: new Date().toISOString(),
    status: "Added",
    bags: collectedBags,
  };

  console.log("Finalizing collection with data:", collectionData); // Debugging output
  console.log("Collected bags:", collectedBags);

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
      // Redirect to the vehicle driver page instead of reloading
      window.location.href = "<?php echo URLROOT; ?>/vehicledriver"; // Redirect to the specified URL
    } else {
      alert(data.message || "Failed to finalize collection");
    }
  } catch (error) {
    console.error("Error finalizing collection:", error);
    alert("Failed to finalize collection");
  }
}

// to end collection (have to implement location check later but for now I have just implemented a direct method)

async function endCollection(collectionId) {
  if (!collectionId) {
    alert("Invalid collection ID.");
    return;
  }

  try {
    const response = await fetch(`${URLROOT}/vehicledriver/endCollection`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({
        collection_id: collectionId,
      }),
    });

    const data = await response.json();

    if (data.success) {
      alert("Collection ended successfully.");
      // Redirect to the main page
      window.location.href =
        "http://localhost/Evergreen_Project/vehicledriver/";
    } else {
      alert(data.message || "Failed to end collection.");
    }
  } catch (error) {
    console.error("Error ending collection:", error);
    alert("Failed to end collection.");
  }
}

function fetchAndDisplayFertilizerItems() {
  const fertilizerItemList = document.getElementById("fertilizerItemList");

  fetch(`${URLROOT}/vehicledriver/getFertilizerItems/${currentSupplierId}`, {
    method: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("API Response:", data); // Debugging output

      // FIX: Check if `data` is an array
      if (Array.isArray(data) && data.length > 0) {
        const itemsHtml = data
          .map(
            (item) => `
              <div class="fertilizer-item">
                  <span>Item ID: ${item.item_id}</span>
                  <span class="fertilizer-item-info">
                      Quantity: ${item.quantity}kg
                  </span>
              </div>
          `
          )
          .join("");

        fertilizerItemList.innerHTML = itemsHtml;
      } else {
        fertilizerItemList.innerHTML = "<p>No fertilizer items ordered.</p>";
      }

      // Show fertilizer items section
      fertilizerItemList.style.display = "block";
    })
    .catch((error) => {
      console.error("Error fetching fertilizer items:", error);
      fertilizerItemList.innerHTML =
        "<p>Error fetching fertilizer items. Please try again.</p>";
    });
}

// Global variable to store the bag ID being updated
let currentUpdateBagId = null;

/**
 * Called when the update icon is clicked.
 * Fetches the bag details and shows the update step in the modal.
 */
async function updateBag(bagId) {
  currentUpdateBagId = bagId;
  try {
    // Fetch bag details from the controller.
    const response = await fetch(`${URLROOT}/Bag/getBagDetails/${bagId}`);
    const data = await response.json();
    if (data.success) {
      // Hide the bag addition steps (bagIdStep and bagDetailsStep)
      document.getElementById("bagIdStep").style.display = "none";
      document.getElementById("bagDetailsStep").style.display = "none";
      // Populate the update step fields with the bag details
      document.getElementById("updateSelectedBagId").textContent = bagId;
      document.getElementById("updateBagCapacity").textContent =
        data.bag.capacity_kg || "";
      document.getElementById("updateActualWeight").value =
        data.bag.actual_weight_kg || "";
      document.getElementById("updateLeafType").value =
        data.bag.leaf_type_id || "";
      document.getElementById("updateLeafAge").value =
        data.bag.leaf_age || "Young";
      document.getElementById("updateMoistureLevel").value =
        data.bag.moisture_level || "Wet";
      document.getElementById("updateDeductionNotes").value =
        data.bag.notes || "";

      // Show the update step
      document.getElementById("updateBagStep").style.display = "block";

      // Open the modal if it is not already open
      const modal = document.getElementById("addCollectionModal");
      if (modal) {
        modal.style.display = "block";
      }
    } else {
      alert(data.message || "Failed to fetch bag details");
    }
  } catch (error) {
    console.error("Error fetching bag details:", error);
    alert("Failed to fetch bag details");
  }
}

/**
 * Called when the user confirms the update in the update step.
 */
async function submitUpdateBag() {
  // Gather updated data from the update step form
  const updatedBag = {
    bag_id: currentUpdateBagId,
    actual_weight_kg: document.getElementById("updateActualWeight").value,
    leaf_type_id: document.getElementById("updateLeafType").value,
    leaf_age: document.getElementById("updateLeafAge").value,
    moisture_level: document.getElementById("updateMoistureLevel").value,
    notes: document.getElementById("updateDeductionNotes").value,
    // Include supplier_id and collection_id if your controller requires them.
    supplier_id: currentSupplierId,
    collection_id: collectionId,
  };

  // Basic validation (you can expand on this as needed)
  if (
    !updatedBag.actual_weight_kg ||
    isNaN(updatedBag.actual_weight_kg) ||
    updatedBag.actual_weight_kg <= 0
  ) {
    alert("Please enter a valid positive weight for the bag.");
    return;
  }

  try {
    const response = await fetch(`${URLROOT}/bag/updateBag`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify(updatedBag),
    });

    const result = await response.json();

    if (result.success) {
      alert("Bag updated successfully.");
      // Optionally, hide the update step and reset the form back to the add bag step:
      document.getElementById("updateBagStep").style.display = "none";
      resetBagForm();
      showBagIdStep();
      // Refresh the assigned bags list to show the updated data
      updateAssignedBagsList();
    } else {
      alert(result.message || "Failed to update bag.");
    }
  } catch (error) {
    console.error("Error updating bag:", error);
    alert("Failed to update bag.");
  }
}

/**
 * Optional function to cancel the update operation.
 * Hides the update step and returns to the add bag steps.
 */
function cancelUpdateBag() {
  document.getElementById("updateBagStep").style.display = "none";
  // Optionally, you may want to clear the update fields here.
  resetBagForm();
  showBagIdStep();
}

async function deleteBag(bagId) {
  if (
    !confirm(
      `Are you sure you want to delete Bag #${bagId}? This action cannot be undone.`
    )
  ) {
    return;
  }

  try {
    const response = await fetch(`${URLROOT}/bag/deleteBag/${bagId}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    const result = await response.json();

    if (result.success) {
      alert("Bag deleted successfully.");
      // Refresh the assigned bags list to remove the deleted bag
      updateAssignedBagsList();
    } else {
      alert(result.message || "Failed to delete bag.");
    }
  } catch (error) {
    console.error("Error deleting bag:", error);
    alert("Failed to delete bag.");
  }
}
