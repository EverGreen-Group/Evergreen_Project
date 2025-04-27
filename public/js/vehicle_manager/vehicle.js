document.addEventListener("DOMContentLoaded", function () {
  // Report Types Chart
  const reportCtx = document
    .getElementById("reportTypesChart")
    .getContext("2d");
  new Chart(reportCtx, {
    type: "doughnut",
    data: {
      labels: [
        "Unallocated",
        "Assigned for Collections",
        "Assigned for Deliveries",
        "Maintainance",
      ],
      datasets: [
        {
          data: [5, 3, 4, 2],
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
          borderColor: "#fff",
          borderWidth: 2,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        title: {
          display: false,
        },
      },
      cutout: "65%",
    },
  });

  // Driver Status Chart
  const driverCtx = document
    .getElementById("driverStatusChart")
    .getContext("2d");
  new Chart(driverCtx, {
    type: "doughnut",
    data: {
      labels: [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
      ],
      datasets: [
        {
          data: [2, 5, 3, 4, 6, 3, 2],
          backgroundColor: [
            "#FF9F40", // Orange
            "#4BC0C0", // Teal
            "#36A2EB", // Blue
            "#9966FF", // Purple
            "#FF6384", // Pink
            "#FFCD56", // Yellow
            "#4CAF50", // Green
          ],
          borderColor: "#fff",
          borderWidth: 2,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        title: {
          display: false,
        },
      },
      cutout: "65%",
    },
  });
});

// Function to close modal
function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

window.onclick = function (event) {
  const modal = document.getElementById("addDriverModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

document.addEventListener("DOMContentLoaded", function () {
  // Add Driver functionality
  const userSelect = document.getElementById("userSelect");
  const firstName = document.getElementById("firstName");
  const lastName = document.getElementById("lastName");
  const email = document.getElementById("email");
  const nic = document.getElementById("nic");
  const dateOfBirth = document.getElementById("dateOfBirth");
  const gender = document.getElementById("gender");

  // Update Driver functionality
  const updateUserSelect = document.getElementById("updateUserSelect");
  const updateFirstName = document.getElementById("updateFirstName");
  const updateLastName = document.getElementById("updateLastName");
  const updateEmail = document.getElementById("updateEmail");
  const updateNic = document.getElementById("updateNic");
  const updateDateOfBirth = document.getElementById("updateDateOfBirth");
  const updateGender = document.getElementById("updateGender");

  // Add Driver event listener
  userSelect.addEventListener("change", function () {
    handleUserSelection(this, {
      firstName,
      lastName,
      email,
      nic,
      dateOfBirth,
      gender,
    });
  });

  // Update Driver event listener
  updateUserSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const userId = selectedOption.value;

    handleUserSelection(this, {
      firstName: updateFirstName,
      lastName: updateLastName,
      email: updateEmail,
      nic: updateNic,
      dateOfBirth: updateDateOfBirth,
      gender: updateGender,
    });

    if (userId) {
      // Fetch existing driver data
      fetch(`${URLROOT}/manager/getEmployeeByUserId/${userId}`)
        .then((response) => response.json())
        .then((data) => {
          document.getElementById("updateAddressLine1").value =
            data.address_line1 || "";
          document.getElementById("updateAddressLine2").value =
            data.address_line2 || "";
          document.getElementById("updateCity").value = data.city || "";
          document.getElementById("updateContactNumber").value =
            data.contact_number || "";
          document.getElementById("updateEmergencyContact").value =
            data.emergency_contact || "";
        })
        .catch((error) => console.error("Error fetching driver data:", error));
    }
  });
});

function handleUserSelection(selectElement, elements) {
  const selectedOption = selectElement.options[selectElement.selectedIndex];

  if (selectedOption.value) {
    elements.firstName.textContent =
      selectedOption.getAttribute("data-first-name");
    elements.lastName.textContent =
      selectedOption.getAttribute("data-last-name");
    elements.email.textContent = selectedOption.getAttribute("data-email");
    elements.nic.textContent = selectedOption.getAttribute("data-nic");
    elements.dateOfBirth.textContent = selectedOption.getAttribute("data-dob");
    elements.gender.textContent = selectedOption.getAttribute("data-gender");
  } else {
    elements.firstName.textContent = "";
    elements.lastName.textContent = "";
    elements.email.textContent = "";
    elements.nic.textContent = "";
    elements.dateOfBirth.textContent = "";
    elements.gender.textContent = "";
  }
}

function showVehicleDetails() {
  // Hardcoded values for the modal
  const licensePlate = "V001";
  const vehicleType = "Sedan";
  const status = "In Use";
  const capacity = "50";
  const make = "Toyota";
  const model = "Corolla";
  const manufacturingYear = "2020";
  const color = "Red";

  // Populate the modal with hardcoded vehicle details
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(".vehicle-modal-image img").src =
  //   "https://i.ikman-st.com/tata-dimo-batta-2010-for-sale-kurunegala-558/de991e55-8b07-4820-bd8d-0e6c6f21d356/620/466/fitted.jpg";
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(1) .value"
  //   ).textContent = licensePlate;
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(2) .value"
  //   ).textContent = vehicleType;
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(3) .value"
  //   ).textContent = status;
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(4) .value"
  //   ).textContent = capacity + " kg";
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(5) .value"
  //   ).textContent = make;
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(6) .value"
  //   ).textContent = model;
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(7) .value"
  //   ).textContent = manufacturingYear;
  // document
  //   .getElementById("viewVehicleModal")
  //   .querySelector(
  //     ".vehicle-modal-details .detail-row:nth-child(8) .value"
  //   ).textContent = color;

  // Show the modal
  document.getElementById("viewVehicleModal").style.display = "block";
}
