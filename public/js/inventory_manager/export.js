document.addEventListener("DOMContentLoaded", function () {
  const reportCtx = document.getElementById("reportTypesChart");

  if (reportCtx) {
    // Clear any previous chart instances
    Chart.helpers.each(Chart.instances, function (instance) {
      instance.destroy();
    });

    new Chart(reportCtx, {
      type: "line",
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        datasets: [
          {
            label: "Black Tea",
            data: [
              5000, 4800, 5200, 5300, 5500, 6000, 6200, 6100, 6300, 6400, 6500,
              6700,
            ],
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "#36A2EB",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Green Tea",
            data: [
              3000, 3200, 3100, 3300, 3400, 3500, 3600, 3700, 3800, 3900, 4000,
              4100,
            ],
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "#4BC0C0",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Herbal Tea",
            data: [
              2000, 2100, 2200, 2500, 2400, 2300, 2600, 2700, 2800, 2900, 3000,
              3100,
            ],
            backgroundColor: "rgba(255, 206, 86, 0.2)",
            borderColor: "#FFCE56",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Oolong Tea",
            data: [
              1500, 1600, 1700, 1800, 1900, 2000, 2100, 2200, 2300, 2400, 2500,
              2600,
            ],
            backgroundColor: "rgba(153, 102, 255, 0.2)",
            borderColor: "#9966FF",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "top",
            labels: {
              padding: 20,
              font: {
                size: 12,
              },
            },
          },
          title: {
            display: true,
            text: "Monthly Tea Leaf Stock",
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(0, 0, 0, 0.1)",
            },
            ticks: {
              callback: function (value) {
                return value + " kg";
              },
            },
            title: {
              display: true,
              text: "Stock (kg)",
            },
          },
          x: {
            grid: {
              display: false,
            },
            title: {
              display: true,
              text: "Months",
            },
          },
        },
      },
    });
  }
});

function viewExportModal() {
  // Show the modal
  document.getElementById("viewExportModal").style.display = "block";
}

function addStockModal() {
  // Show the modal
  document.getElementById("addStockModal").style.display = "block";
}

function addExportRecord(event) {
  // Gather data from the modal
  const teaType = document.getElementById("exportTeaType").value;
  const grading = document.getElementById("exportGrading").value;
  const quantity = document.getElementById("exportQuantity").value;
  const pricePerKg = document.getElementById("exportPrice").value;
  const company = document.getElementById("exportCompany").value;
  const notes = document.getElementById("exportNotes").value;

  // Example of how you might send this data to your server
  const exportData = {
    teaType,
    grading,
    quantity,
    pricePerKg,
    company,
    notes,
  };
  document.getElementById("addExportModal").style.display = "block";
  // Send exportData to your server (using fetch or another method)
  console.log("Export Record:", exportData);
  // Implement your AJAX call here to save the export record
}

document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("orderRevenueChart").getContext("2d");

  // Sample data for the past week
  const data = {
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
        label: "Daily Revenue (Rs.)",
        data: [337500, 425000, 385000, 562000, 298000, 445000, 378000],
        borderColor: "#36A2EB",
        backgroundColor: "rgba(54, 162, 235, 0.1)",
        tension: 0.4,
        fill: true,
        pointBackgroundColor: "#36A2EB",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
      },
    ],
  };

  const config = {
    type: "line",
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          padding: 12,
          titleFont: {
            size: 14,
            weight: "bold",
          },
          bodyFont: {
            size: 13,
          },
          callbacks: {
            label: function (context) {
              return "Revenue: Rs. " + context.parsed.y.toLocaleString();
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return "Rs. " + value.toLocaleString();
            },
          },
          grid: {
            color: "rgba(0, 0, 0, 0.05)",
          },
        },
        x: {
          grid: {
            display: false,
          },
        },
      },
    },
  };

  new Chart(ctx, config);
});
