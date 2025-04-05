// Constants
const API_ENDPOINTS = {
  GET_COLLECTIONS: `${URLROOT}/manager/getCollectionRequests`,
  GET_COLLECTION_DETAILS: (id) =>
    `${URLROOT}/manager/getCollectionDetails/${id}`,
  APPROVE_COLLECTION: `${URLROOT}/manager/approveCollection`,
};

// Utility functions
const formatCollectionId = (id) => `COL${String(id).padStart(3, "0")}`;

const createElementFromHTML = (htmlString) => {
  const div = document.createElement("div");
  div.innerHTML = htmlString.trim();
  return div.firstChild;
};

// API handlers
const fetchAPI = async (url, options = {}) => {
  const defaultOptions = {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "Content-Type": "application/json",
    },
  };

  try {
    const response = await fetch(url, { ...defaultOptions, ...options });
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(`API Error: ${error.message}`);
    throw error;
  }
};

// Collection row template
const createCollectionRow = (collection) => `
  <tr>
    <td>${formatCollectionId(collection.collection_id)}</td>
    <td>${collection.route_name}</td>
    <td>${collection.driver_name}</td>
    <td>
      <span class="status ${
        collection.fertilizer_distributed ? "completed" : "cancelled"
      }">
        ${collection.fertilizer_distributed ? "YES" : "NO"}
      </span>
    </td>
    <td>
      <button class="btn btn-primary" onclick="CollectionManager.openDetailModal(${
        collection.collection_id
      })">
        VIEW
      </button>
    </td>
  </tr>
`;

// Bag card template
const createBagCard = (bag, urlRoot) => `
  <div class="bag-card">
    <div class="bag-card-header">
      <span class="bag-id">Bag #${bag.bag_id}</span>
      <span class="bag-capacity">${bag.capacity_kg} kg</span>
    </div>
    <div class="bag-card-actions">
      <button class="btn btn-small btn-outline-primary" 
              onclick="window.location.href='${urlRoot}/manager/bagDetails/${bag.bag_id}'">
        <i class='bx bx-show'></i> View
      </button>
      <button class="btn btn-small btn-outline-danger" onclick="CollectionManager.removeBag(${bag.bag_id})">
        <i class='bx bx-trash'></i> Delete
      </button>
    </div>
  </div>
`;

// Main Collection Manager class
class CollectionManager {
  static async loadCollectionRequests() {
    try {
      const data = await fetchAPI(API_ENDPOINTS.GET_COLLECTIONS);
      const tbody = document.querySelector(
        "#collection-confirmation-table tbody"
      );
      tbody.innerHTML = data.map(createCollectionRow).join("");
    } catch (error) {
      console.error("Error loading collection requests:", error);
    }
  }

  static async openDetailModal(collectionId) {
    try {
      const collection = await fetchAPI(
        API_ENDPOINTS.GET_COLLECTION_DETAILS(collectionId)
      );
      const content = document.getElementById(
        "collectionRequestDetailsContent"
      );

      content.innerHTML = `
        <div class="collection-confirmation-details">
          ${this.renderCollectionInfo(collection)}
          ${this.renderRouteDetails(collection)}
          ${this.renderScheduleInfo(collection)}
          ${this.renderBagsSection(collection)}
          ${this.renderConfirmationActions(collection)}
        </div>
      `;

      document.getElementById("collectionRequestDetailsModal").style.display =
        "block";
    } catch (error) {
      console.error("Error loading collection details:", error);
    }
  }

  static renderCollectionInfo(collection) {
    return `
      <div class="detail-group">
        <h3>Collection Information</h3>
        <div class="detail-row">
          <span class="label">Collection ID:</span>
          <span class="value">${formatCollectionId(
            collection.collection_id
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
    `;
  }

  static renderRouteDetails(collection) {
    return `
      <div class="detail-group">
        <h3>Route & Schedule Details</h3>
        <div class="detail-row">
          <span class="label">Route:</span>
          <span class="value">
            <a href="${URLROOT}/manager/routeDetails/${
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
            <a href="${URLROOT}/manager/driverDetails/${
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
    `;
  }

  static renderScheduleInfo(collection) {
    return `
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
          <span class="value">${collection.shift_name} (${collection.shift_start} - ${collection.shift_end})</span>
        </div>
      </div>
    `;
  }

  static renderBagsSection(collection) {
    return `
      <div class="detail-group">
        <div class="detail-header">
          <h3>Assigned Bags</h3>
          <button class="btn btn-primary" onclick="CollectionManager.openAddBagModal()">
            <i class='bx bx-plus'></i> Add Bag
          </button>
        </div>
        <div class="bags-grid">
          ${
            collection.bags.length > 0
              ? collection.bags
                  .map((bag) => createBagCard(bag, URLROOT))
                  .join("")
              : "<p>No bags assigned yet</p>"
          }
        </div>
      </div>
    `;
  }

  static renderConfirmationActions(collection) {
    return `
      <div class="confirmation-actions">
        <button class="btn btn-primary" onclick="CollectionManager.approveCollection(${collection.collection_id})">
          <i class='bx bx-check'></i> Approve Collection
        </button>
        <button class="btn btn-tertiary" onclick="CollectionManager.denyCollection(${collection.collection_id})">
          <i class='bx bx-x'></i> Deny Collection
        </button>
      </div>
    `;
  }

  static async approveCollection(collectionId) {
    try {
      const response = await fetchAPI(API_ENDPOINTS.APPROVE_COLLECTION, {
        method: "POST",
        body: JSON.stringify({
          collection_id: collectionId,
          bags_added: 1,
        }),
      });

      if (response.success) {
        alert("Collection approved successfully!");
        await this.loadCollectionRequests();
      } else {
        alert("Error approving collection: " + response.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while approving the collection.");
    }
  }

  static initializeEventListeners() {
    document.addEventListener("DOMContentLoaded", () => {
      this.loadCollectionRequests();
      this.setupModalHandlers();
    });
  }

  static setupModalHandlers() {
    const createScheduleModal = document.getElementById("createScheduleModal");
    const openCreateScheduleModal = document.getElementById(
      "openCreateScheduleModal"
    );
    const updateScheduleModal = document.getElementById("updateScheduleModal");
    const openUpdateScheduleModal = document.getElementById(
      "openUpdateScheduleModal"
    );
    const closeButtons = document.querySelectorAll(".close");

    // Open Create Schedule Modal
    openCreateScheduleModal?.addEventListener("click", (event) => {
      event.preventDefault();
      createScheduleModal.style.display = "block";
    });

    // Open Update Schedule Modal
    openUpdateScheduleModal?.addEventListener("click", (event) => {
      event.preventDefault();
      updateScheduleModal.style.display = "block";
    });

    // Close Modals
    closeButtons.forEach((button) => {
      button.addEventListener("click", () => {
        createScheduleModal.style.display = "none";
        updateScheduleModal.style.display = "none"; // Close update modal as well
      });
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", (event) => {
      if (event.target === createScheduleModal) {
        createScheduleModal.style.display = "none";
      } else if (event.target === updateScheduleModal) {
        updateScheduleModal.style.display = "none";
      }
    });
  }
}

// Initialize the Collection Manager
CollectionManager.initializeEventListeners();
