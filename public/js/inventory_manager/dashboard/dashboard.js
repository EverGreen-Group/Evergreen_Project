// SECTION 1
//////////////////////////////////////////////////////////////////////////////////////////////////

function reportModel() {
  const modal = document.getElementById("reportModal");
  const model2 = document.getElementById("reportModalContent");
  modal.style.display = "flex";
  modal.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
  modal.style.backdropFilter = "blur(1px)";
  //model2.style.opacity = '1';

  // Add animation class
  const modalContent = modal.querySelector(".modal-content");
  modalContent.style.animation = "modalPop 0.3s ease-out";
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = "none";
}

// // Improved click outside listener
// window.addEventListener('click', function (event) {
// 	const modal = document.getElementById('reportModal');
// 	if (event.target === modal) {
// 		closeModal();
// 	}
// });

function showCollectionBagDetails(collectionId) {
  const content = document.getElementById("collectionBagDetailsContent");
  const modal = document.getElementById("collectionBagDetailsModal");
  modal.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
  modal.style.backdropFilter = "blur(1px)";

  // Show loading state
  showDummyData();

  // Fetch data from existing endpoint
  fetch(`${URLROOT}/manager/getCollectionDetails/${collectionId}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      console.log(response);
      return response.json();
    })
    .then((data) => {
      // Organize bags by supplier
      const supplierBags = {};
      if (Array.isArray(data.bags)) {
        // Check if bags exists and is an array
        data.bags.forEach((bag) => {
          if (!supplierBags[bag.supplier_name]) {
            supplierBags[bag.supplier_name] = [];
          }
          supplierBags[bag.supplier_name].push(bag);
        });
      }

      const supplierRows = Object.entries(supplierBags)
        .map(
          ([supplier, bags]) => `
                <tr>
                    <td>${supplier}</td>
                    <td>${bags
                      .map(
                        (bag) => `
                        <button class="tag-button">
                            Bag ${bag.bag_id} (Capacity: ${bag.capacity} kg, Filled: ${bag.filled_amount} kg)
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
                                <span class="value">${data.collection_id}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Route:</span>
                                <span class="value">${data.route_name}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Driver:</span>
                                <span class="value">${data.first_name} ${data.last_name}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Number of Suppliers:</span>
                                <span class="value">${data.number_of_suppliers}</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Total Quantity:</span>
                                <span class="value">${data.total_quantity} kg</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Collection Status:</span>
                                <span class="value">${data.collection_status}</span>
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
                    <button type="submit" class="btn btn-primary" onclick="approveCollection(${data.collection_id})">Confirm</button>
                </div>
            `;
    })
    .catch((error) => {
      console.error("Error fetching collection details:", error);
      content.innerHTML = `
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <div class="detail-group">
                    <h3>Error</h3>
                    <p>Failed to load collection details. Please try again later.</p>
                </div>
            </div>
        </div>
      `;
    });

  modal.style.display = "block";
}

// Add this function to show loading state with dummy data
function showDummyData() {
  const content = document.getElementById("collectionBagDetailsContent");
  content.innerHTML = `
        <div class="vehicle-modal-content">
            <div class="vehicle-modal-details">
                <div class="detail-group">
                    <h3>Collection Information</h3>
                    <div class="detail-row">
                        <span class="label">Collection ID:</span>
                        <span class="value">Loading...</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Route:</span>
                        <span class="value">Loading...</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Driver:</span>
                        <span class="value">Loading...</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Number of Suppliers:</span>
                        <span class="value">Loading...</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Total Quantity:</span>
                        <span class="value">Loading...</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Collection Status:</span>
                        <span class="value">Loading...</span>
                    </div>
                </div>
                <div class="detail-group">
                    <h3>Assigned Suppliers and Their Bags</h3>
                    <p>Loading supplier and bag information...</p>
                </div>
            </div>
        </div>
    `;
}

// Function to handle collection approval
function approveCollection(collectionId) {
  if (confirm("Are you sure you want to approve this collection?")) {
    fetch(`${URLROOT}/inventory/approveCollection/${collectionId}`, {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Collection approved successfully");
          closeModal("collectionBagDetailsModal");
          loadStockData(); // Refresh the table
        } else {
          alert("Failed to approve collection: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Error approving collection:", error);
        alert("Failed to approve collection");
      });
  }
}

// END OF SECTION 1
////////////////////////////////////////////////////////////////////////////////////////////////////

// SECTION 2
//////////////////////////////////////////////////////////////////////////////////////////////////

function filterTable() {
  // Get the selected filter value
  const filter = document.getElementById("statusFilter").value;
  const table = document.getElementById("stockTable");
  const rows = table.getElementsByTagName("tr");

  // Loop through all rows in the table
  for (let i = 0; i < rows.length; i++) {
    const statusCell = rows[i].getElementsByClassName("status-cell")[0];
    if (statusCell) {
      const status = statusCell.textContent || statusCell.innerText;
      // Show the row if it matches the filter or if "All" is selected
      if (filter === "All" || status === filter) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}

// SECTION 3

// ISHAN, ADD THE NEW SCRIPTS HERE
//////////////////////////////////////////////////////////////////////////////////////////////////
