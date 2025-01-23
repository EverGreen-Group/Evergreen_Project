document.addEventListener("DOMContentLoaded", function () {
  // Report Types Chart
  const reportCtx = document.getElementById("reportTypesChart");
  if (reportCtx) {
    fetchAvailableTeaStock(reportCtx);
  }

  fetchTeaStock();
});

function viewStockModal() {
  // Show the modal
  document.getElementById("viewStockModal").style.display = "block";
}

function fetchTeaStock() {
  fetch(`${URLROOT}/inventory/getTeaStock`)
    .then((response) => response.json())
    .then((data) => {
      updateTeaStockTable(data);
    })
    .catch((error) => {
      console.error("Error fetching tea stock data:", error);
    });
}

function updateTeaStockTable(teaStockData) {
  const tableBody = document.querySelector("#teaStockTable tbody");
  tableBody.innerHTML = ""; // Clear existing rows

  teaStockData.forEach((stock) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${stock.leaf_type}</td>
      <td>${stock.grading_count}</td>
      <td>${stock.total_stock} kg</td>
      <td>
        <div style="display: flex; justify-content: center; margin-right: 80px; gap: 30px;">
          <button class="btn btn-primary" onclick="openViewStockModal(${stock.leaf_type_id})">View</button>
        </div>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

function fetchAvailableTeaStock(reportCtx) {
  fetch(`${URLROOT}/inventory/getAvailableTeaStock`)
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((stock) => stock.leaf_type);
      const stockValues = data.map((stock) => stock.total_stock);

      // Create the chart with the fetched data
      new Chart(reportCtx, {
        type: "doughnut",
        data: {
          labels: labels,
          datasets: [
            {
              data: stockValues,
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

      // Update the custom legend
      updateLegend(data);
    })
    .catch((error) => {
      console.error("Error fetching available tea stock data:", error);
    });
}

function updateLegend(data) {
  const legendContainer = document.querySelector(".legend-wrapper");
  legendContainer.innerHTML = ""; // Clear existing legend items

  data.forEach((stock, index) => {
    const legendItem = document.createElement("div");
    legendItem.classList.add("legend-item");
    legendItem.innerHTML = `
      <span class="legend-dot" style="background-color: ${getColor(
        index
      )};"></span>
      <span class="legend-text">${stock.leaf_type}</span>
    `;
    legendContainer.appendChild(legendItem);
  });
}

function getColor(index) {
  const colors = ["#FF9F40", "#4BC0C0", "#36A2EB", "#9966FF"]; // Define your colors
  return colors[index % colors.length]; // Cycle through colors if there are more types than colors
}

function addStock(event) {
  event.preventDefault(); // Prevent the default form submission

  // Gather data from the modal
  const teaType = document.getElementById("teaType").value;
  const grading = document.getElementById("grading").value;
  const quantity = document.getElementById("quantity").value;
  const notes = document.getElementById("notes").value;

  // Create the data object
  const stockData = {
    tea_type: teaType,
    grading: grading,
    quantity: quantity,
    notes: notes,
  };

  // Send the data to the server via AJAX
  fetch(`${URLROOT}/inventory/addStock`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(stockData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Close the modal
        closeModal("addStockModal");
        // Optionally refresh the stock data
        fetchTeaStock(); // Call the function to refresh the stock overview
      } else {
        alert("Failed to add stock. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Error adding stock:", error);
    });
}

function openAddStockModal() {
  fetchTeaTypes(); // Fetch tea types when the modal opens
  document.getElementById("teaType").addEventListener("change", function () {
    const selectedTeaTypeId = this.value;
    fetchGradings(selectedTeaTypeId);
  });
  document.getElementById("addStockModal").style.display = "block"; // Show the modal
}

function fetchTeaTypes() {
  fetch(`${URLROOT}/inventory/getTeaTypes`)
    .then((response) => response.json())
    .then((data) => {
      const teaTypeSelect = document.getElementById("teaType");
      teaTypeSelect.innerHTML = '<option value="">Select a Tea Type</option>'; // Clear existing options

      data.forEach((tea) => {
        const option = document.createElement("option");
        option.value = tea.leaf_type_id; // Set the value to the ID
        option.textContent = tea.name; // Set the display text
        teaTypeSelect.appendChild(option);
      });
    })
    .catch((error) => {
      console.error("Error fetching tea types:", error);
    });
}

document.getElementById("teaType").addEventListener("change", function () {
  const selectedTeaTypeId = this.value;
  fetchGradings(selectedTeaTypeId);
});

function fetchGradings(teaTypeId) {
  fetch(`${URLROOT}/inventory/getGradings/${teaTypeId}`)
    .then((response) => response.json())
    .then((data) => {
      const gradingSelect = document.getElementById("grading");
      gradingSelect.innerHTML = '<option value="">Select a Grading</option>'; // Clear existing options

      data.forEach((grading) => {
        const option = document.createElement("option");
        option.value = grading.grading_id; // Set the value to the grading ID
        option.textContent = grading.name; // Set the display text
        gradingSelect.appendChild(option);
      });
    })
    .catch((error) => {
      console.error("Error fetching gradings:", error);
    });
}

// FOR THE STOCK BREAKDOWNS

function openViewStockModal(teaTypeId) {
  fetchStockDetails(teaTypeId); // Fetch stock details for the selected tea type
  document.getElementById("viewStockModal").style.display = "block"; // Show the modal
}

function fetchStockDetails(teaTypeId) {
  fetch(`${URLROOT}/inventory/getStockDetails/${teaTypeId}`)
    .then((response) => response.json())
    .then((data) => {
      populateStockDetails(data);
    })
    .catch((error) => {
      console.error("Error fetching stock details:", error);
    });
}

function populateStockDetails(stockDetails) {
  const tbody = document.querySelector("#viewStockModal table tbody");
  tbody.innerHTML = ""; // Clear existing rows

  let totalStock = 0; // Initialize total stock

  stockDetails.forEach((detail) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${detail.grading_name}</td>
      <td>${detail.total_stock} kg</td>
      <td>${detail.last_added || "N/A"}</td>
      <td>${detail.last_deducted || "N/A"}</td>
    `;
    tbody.appendChild(row);
    totalStock += parseFloat(detail.total_stock); // Accumulate total stock
  });

  // Update total stock in the footer
  const totalRow = document.querySelector("#viewStockModal table tfoot tr");
  totalRow.querySelector(
    "td:nth-child(2)"
  ).innerHTML = `<strong>${totalStock} kg</strong>`;
}
