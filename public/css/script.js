const allSideMenu = document.querySelectorAll("#sidebar .side-menu.top li a");

allSideMenu.forEach((item) => {
  const li = item.parentElement;
  item.addEventListener("click", function () {
    allSideMenu.forEach((i) => i.parentElement.classList.remove("active"));
    li.classList.add("active");
  });
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector("#content nav .bx.bx-menu");
const sidebar = document.getElementById("sidebar");
if (menuBar && sidebar) {
  menuBar.addEventListener("click", () => sidebar.classList.toggle("hide"));
}

// SEARCH FORM
const searchButton = document.querySelector("#content nav form .form-input button");
const searchButtonIcon = document.querySelector("#content nav form .form-input button .bx");
const searchForm = document.querySelector("#content nav form");
if (searchButton && searchButtonIcon && searchForm) {
  searchButton.addEventListener("click", function (e) {
    if (window.innerWidth < 576) {
      e.preventDefault();
      searchForm.classList.toggle("show");
      searchButtonIcon.classList.replace(
        searchForm.classList.contains("show") ? "bx-search" : "bx-x",
        searchForm.classList.contains("show") ? "bx-x" : "bx-search"
      );
    }
  });
}

if (window.innerWidth < 768) {
  sidebar?.classList.add("hide");
} else if (window.innerWidth > 576) {
  searchButtonIcon?.classList.replace("bx-x", "bx-search");
  searchForm?.classList.remove("show");
}

window.addEventListener("resize", function () {
  if (this.innerWidth > 576) {
    searchButtonIcon?.classList.replace("bx-x", "bx-search");
    searchForm?.classList.remove("show");
  }
});

// THEME SWITCH
const switchMode = document.getElementById("switch-mode");
function setTheme(isDark) {
  if (isDark) {
    document.body.classList.add("dark");
  } else {
    document.body.classList.remove("dark");
  }
  localStorage.setItem("darkMode", isDark);
  switchMode.checked = isDark;
}
const savedDarkMode = localStorage.getItem("darkMode") === "true";
if (switchMode) {
  setTheme(savedDarkMode);
  switchMode.addEventListener("change", function () {
    setTheme(this.checked);
  });
}

// ACTIVE MENU ITEM
const currentPage = window.location.pathname.split("/").pop().toLowerCase();
allSideMenu.forEach((item) => {
  const href = item.getAttribute("href").toLowerCase();
  if (href === currentPage || href.includes(currentPage)) {
    item.parentElement.classList.add("active");
  }
});

// FORM SUBMISSION
function submitmessage(event) {
  event.preventDefault();
  const form = document.querySelector(".complaint-form");
  if (!form) return;
  const formData = new FormData(form);
  let isFormValid = true;
  formData.forEach((value) => {
    if (!value.trim()) isFormValid = false;
  });
  alert(isFormValid ? "Submit Successful" : "Unsuccessful: Please fill in all required fields.");
  setTimeout(() => window.location.reload(), 2000);
}

function refreshPage() {
  document.querySelector(".complaint-form")?.reset();
}

function updatePricePerUnit() {
  const typeSelect = document.getElementById("type_id");
  const pricePerUnitInput = document.getElementById("price_per_unit");
  const totalPriceInput = document.getElementById("total_price");
  const totalAmountInput = document.getElementById("total_amount");
  if (!typeSelect || !pricePerUnitInput || !totalPriceInput || !totalAmountInput) return;

  const selectedType = typeSelect.value;
  const type = window.FERTILIZER_TYPES?.find((t) => t.type_id == selectedType);
  if (type) {
    const defaultUnit = "kg";
    pricePerUnitInput.value = type[`price_${defaultUnit}`];
    const totalAmount = totalAmountInput.value;
    if (totalAmount) {
      totalPriceInput.value = (totalAmount * pricePerUnitInput.value).toFixed(2);
    }
  }
}

function calculatePrices() {
  const typeSelect = document.getElementById("type_id");
  const unitSelect = document.getElementById("unit");
  const totalAmountInput = document.getElementById("total_amount");
  const pricePerUnitInput = document.getElementById("price_per_unit");
  const totalPriceInput = document.getElementById("total_price");
  if (!typeSelect || !unitSelect || !totalAmountInput || !pricePerUnitInput || !totalPriceInput) return;

  if (typeSelect.value && unitSelect.value && totalAmountInput.value) {
    const selectedOption = typeSelect.options[typeSelect.selectedIndex];
    let unitPrice = 0;
    switch (unitSelect.value) {
      case "kg":
        unitPrice = parseFloat(selectedOption.dataset.unitPriceKg);
        break;
      case "packs":
        unitPrice = parseFloat(selectedOption.dataset.packPrice);
        break;
      case "box":
        unitPrice = parseFloat(selectedOption.dataset.boxPrice);
        break;
    }
    pricePerUnitInput.value = unitPrice;
    totalPriceInput.value = (unitPrice * parseFloat(totalAmountInput.value)).toFixed(2);
  }
}

// DELETE FUNCTIONALITY
function confirmDelete(orderId) {
  const modal = document.getElementById("deleteModal");
  if (!modal) return console.error("Delete modal not found");
  modal.style.display = "flex";
  window.deleteOrderId = orderId;
  const confirmButton = document.getElementById("confirmDeleteBtn");
  if (confirmButton) {
    const newConfirmButton = confirmButton.cloneNode(true);
    confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
    newConfirmButton.addEventListener("click", () => executeDelete(orderId));
  }
}

function closeModal() {
  const modal = document.getElementById("deleteModal");
  if (modal) modal.style.display = "none";
}

function executeDelete(orderId) {
  if (!orderId) return console.error("No order ID provided");
  const formData = new FormData();
  formData.append("_method", "POST");
  fetch(`${URLROOT}/Supplier/deleteFertilizerRequest/${orderId}`, {
    method: "POST",
    headers: { "X-Requested-With": "XMLHttpRequest" },
    body: formData,
  })
    .then((response) => {
      if (!response.ok) throw new Error("Network response was not ok");
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        alert(data.message || "Order deleted successfully!");
        window.location.reload();
      } else {
        throw new Error(data.message || "Delete failed");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to delete the order: " + error.message);
      setTimeout(() => window.location.reload(), 2000);
    })
    .finally(() => closeModal());
}

window.onclick = function (event) {
  const modal = document.getElementById("deleteModal");
  if (event.target === modal) closeModal();
};

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") closeModal();
});

// CHARTS
document.addEventListener("DOMContentLoaded", function () {
  const charts = [
    {
      id: "incomeCostChart",
      config: {
        type: "bar",
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
          datasets: [
            { label: "Income", data: [5000, 7000, 8000, 10000, 12000, 14000, 13000, 11000, 15000, 16000, 18000, 20000], backgroundColor: "rgba(75, 192, 192, 0.6)", borderColor: "rgba(75, 192, 192, 1)", borderWidth: 1 },
            { label: "Cost", data: [3000, 4000, 5000, 6000, 700, 8000, 7500, 6000, 9000, 200, 12000, 13000], backgroundColor: "rgba(255, 99, 132, 0.6)", borderColor: "rgba(255, 99, 132, 1)", borderWidth: 1 },
          ],
        },
        options: { responsive: true, plugins: { legend: { position: "top" }, title: { display: true, text: "Income vs Cost for Orders (Yearly)" } }, scales: { x: { beginAtZero: true }, y: { beginAtZero: true } } },
      },
    },
    {
      id: "paymentAnalysisChart",
      config: {
        type: "bar",
        data: {
          labels: ["fertilizer", "teapackets", "income"],
          datasets: [{ data: [120, 10, 200], backgroundColor: ["rgba(255, 99, 35, 0.8)", "rgba(95, 162, 235, 0.8)", "rgba(255, 10, 86, 0.8)"], borderColor: ["rgba(200, 200, 200, 1)"], borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, title: { display: true, text: "Payments" } }, scales: { x: { beginAtZero: true }, y: { beginAtZero: true } } },
      },
    },
    {
      id: "comparePaymentsChart",
      config: {
        type: "bar",
        data: {
          labels: ["payments", "income", "profit", "loss"],
          datasets: [{ data: [4050, 5020, 930, 50], backgroundColor: ["rgba(45, 99, 35, 0.8)", "rgba(95, 67, 87, 0.8)", "rgba(180, 20, 180, 1)", "rgba(93, 65, 89, 1)"], borderColor: ["rgba(200, 200, 200, 1)"], borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, title: { display: true, text: "Payment vs Income" } }, scales: { x: { beginAtZero: true }, y: { beginAtZero: true } } },
      },
    },
    {
      id: "fertilizerOrdersChart",
      config: {
        type: "line",
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
          datasets: [{ label: "Tea Leaves Collections", data: [32, 210, 583, 156, 284, 515, 502, 389, 412, 479, 500, 0], fill: false, backgroundColor: "rgba(75, 192, 192, 0.2)", borderColor: "rgba(75, 192, 192, 1)", borderWidth: 2 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, title: { display: true, text: "Tea Leaves Collections (Monthly)" } }, scales: { x: { title: { display: true, text: "Month" } }, y: { title: { display: true, text: "Amount" }, beginAtZero: true } } },
      },
    },
    {
      id: "fertilizerChart",
      config: {
        type: "line",
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November"],
          datasets: [{ label: "Requests", data: [120, 10, 200, 180, 220, 80, 100, 190, 180, 250, 230, 0], fill: false, backgroundColor: "rgba(75, 192, 192, 0.2)", borderColor: "rgba(75, 192, 192, 1)", borderWidth: 2 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, title: { display: true, text: "Requests (Monthly)" } }, scales: { x: { title: { display: true, text: "Month" } }, y: { title: { display: true, text: "Number of Requests" }, beginAtZero: true } } },
      },
    },
    {
      id: "fertilizerRequestChart",
      config: {
        type: "pie",
        data: {
          labels: ["June", "July", "August", "September", "October", "November"],
          datasets: [{ data: [120, 10, 200, 180, 220, 80], backgroundColor: ["rgba(255, 99, 132, 0.8)", "rgba(54, 162, 235, 0.8)", "rgba(255, 206, 86, 0.8)", "rgba(75, 192, 192, 0.8)", "rgba(153, 102, 255, 0.8)", "rgba(255, 159, 64, 0.8)"], borderColor: ["rgba(255, 99, 132, 1)", "rgba(54, 162, 235, 1)", "rgba(255, 206, 86, 1)", "rgba(75, 192, 192, 1)", "rgba(153, 102, 255, 1)", "rgba(255, 159, 64, 1)"], borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, title: { display: true, text: "Fertilizer Request History (Monthly Distribution)" } } },
      },
    },
    {
      id: "teaLeavesGraph",
      config: {
        type: "bar",
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
          datasets: [{ label: "Tea Leaves Collected (kg)", data: [500, 540, 120, 750, 900, 500, 290, 600, 670, 750, 900, 0], backgroundColor: "rgba(0, 198, 172, 0.712)", borderColor: "rgba(0, 162, 141, 0.712)", borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, tooltip: { enabled: true } }, scales: { x: { title: { display: true, text: "Months" } }, y: { title: { display: true, text: "Amount (kg)" }, beginAtZero: true } } },
      },
    },
    {
      id: "teaLeavesConfirmationGraph",
      config: {
        type: "bar",
        data: {
          labels: ["sent", "pending", "confirmed", "reported"],
          datasets: [{ label: "Tea Leaves Confirmations", data: [70, 48, 12, 1], backgroundColor: ["#007664bc", "#ecb500bc", "#8d9f2dbc", "#ff0800bc"], borderColor: "rgba(0, 0, 0, 0.3)", borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { position: "bottom" }, tooltip: { enabled: true } }, scales: { x: { title: { display: true, text: "Confirmation Status" } }, y: { title: { display: true, text: "No of Suppliers" }, beginAtZero: true } } },
      },
    },
    {
      id: "teaLeavesConfirmationChart",
      config: {
        type: "pie",
        data: {
          labels: ["sent", "pending", "confirmed", "reported"],
          datasets: [{ label: "Tea Leaves Confirmations", data: [70, 48, 12, 1], backgroundColor: ["#007664bc", "#ecb500bc", "#8d9f2dbc", "#ff0800bc"], borderColor: "rgba(0, 0, 0, 0.3)", borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { position: "top" }, tooltip: { enabled: true } } },
      },
    },
  ];

  if (currentPage.includes("dashboard") || currentPage.includes("supplier") || currentPage.includes("manager")) {
    charts.forEach(({ id, config }) => {
      const ctx = document.getElementById(id);
      if (ctx) {
        new Chart(ctx.getContext("2d"), config);
      } else {
        console.warn(`Canvas element with ID "${id}" not found on page ${currentPage}`);
      }
    });
  }

  const form = document.getElementById("fertilizerForm");
  if (form) {
    document.getElementById("type_id")?.addEventListener("change", calculatePrices);
    document.getElementById("unit")?.addEventListener("change", calculatePrices);
    document.getElementById("total_amount")?.addEventListener("input", calculatePrices);
    calculatePrices();
    document.getElementById("type_id")?.addEventListener("change", updatePrice);
    document.getElementById("unit")?.addEventListener("change", updatePrice);
    document.getElementById("total_amount")?.addEventListener("input", updatePrice);

    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      const typeId = document.getElementById("type_id")?.value;
      const unit = document.getElementById("unit")?.value;
      const totalAmount = document.getElementById("total_amount")?.value;
      if (!typeId || !unit || !totalAmount) {
        alert("Please fill in all required fields");
        return;
      }
      calculatePrices();
      try {
        const typeSelect = document.getElementById("type_id");
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const fertilizerName = selectedOption.text;
        const formData = new FormData(form);
        formData.append("fertilizer_name", fertilizerName);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
          params.append(key, value);
        }
        const response = await fetch(form.action, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: params.toString(),
        });
        const result = await response.json();
        if (result.success) {
          alert("Request updated successfully!");
          window.location.href = URLROOT + "/supplier/requestFertilizer";
        } else {
          alert(result.message || "Failed to update request");
        }
        setTimeout(() => window.location.reload(), 2000);
      } catch (error) {
        console.error("Error:", error);
        alert("An error occurred while updating the request");
        setTimeout(() => window.location.reload(), 2000);
      }
    });
  }

  const deleteButtons = document.querySelectorAll(".btn-delete");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", () => confirmDelete(button.getAttribute("data-id")));
  });
});

function updatePrice() {
  const typeSelect = document.getElementById("type_id");
  const unitSelect = document.getElementById("unit");
  const amountInput = document.getElementById("total_amount");
  const pricePerUnitInput = document.getElementById("price_per_unit");
  const totalPriceInput = document.getElementById("total_price");
  if (!typeSelect || !unitSelect || !amountInput || !pricePerUnitInput || !totalPriceInput || !typeSelect.value || !unitSelect.value || !amountInput.value) return;

  const selectedOption = typeSelect.options[typeSelect.selectedIndex];
  let pricePerUnit = 0;
  switch (unitSelect.value) {
    case "kg":
      pricePerUnit = parseFloat(selectedOption.dataset.unitPriceKg);
      break;
    case "packs":
      pricePerUnit = parseFloat(selectedOption.dataset.packPrice);
      break;
    case "box":
      pricePerUnit = parseFloat(selectedOption.dataset.boxPrice);
      break;
  }
  pricePerUnitInput.value = pricePerUnit;
  totalPriceInput.value = (pricePerUnit * parseFloat(amountInput.value)).toFixed(2);
}