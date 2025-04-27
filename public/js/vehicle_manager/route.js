document.addEventListener("DOMContentLoaded", function () {
  const dayFilter = document.getElementById("day-filter");
  const suppliersTable = document.getElementById("suppliers-table");

  dayFilter.addEventListener("change", function () {
    const selectedDay = this.value;

    const tbody = suppliersTable.getElementsByTagName("tbody")[0];
    const rows = tbody.getElementsByTagName("tr");

    for (let row of rows) {
      const preferredDayElement = row.querySelector(".preferred-day");

      const preferredDay = preferredDayElement.textContent.trim();

      if (selectedDay === "" || preferredDay === selectedDay) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    }
  });
});

/////////////////////////////////
