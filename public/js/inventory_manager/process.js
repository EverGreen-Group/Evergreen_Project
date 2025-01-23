document.addEventListener("DOMContentLoaded", function () {
  // Report Types Chart
  const reportCtx = document.getElementById("reportTypesChart2");
  if (reportCtx) {
    fetchAvailableRawLeafStock(reportCtx);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const reportCtx = document.getElementById("reportTypesChart");

  if (reportCtx) {
    // Clear any previous chart instances
    Chart.helpers.each(Chart.instances, function (instance) {
      instance.destroy();
    });

    // Hardcoded chaotic data for Normal Leaf and Super Leaf for the past week
    const labels = [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ];
    const normalLeafData = [12, 18, 25, 30, 22, 35, 40]; // Chaotic values for Normal Leaf
    const superLeafData = [8, 15, 20, 25, 18, 30, 28]; // Chaotic values for Super Leaf

    // Create the line chart with the chaotic data
    new Chart(reportCtx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Normal Leaf",
            data: normalLeafData,
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "#36A2EB",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Super Leaf",
            data: superLeafData,
            backgroundColor: "rgba(255, 159, 64, 0.2)",
            borderColor: "#FF9F40",
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
            display: false,
            text: "Weekly Collection of Raw Tea Leaves",
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
              text: "Days of the Week",
            },
          },
        },
      },
    });
  }
});

function fetchAvailableRawLeafStock(reportCtx) {
  fetch(`${URLROOT}/process/getAvailableRawLeafStock`)
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((stock) => stock.name);
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
      console.error("Error fetching available raw leaf stock data:", error);
    });
}

function updateLegend(data) {
  const legendContainer = document.querySelector(".legend-wrapper");
  legendContainer.innerHTML = ""; // Clear existing legend items

  data.forEach((stock, index) => {
    const legendItem = document.createElement("div");
    console.log(stock);
    legendItem.classList.add("legend-item");
    legendItem.innerHTML = `
      <span class="legend-dot" style="background-color: ${getColor(
        index
      )};"></span>
      <span class="legend-text">${stock.name}</span>
    `;
    legendContainer.appendChild(legendItem);
  });
}

function getColor(index) {
  const colors = ["#FF9F40", "#4BC0C0", "#36A2EB", "#9966FF"]; // Define your colors
  return colors[index % colors.length]; // Cycle through colors if there are more types than colors
}
