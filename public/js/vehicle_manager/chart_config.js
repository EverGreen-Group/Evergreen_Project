document.addEventListener("DOMContentLoaded", function () {
  const vehicleData = JSON.parse(
    document.getElementById("vehicleData").textContent
  );
  const types = vehicleData.map((item) => item.vehicle_type);
  const counts = vehicleData.map((item) => parseInt(item.count));

  new Chart(document.getElementById("vehicleTypesChart"), {
    type: "doughnut",
    data: {
      labels: types,
      datasets: [
        {
          data: counts,
          backgroundColor: [
            "var(--main)",
            "var(--light-main)",
            "var(--yellow)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
        },
        title: {
          display: true,
          text: "Available Vehicles by Type",
          font: { size: 16 },
        },
      },
    },
  });
});
