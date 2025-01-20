// Add this function to load collection requests
function loadCollectionRequests() {
  fetch(`${URLROOT}/vehiclemanager/getCollectionRequests`, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tbody = document.querySelector(
        "#collection-confirmation-table tbody"
      );
      tbody.innerHTML = ""; // Clear existing rows

      data.forEach((collection) => {
        const row = `
                  <tr>
                      <td>COL${String(collection.collection_id).padStart(
                        3,
                        "0"
                      )}</td>
                      <td>${collection.route_name}</td>
                      <td>${collection.driver_name}</td>
                      <td>
                          <span class="status ${
                            collection.fertilizer_distributed
                              ? "completed"
                              : "cancelled"
                          }">
                              ${
                                collection.fertilizer_distributed ? "YES" : "NO"
                              }
                          </span>
                      </td>
                      <td>
                          <button class="btn btn-primary" onclick="openCollectionRequestDetailModal(${
                            collection.collection_id
                          })">
                              VIEW
                          </button>
                      </td>
                  </tr>
              `;
        tbody.innerHTML += row;
      });
    })
    .catch((error) => {
      console.error("Error loading collection requests:", error);
    });
}

function openCollectionRequestDetailModal(collectionId) {
  fetch(`${URLROOT}/vehiclemanager/getCollectionDetails/${collectionId}`, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((collection) => {
      const content = document.getElementById(
        "collectionRequestDetailsContent"
      );
      content.innerHTML = `
    <div class="collection-confirmation-details">
      <div class="detail-group">
        <h3>Collection Information</h3>
        <div class="detail-row">
          <span class="label">Collection ID:</span>
          <span class="value">COL${String(collection.collection_id).padStart(
            3,
            "0"
          )}</span>
        </div>
        <div class="detail-row">
          <span class="label">Created At:</span>
          <span class="value">${collection.created_at}</span>
        </div>
        <div class="detail-row">
          <span class="label">Status:</span>
          <span class="value status-pending">${
            collection.collection_status
          }</span>
        </div>
      </div>

      <div class="detail-group">
        <h3>Route & Schedule Details</h3>
        <div class="detail-row">
          <span class="label">Route:</span>
          <span class="value">
            <a href="${URLROOT}/vehiclemanager/routeDetails/${
        collection.route_id
      }" class="detail-link">
              ${collection.route_name}
              <span class="supplier-count">${
                collection.number_of_suppliers
              } suppliers</span>
            </a>
          </span>
        </div>
        <div class="detail-row">
          <span class="label">Driver:</span>
          <span class="value">
            <a href="${URLROOT}/vehiclemanager/driverDetails/${
        collection.driver_id
      }" class="detail-link">
              ${collection.first_name} ${collection.last_name} (${
        collection.driver_status
      })
            </a>
          </span>
        </div>
        <div class="detail-row">
          <span class="label">Has Deliveries:</span>
          <span class="value">${
            collection.fertilizer_distributed === "1" ? "Yes" : "No"
          }</span>
        </div>
      </div>

      <div class="detail-group">
        <h3>Schedule Information</h3>
        <div class="detail-row">
          <span class="label">Day:</span>
          <span class="value">${collection.day}</span>
        </div>
        <div class="detail-row">
          <span class="label">Week:</span>
          <span class="value">Week ${collection.week_number}</span>
        </div>
        <div class="detail-row">
          <span class="label">Shift:</span>
          <span class="value">${collection.shift_name} (${
        collection.shift_start
      } - ${collection.shift_end})</span>
        </div>
      </div>

      <div class="detail-group">
        <div class="detail-header">
            <h3>Assigned Bags</h3>
            <button class="btn btn-primary" onclick="openAddBagModal()">
                <i class='bx bx-plus'></i> Add Bag
            </button>
        </div>
        <div class="bags-grid">
            ${
              collection.bags.length > 0
                ? collection.bags
                    .map(
                      (bag) => `
                <div class="bag-card">
                    <div class="bag-card-header">
                        <span class="bag-id">Bag #${bag.bag_id}</span>
                        <span class="bag-capacity">${bag.capacity_kg} kg</span>
                    </div>
                    <div class="bag-card-actions">
                        <button class="btn btn-small btn-outline-primary" 
                                onclick="window.location.href='${URLROOT}/vehiclemanager/bagDetails/${bag.bag_id}'">
                            <i class='bx bx-show'></i> View
                        </button>
                        <button class="btn btn-small btn-outline-danger" onclick="removeBag(${bag.bag_id})">
                            <i class='bx bx-trash'></i> Delete
                        </button>
                    </div>
                </div>
            `
                    )
                    .join("")
                : "<p>No bags assigned yet</p>"
            }
        </div>
      </div>

      <div class="confirmation-actions">
        <button class="btn btn-primary" onclick="approveCollection(${
          collection.collection_id
        })">
          <i class='bx bx-check'></i> Approve Collection
        </button>
        <button class="btn btn-tertiary" onclick="denyCollection(${
          collection.collection_id
        })">
          <i class='bx bx-x'></i> Deny Collection
        </button>
      </div>
    </div>
          `;

      document.getElementById("collectionRequestDetailsModal").style.display =
        "block";
    })
    .catch((error) => {
      console.error("Error loading collection details:", error);
    });
}

function approveCollection(collectionId) {
  const vehicleManagerId = 1;
  const currentTime = new Date().toISOString(); // Get the current time in ISO format

  fetch(`${URLROOT}/vehiclemanager/approveCollection`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({
      collection_id: collectionId,
      bags_added: 1, // Assuming bags are added
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Collection approved successfully!");
        // Optionally refresh the collection requests or update the UI
        loadCollectionRequests(); // Refresh the list
        // redirect('/');
      } else {
        alert("Error approving collection: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while approving the collection.");
    });
}

// Load collection requests when the page loads
document.addEventListener("DOMContentLoaded", function () {
  loadCollectionRequests();
});

// Add refresh functionality if needed
function refreshCollectionRequests() {
  loadCollectionRequests();
}

document.addEventListener("DOMContentLoaded", () => {
  const createScheduleModal = document.getElementById("createScheduleModal");
  const openCreateScheduleModal = document.getElementById(
    "openCreateScheduleModal"
  );
  const closeButtons = document.querySelectorAll(".close");

  // Open the Create Schedule modal
  openCreateScheduleModal.addEventListener("click", (event) => {
    event.preventDefault(); // Prevent default anchor behavior
    createScheduleModal.style.display = "block"; // Show the modal
  });

  // Close the modal when the close button is clicked
  closeButtons.forEach((button) => {
    button.addEventListener("click", () => {
      createScheduleModal.style.display = "none"; // Hide the modal
    });
  });

  // Close the modal when clicking outside of the modal content
  window.addEventListener("click", (event) => {
    if (event.target === createScheduleModal) {
      createScheduleModal.style.display = "none"; // Hide the modal
    }
  });
});
