document.addEventListener("DOMContentLoaded", function () {
  // Report Types Chart
  const reportCtx = document
    .getElementById("reportTypesChart")
    .getContext("2d");
  new Chart(reportCtx, {
    type: "doughnut",
    data: {
      labels: [
        "Collection Mismatches",
        "Delivery Issues",
        "Supplier Complaints",
        "Driver Reports",
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
      labels: ["Unassigned", "Available", "On Delivery", "On Collection"],
      datasets: [
        {
          data: [2, 5, 3, 4],
          backgroundColor: ["#FF9F40", "#4BC0C0", "#36A2EB", "#9966FF"],
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
      fetch(`${URLROOT}/vehiclemanager/getEmployeeByUserId/${userId}`)
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
