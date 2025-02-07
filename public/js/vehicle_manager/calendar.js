// pure_calendar.js

class Calendar {
  constructor(containerId, onDateSelect) {
    this.container = document.getElementById(containerId);
    this.onDateSelect = onDateSelect;
    this.date = new Date();
    this.currentMonth = this.date.getMonth();
    this.currentYear = this.date.getFullYear();
    this.months = [
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
    ];
    this.init();
  }

  init() {
    this.createCalendarHTML();
    this.renderCalendar();
    this.addEventListeners();
  }

  createCalendarHTML() {
    this.container.innerHTML = `
              <div class="head">
  
                  <h3 id="monthDisplay"></h3>
                  <button class="btn btn-primary" id="prevMonth">&lt;</button>
                  <button class="btn btn-primary" style="margin-right:30px;" id="nextMonth">&gt;</button>
              </div>
              <table class="calendar">
                  <thead>
                      <tr>
                          <th>Sun</th>
                          <th>Mon</th>
                          <th>Tue</th>
                          <th>Wed</th>
                          <th>Thu</th>
                          <th>Fri</th>
                          <th>Sat</th>
                      </tr>
                  </thead>
                  <tbody id="calendarBody"></tbody>
              </table>
          `;
  }

  renderCalendar() {
    const firstDay = new Date(this.currentYear, this.currentMonth, 1);
    const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
    const startingDay = firstDay.getDay();
    const monthLength = lastDay.getDate();

    document.getElementById("monthDisplay").textContent = `${
      this.months[this.currentMonth]
    } ${this.currentYear}`;

    const calendarBody = document.getElementById("calendarBody");
    let date = 1;
    let html = "";

    for (let i = 0; i < 6; i++) {
      let row = "<tr>";
      for (let j = 0; j < 7; j++) {
        if (i === 0 && j < startingDay) {
          row += "<td></td>";
        } else if (date > monthLength) {
          row += "<td></td>";
        } else {
          const currentDate = new Date();
          const isToday =
            date === currentDate.getDate() &&
            this.currentMonth === currentDate.getMonth() &&
            this.currentYear === currentDate.getFullYear();

          row += `<td class="calendar-day${isToday ? " today" : ""}" 
                          data-date="${date}">${date}</td>`;
          date++;
        }
      }
      row += "</tr>";
      html += row;
      if (date > monthLength) break;
    }

    calendarBody.innerHTML = html;
  }

  addEventListeners() {
    document.getElementById("prevMonth").addEventListener("click", () => {
      this.currentMonth--;
      if (this.currentMonth < 0) {
        this.currentMonth = 11;
        this.currentYear--;
      }
      this.renderCalendar();
    });

    document.getElementById("nextMonth").addEventListener("click", () => {
      this.currentMonth++;
      if (this.currentMonth > 11) {
        this.currentMonth = 0;
        this.currentYear++;
      }
      this.renderCalendar();
    });

    this.container.addEventListener("click", (e) => {
      if (e.target.classList.contains("calendar-day")) {
        const selectedDate = new Date(
          this.currentYear,
          this.currentMonth,
          parseInt(e.target.dataset.date)
        );
        if (this.onDateSelect) {
          this.onDateSelect(selectedDate);
        }

        // Remove previous selection
        document.querySelectorAll(".calendar-day").forEach((day) => {
          day.classList.remove("selected");
        });
        e.target.classList.add("selected");
      }
    });
  }
}
