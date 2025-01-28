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

function addBatch() {
  const batchData = {
    start_time: new Date().toISOString().slice(0, 19).replace("T", " "), // Current date and time
    total_output: 0, // Default value
    total_wastage: 0, // Default value
  };

  fetch(`${URLROOT}/batches/add`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(batchData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      console.log("Batch added successfully:", data);
      // Optionally refresh the batch logs or update the UI
      refreshBatchLogs(); // Call a function to refresh the batch logs
    })
    .catch((error) => {
      console.error("Error adding batch:", error);
    });
}

// Function to refresh the batch logs
function refreshBatchLogs() {
  // Fetch the updated batch logs and update the UI accordingly
  fetch(`${URLROOT}/batches/getBatchesWithoutEndTime`) // Adjust the URL as needed
    .then((response) => response.json())
    .then((batches) => {
      const tableBody = document
        .getElementById("batchLogsTable")
        .getElementsByTagName("tbody")[0];
      tableBody.innerHTML = ""; // Clear existing rows

      batches.forEach((batch) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${batch.batch_id}</td>
          <td>${batch.start_time}</td>
          <td>${batch.end_time ? batch.end_time : "N/A"}</td>
          <td>${batch.total_output_kg} kg</td>
          <td>${batch.total_wastage_kg} kg</td>
          <td>${batch.created_at}</td>
          <td><button class="btn btn-primary">Manage</button></td>
        `;
        tableBody.appendChild(row);
      });
    })
    .catch((error) => {
      console.error("Error fetching batch logs:", error);
    });
}

function openBatchDetailModal(batchId) {
  // Fetch batch details from the server
  fetch(`${URLROOT}/batches/getBatchDetails/${batchId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.batch) {
        // Populate batch information
        document.getElementById("batchIdDetail").innerText =
          data.batch.batch_id;
        document.getElementById("startTimeDetail").innerText =
          data.batch.start_time;
        document.getElementById("totalOutputDetail").innerText =
          data.batch.total_output_kg + " kg";
        document.getElementById("totalWastageDetail").innerText =
          data.batch.total_wastage_kg + " kg";

        // Populate ingredients
        const ingredientDetails = document.getElementById("ingredientDetails");
        ingredientDetails.innerHTML = ""; // Clear existing rows
        data.ingredients.forEach((ingredient) => {
          ingredientDetails.innerHTML += `
            <tr>
              <td>${ingredient.ingredient_id}</td>
              <td>${ingredient.leaf_type_id}</td>
              <td>${ingredient.quantity_used_kg}</td>
              <td>${ingredient.added_at}</td>
            </tr>
          `;
        });

        // Populate outputs
        const outputDetails = document.getElementById("outputDetails");
        outputDetails.innerHTML = ""; // Clear existing rows
        data.outputs.forEach((output) => {
          outputDetails.innerHTML += `
            <tr>
              <td>${output.processed_id}</td>
              <td>${output.leaf_type_id}</td>
              <td>${output.grading_id}</td>
              <td>${output.output_kg}</td>
              <td>${output.processed_at}</td>
            </tr>
          `;
        });

        // Populate machine usage
        const machineUsageDetails = document.getElementById(
          "machineUsageDetails"
        );
        machineUsageDetails.innerHTML = ""; // Clear existing rows
        data.machineUsage.forEach((usage) => {
          machineUsageDetails.innerHTML += `
            <tr>
              <td>${usage.usage_id}</td>
              <td>${usage.machine_id}</td>
              <td>${usage.operator_id}</td>
              <td>${usage.start_time}</td>
              <td>${usage.end_time ? usage.end_time : "N/A"}</td>
              <td>${usage.notes}</td>
            </tr>
          `;
        });

        // Show the modal
        document.getElementById("openBatchDetailModal").style.display = "block";
      } else {
        console.error("Batch not found");
      }
    })
    .catch((error) => {
      console.error("Error fetching batch details:", error);
    });
}

function addIngredient() {
  const batchId = document.getElementById("batchIdDetail").innerText; // Get the batch ID
  const leafTypeId = prompt("Enter Leaf Type ID:"); // Prompt for Leaf Type ID
  const quantityUsedKg = prompt("Enter Quantity Used (kg):"); // Prompt for Quantity Used

  const ingredientData = {
    batch_id: batchId,
    leaf_type_id: leafTypeId,
    quantity_used_kg: quantityUsedKg,
  };

  fetch(`${URLROOT}/batches/addIngredient`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(ingredientData),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data.message);
      // Optionally refresh the ingredient details in the modal
    })
    .catch((error) => {
      console.error("Error adding ingredient:", error);
    });
}

function addOutput() {
  const batchId = document.getElementById("batchIdDetail").innerText; // Get the batch ID
  const leafTypeId = prompt("Enter Leaf Type ID:"); // Prompt for Leaf Type ID
  const gradingId = prompt("Enter Grading ID:"); // Prompt for Grading ID
  const outputKg = prompt("Enter Output (kg):"); // Prompt for Output

  const outputData = {
    batch_id: batchId,
    leaf_type_id: leafTypeId,
    grading_id: gradingId,
    output_kg: outputKg,
  };

  fetch(`${URLROOT}/batches/addOutput`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(outputData),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data.message);
      // Optionally refresh the output details in the modal
    })
    .catch((error) => {
      console.error("Error adding output:", error);
    });
}

function addMachineUsage() {
  const batchId = document.getElementById("batchIdDetail").innerText; // Get the batch ID
  const machineId = prompt("Enter Machine ID:"); // Prompt for Machine ID
  const operatorId = prompt("Enter Operator ID:"); // Prompt for Operator ID
  const notes = prompt("Enter Notes:"); // Prompt for Notes

  const machineUsageData = {
    batch_id: batchId,
    machine_id: machineId,
    operator_id: operatorId,
    notes: notes,
  };

  fetch(`${URLROOT}/batches/addMachineUsage`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(machineUsageData),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data.message);
      // Optionally refresh the machine usage details in the modal
    })
    .catch((error) => {
      console.error("Error adding machine usage:", error);
    });
}
