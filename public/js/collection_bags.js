// Update the container styling first
const style = document.createElement("style");
style.textContent = `
.order .head {
    margin-bottom: 15px;
}

/* Create a container with fixed dimensions for the chart */
.chart-container-wrapper {
    width: 200px;  /* Fixed width */
    height: 200px; /* Fixed height */
    margin: 0 auto; /* Center the chart */
    position: relative;
}

#bagUsageChart {
    max-width: 100% !important;
    max-height: 100% !important;
    width: 200px !important;
    height: 200px !important;
}
`;
document.head.appendChild(style);

// Update the chart initialization code
document.addEventListener("DOMContentLoaded", function () {
  const bagsUsed = 4;
  const bagsNotUsed = 13;
  const bagsInProcessing = 5;

  // Wrap the canvas in a div with controlled dimensions
  const canvas = document.getElementById("bagUsageChart");
  const wrapper = document.createElement("div");
  wrapper.className = "chart-container-wrapper";
  canvas.parentNode.insertBefore(wrapper, canvas);
  wrapper.appendChild(canvas);

  const ctx = canvas.getContext("2d");
  const bagUsageChart = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Bags Used", "Bags Not Used", "Bags In Processing"],
      datasets: [
        {
          label: "Bag Usage",
          data: [bagsUsed, bagsNotUsed, bagsInProcessing],
          backgroundColor: [
            "rgba(54, 162, 235, 0.6)",
            "rgba(255, 99, 132, 0.6)",
            "rgba(255, 206, 86, 0.6)",
          ],
          borderColor: [
            "rgba(54, 162, 235, 1)",
            "rgba(255, 99, 132, 1)",
            "rgba(255, 206, 86, 1)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          display: false, // Remove legend to save space
        },
        title: {
          display: false, // Remove title to save space
        },
      },
      layout: {
        padding: 0, // Remove padding
      },
    },
  });
});

function showCollectionBagDetails() {
  const content = document.getElementById("collectionBagDetailsContent");

  // Hardcoded values for demonstration
  const collectionBag = {
    collection_id: "COL001",
    route: "Route A",
    driver: "Driver 1",
    suppliers: [
      {
        name: "Supplier A",
        bags: [
          {
            name: "Bag 1",
            capacity: 50,
            filledAmount: 30,
            detailsUrl: "bag_details.php?id=1",
          },
          {
            name: "Bag 2",
            capacity: 70,
            filledAmount: 50,
            detailsUrl: "bag_details.php?id=2",
          },
        ],
      },
      {
        name: "Supplier B",
        bags: [
          {
            name: "Bag 3",
            capacity: 60,
            filledAmount: 20,
            detailsUrl: "bag_details.php?id=3",
          },
        ],
      },
    ],
    unassignedSuppliers: ["Supplier C", "Supplier D"],
    unassignedBags: [
      { name: "Bag 4", capacity: 40, detailsUrl: "bag_details.php?id=4" },
      { name: "Bag 5", capacity: 30, detailsUrl: "bag_details.php?id=5" },
    ],
  };

  // Create tags for unassigned bags
  const unassignedBagTags = collectionBag.unassignedBags
    .map(
      (bag) => `
        <button class="tag-button" onclick="window.location.href='${bag.detailsUrl}'">
            ${bag.name} (Capacity: ${bag.capacity} kg)
        </button>
    `
    )
    .join(" ");

  // Create table rows for assigned suppliers and their bags
  const supplierRows = collectionBag.suppliers
    .map(
      (supplier) => `
        <tr>
            <td>${supplier.name}</td>
            <td>${supplier.bags
              .map(
                (bag) => `
                <button class="tag-button" onclick="window.location.href='${bag.detailsUrl}'">
                    ${bag.name} (Capacity: ${bag.capacity} kg, Filled: ${bag.filledAmount} kg)
                </button>
            `
              )
              .join(" ")}</td>
        </tr>
    `
    )
    .join("");

  content.innerHTML = `
          <div class="vehicle-modal-content">
              <div class="vehicle-modal-details">
                  <div class="detail-group">
                      <h3>Collection Information</h3>
                      <div class="detail-row">
                          <span class="label">Collection ID:</span>
                          <span class="value">${
                            collectionBag.collection_id
                          }</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Route:</span>
                          <span class="value">${collectionBag.route}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Driver:</span>
                          <span class="value">${collectionBag.driver}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Number of Suppliers:</span>
                          <span class="value">${
                            collectionBag.suppliers.length
                          }</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Unassigned Suppliers</h3>
                      <div class="detail-row">
                          <span class="label">Suppliers:</span>
                          <span class="value">${collectionBag.unassignedSuppliers.join(
                            ", "
                          )}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Unassigned Bags</h3>
                      <div class="detail-row">
                          <span class="label">Bags:</span>
                          <span class="value">${unassignedBagTags}</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Assigned Suppliers and Their Bags</h3>
                      <table>
                          <thead>
                              <tr>
                                  <th>Supplier</th>
                                  <th>Assigned Bags</th>
                              </tr>
                          </thead>
                          <tbody>
                              ${supplierRows}
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
    `;
  document.getElementById("collectionBagDetailsModal").style.display = "block";
}

function showBagDetails(
  bagId,
  bagType,
  capacity,
  actualCapacity,
  leafType,
  lastModified,
  collectionId,
  driver,
  moisture,
  bagWeight,
  actualWeight,
  grossWeight,
  leafAge,
  assignedSupplier
) {
  const content = document.getElementById("collectionBagDetailsContent");

  content.innerHTML = `
          <div class="vehicle-modal-content">
              <div class="vehicle-modal-image">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" />
              </div>
              <div class="vehicle-modal-details">
                  <div class="detail-group">
                      <h3>Basic Information</h3>
                      <div class="detail-row">
                          <span class="label">Bag ID:</span>
                          <span class="value">${bagId}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Bag Type:</span>
                          <span class="value">${bagType}</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Status:</span>
                          <span class="value">Available</span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Assigned Supplier:</span>
                          <span class="value">${
                            assignedSupplier || "N/A"
                          }</span>
                      </div>
                  </div>

                  <div class="detail-group">
                      <h3>Specifications</h3>
                      <div class="specifications-container">
                          <div class="specifications-left">
                              <div class="detail-row">
                                  <span class="label">Capacity:</span>
                                  <span class="value">${capacity} kg</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Actual Capacity:</span>
                                  <span class="value">${actualCapacity} kg</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Leaf Type:</span>
                                  <span class="value">${leafType}</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Last Modified:</span>
                                  <span class="value">${lastModified}</span>
                              </div>
                          </div>
                          <div class="specifications-right">
                              <div class="detail-row">
                                  <span class="label">Collection ID:</span>
                                  <span class="value">${collectionId}</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Added By Driver:</span>
                                  <span class="value">${driver}</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Moisture:</span>
                                  <span class="value">${moisture}%</span>
                              </div>
                              <div class="detail-row">
                                  <span class="label">Bag Weight:</span>
                                  <span class="value">${bagWeight} kg</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    `;
  document.getElementById("collectionBagDetailsModal").style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

function openAddBagModal(lastInsertedId) {
  const content = document.getElementById("collectionBagDetailsContent");

  content.innerHTML = `
          <div class="vehicle-modal-content">
              <div class="vehicle-modal-image">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QR Code" />
              </div>
              <div class="vehicle-modal-details">
                  <div class="detail-group">
                      <h3>Basic Information</h3>
                      <div class="detail-row">
                          <span class="label">Capacity (kg):</span>
                          <span class="value"><input type="number" id="bagCapacity" name="bagCapacity" required style="width: 100%; padding: 8px; box-sizing: border-box;"></span>
                      </div>
                      <div class="detail-row">
                          <span class="label">Bag Weight:</span>
                          <span class="value"><input type="number" id="bagWeight" name="bagWeight" required style="width: 100%; padding: 8px; box-sizing: border-box;"></span>
                      </div>
                  </div>
              </div>
              <div style="text-align: center; margin-top: 20px;">
                  <button class="btn btn-secondary full-width" onclick="generateQRCode()" style="background-color: var(--mainn); color: white;">Generate QR Code</button>
              </div>
              <div style="text-align: center; margin-top: 20px;">
                  <button class="btn btn-primary full-width" onclick="addNewBag(event)">ADD BAG</button>
              </div>
          </div>
    `;

  document.getElementById("collectionBagDetailsModal").style.display = "block";
}

function addNewBag(event) {
  event.preventDefault(); // Prevent the default form submission

  // Gather input values
  const bagCapacity = document.getElementById("bagCapacity").value;
  const bagWeight = document.getElementById("bagWeight").value;

  // Prepare data to send to the server
  const data = {
    capacity_kg: bagCapacity,
    bag_weight_kg: bagWeight,
  };

  // Construct the URL using URLROOT
  const url = `${URLROOT}/vehiclemanager/createBag`; // Adjust the path as necessary

  // Send the data to the server
  fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Handle success (e.g., close modal, show success message)
        alert("Bag created successfully!");
        closeModal("collectionBagDetailsModal"); // Close the modal
        // Optionally, refresh the bag list or update the UI
      } else {
        // Handle error
        alert(data.message || "Failed to create bag.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while creating the bag.");
    });
}

// TO FILL THE COLLECTION BAG TABLE

document.addEventListener("DOMContentLoaded", function () {
  fetchBags();
});

function fetchBags() {
  fetch(`${URLROOT}/vehiclemanager/getBags`) // Adjust the URL as necessary
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        populateBagsTable(data.bags);
      } else {
        console.error("Failed to fetch bags:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error fetching bags:", error);
    });
}

function populateBagsTable(bags) {
  const tbody = document.querySelector("#bagsTable tbody"); // Target the correct tbody
  tbody.innerHTML = ""; // Clear existing rows

  bags.forEach((bag) => {
    const row = document.createElement("tr");
    row.innerHTML = `
          <td>${bag.bag_id}</td>
          <td>${bag.capacity_kg} kg</td>
          <td>${bag.bag_weight_kg} kg</td>
          <td>
              <div style="display: flex; justify-content: flex-end; margin-right: 80px; gap: 30px;">
                  <button class="btn btn-primary" onclick="showBagDetails('${bag.bag_id}', 'Type A', ${bag.capacity_kg}, ${bag.bag_weight_kg}, 'Normal Leaf', '2023-10-01', 'COL001', 'Driver A', 12, 5, 45, 50, 30)">View</button>
                  <button class="btn btn-secondary" onclick="openUpdateBagModal('${bag.bag_id}')">Update</button>
                  <button class="btn btn-tertiary" onclick="removeBag('${bag.bag_id}')">Remove</button>
              </div>
          </td>
      `;
    tbody.appendChild(row);
  });
}
