document.addEventListener("DOMContentLoaded", function () {
  // Report Types Chart
  const reportCtx = document.getElementById("reportTypesChart");
  if (reportCtx) {
    new Chart(reportCtx, {
      type: "doughnut",
      data: {
        labels: ["Black Tea", "Green Tea", "Herbal Tea", "Oolong Tea"],
        datasets: [
          {
            data: [5000, 3000, 2000, 1500],
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
  }
});

function viewStockModal() {
  // Show the modal
  document.getElementById("viewStockModal").style.display = "block";
}

function addStockModal() {
  // Show the modal
  document.getElementById("addStockModal").style.display = "block";
}
