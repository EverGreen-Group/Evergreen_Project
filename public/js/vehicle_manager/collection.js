// UPDATING TIME
document.addEventListener("DOMContentLoaded", function () {
  function updateTime() {
    const timeElement = document.querySelector("#live-time span");
    const now = new Date();
    timeElement.textContent = now.toLocaleTimeString("en-US", {
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      hour12: true,
    });
  }

  // Update time immediately and then every second
  updateTime();
  setInterval(updateTime, 1000);

  ///////////////////////////////

  document.getElementById("day").addEventListener("change", function () {
    const selectedDay = this.value;
    const routeSelect = document.getElementById("route");

    // Clear existing options
    routeSelect.innerHTML =
      '<option value="" disabled selected>Select a route</option>';

    // Fetch routes based on the selected day
    fetch(`${URLROOT}/routes/getRoutesByDay/${selectedDay}`)
      .then((response) => response.json())
      .then((data) => {
        data.routes.forEach((route) => {
          const option = document.createElement("option");
          option.value = route.route_id;
          option.textContent = route.route_name;
          routeSelect.appendChild(option);
        });
      })
      .catch((error) => console.error("Error fetching routes:", error));
  });

  /////////////////////////////////

  function loadScheduleData(scheduleId) {
    fetch(`${URLROOT}/collectionschedules/getSchedule/${scheduleId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data) {
          console.log(data); // Log the fetched data

          // Select the driver dropdown
          const driverElement = document.getElementById("edit_driver");

          if (driverElement) {
            // Check if the driver option exists, if not, create it
            let driverOption = driverElement.querySelector(
              `option[value="${data.driver_id}"]`
            );

            if (!driverOption) {
              driverOption = document.createElement("option");
              driverOption.value = data.driver_id;
              driverOption.text = data.driver_name;
              driverElement.appendChild(driverOption);
            }

            driverElement.value = data.driver_id;
          }

          // Set other form fields
          document.getElementById("edit_route").value = data.route_id;
          document.getElementById("edit_shift").value = data.shift_id;
          document.getElementById("edit_week_number").value = data.week_number;
        }
      })
      .catch((error) => console.error("Error loading schedule data:", error));
  }

  /////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////

  function updateCountdown() {
    const countdownElement = document.querySelector(".countdown");
    if (!countdownElement) return;

    const startTime = new Date(countdownElement.dataset.startTime).getTime();
    const endTime = new Date(countdownElement.dataset.endTime).getTime();
    const windowTime = startTime - 10 * 60 * 1000; // 10 minutes before
    let hasReloaded = false; // Flag to prevent multiple reloads

    function update() {
      const now = new Date().getTime();
      const distanceToStart = windowTime - now;
      const distanceToEnd = endTime - now;

      if (distanceToStart < 0 && distanceToEnd > 0 && !hasReloaded) {
        countdownElement.innerHTML = "You can now mark yourself as ready!";
        // Optionally, you can enable the "Mark as Ready" button here
        // document.querySelector('.btn-primary').disabled = false;
        hasReloaded = true; // Set the flag to true to prevent further reloads
        // location.reload(); // Uncomment if you still want to reload once
        return;
      }

      if (distanceToStart > 0) {
        const hours = Math.floor(distanceToStart / (1000 * 60 * 60));
        const minutes = Math.floor(
          (distanceToStart % (1000 * 60 * 60)) / (1000 * 60)
        );
        const seconds = Math.floor((distanceToStart % (1000 * 60)) / 1000);

        countdownElement.innerHTML = `Time until ready: ${hours}h ${minutes}m ${seconds}s`;
      } else {
        countdownElement.innerHTML =
          "Shift has started, you can still mark yourself as ready!";
      }
    }

    update();
    setInterval(update, 1000);
  }

  document.addEventListener("DOMContentLoaded", updateCountdown);

  // COLLECTION REQUEST MODAL TEST

  // FOR ROUTE MODAL TESTING (MUST CHANGE)
});

function openCollectionRequestDetailModal() {
  const content = document.getElementById("collectionRequestDetailsContent");

  content.innerHTML = `
    <div class="collection-confirmation-details">
      <div class="detail-group">
        <h3>Collection Information</h3>
        <div class="detail-row">
          <span class="label">Collection ID:</span>
          <span class="value">COL123</span>
        </div>
        <div class="detail-row">
          <span class="label">Created At:</span>
          <span class="value">2024-03-26 14:30:00</span>
        </div>
        <div class="detail-row">
          <span class="label">Status:</span>
          <span class="value status-pending">Pending Approval</span>
        </div>
      </div>

      <div class="detail-group">
        <h3>Route & Schedule Details</h3>
        <div class="detail-row">
          <span class="label">Route:</span>
          <span class="value">
            <a href="${URLROOT}/vehiclemanager/routeDetails/R123" class="detail-link">
              Galle Route 01 (R123)
              <span class="supplier-count">12 suppliers</span>
            </a>
          </span>
        </div>
        <div class="detail-row">
          <span class="label">Driver:</span>
          <span class="value">
            <a href="${URLROOT}/vehiclemanager/driverDetails/D789" class="detail-link">
              John Doe (D789)
            </a>
          </span>
        </div>
        <div class="detail-row">
          <span class="label">Vehicle:</span>
          <span class="value">
            <a href="${URLROOT}/vehiclemanager/vehicleDetails/V456" class="detail-link">
              ABC-1234 (Lorry)
            </a>
          </span>
        </div>
        <div class="detail-row">
          <span class="label">Has Deliveries:</span>
          <span class="value">Yes</span>
        </div>
      </div>

      <div class="detail-group">
        <h3>Schedule Information</h3>
        <div class="detail-row">
          <span class="label">Day:</span>
          <span class="value">Monday</span>
        </div>
        <div class="detail-row">
          <span class="label">Shift Time:</span>
          <span class="value">08:00 AM - 05:00 PM</span>
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
            <div class="bag-card">
                <div class="bag-card-header">
                    <span class="bag-id">Bag #71</span>
                    <span class="bag-capacity">21.00 kg</span>
                </div>
                <div class="bag-card-actions">
                    <button class="btn btn-small btn-outline-primary" onclick="window.location.href='${URLROOT}/vehiclemanager/bagDetails/71'">
                        <i class='bx bx-show'></i> View
                    </button>
                    <button class="btn btn-small btn-outline-danger" onclick="removeBag(71)">
                        <i class='bx bx-trash'></i> Delete
                    </button>
                </div>
            </div>
            <div class="bag-card">
                <div class="bag-card-header">
                    <span class="bag-id">Bag #72</span>
                    <span class="bag-capacity">21.00 kg</span>
                </div>
                <div class="bag-card-actions">
                    <button class="btn btn-small btn-outline-primary" onclick="window.location.href='${URLROOT}/vehiclemanager/bagDetails/72'">
                        <i class='bx bx-show'></i> View
                    </button>
                    <button class="btn btn-small btn-outline-danger" onclick="removeBag(72)">
                        <i class='bx bx-trash'></i> Delete
                    </button>
                </div>
            </div>
        </div>
      </div>

      <div class="confirmation-actions">
        <button class="btn btn-primary" onclick="approveCollection(123)">
          <i class='bx bx-check'></i> Approve Collection
        </button>
        <button class="btn btn-tertiary" onclick="denyCollection(123)">
          <i class='bx bx-x'></i> Deny Collection
        </button>
      </div>
    </div>
  `;

  document.getElementById("collectionRequestDetailsModal").style.display =
    "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}
