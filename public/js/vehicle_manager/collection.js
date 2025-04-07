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
});

document.getElementById("day").addEventListener("change", function () {
  const selectedDay = this.value;
  const routeSelect = document.getElementById("route");
  const driverSelect = document.getElementById("driver");

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
  /////////////////////////////////

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

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

document.getElementById("shift").addEventListener("change", function () {
  const selectedDay = document.getElementById("day").value;
  const selectedShift = this.value;
  const driverSelect = document.getElementById("driver");

  // Clear existing options
  driverSelect.innerHTML =
    '<option value="" disabled selected>Select a driver</option>';

  // Make sure day is selected
  if (!selectedDay) {
    console.error("Day not selected");
    return;
  }

  // Fetch unallocated drivers based on the selected day and shift
  fetch(
    `${URLROOT}/vehicledriver/getUnallocatedDriversByDayAndShift/${selectedDay}/${selectedShift}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Driver data:", data); // Debug to see what's actually being returned

      // Check if data is an array directly or wrapped in a 'drivers' property
      const driversArray = Array.isArray(data) ? data : data.drivers || [];

      if (driversArray.length > 0) {
        driversArray.forEach((driver) => {
          const option = document.createElement("option");
          option.value = driver.driver_id;
          option.textContent = driver.full_name || driver.first_name;
          driverSelect.appendChild(option);
        });
      } else {
        const option = document.createElement("option");
        option.disabled = true;
        option.textContent = "No available drivers";
        driverSelect.appendChild(option);
      }
    })
    .catch((error) => console.error("Error fetching drivers:", error));
});
