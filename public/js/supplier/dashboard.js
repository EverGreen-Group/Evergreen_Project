document.addEventListener("DOMContentLoaded", function () {
  initializeScheduleCards();
});

function initializeScheduleCards() {
  const scheduleData = [
    {
      date: "Tomorrow",
      time: "08:00 AM",
      orderId: "11",
      amount: "20",
      status: "Pending",
    },
    {
      date: "2024/11/05",
      time: "09:00 AM",
      orderId: "10",
      amount: "20",
      status: "Delivered",
    },
    {
      date: "2024/10/12",
      time: "08:00 AM",
      orderId: "9",
      amount: "20",
      status: "Delivered",
    },
    {
      date: "2024/10/07",
      time: "09:00 AM",
      orderId: "12",
      amount: "26",
      status: "Delivered",
    },
  ];

  let currentIndex = 0;
  const elements = {
    cardContainer: document.querySelector(".schedule-card"),
    prevBtn: document.querySelector(".prev-btn"),
    nextBtn: document.querySelector(".next-btn"),
    currentCardSpan: document.querySelector(".current-card"),
    totalCardsSpan: document.querySelector(".total-cards"),
  };

  function updateCard() {
    const data = scheduleData[currentIndex];
    const cardContent = generateCardContent(data);
    elements.cardContainer.querySelector(".card-content").innerHTML =
      cardContent;
    elements.currentCardSpan.textContent = currentIndex + 1;
  }

  function generateCardContent(data) {
    return `
            <div class="card-header">
                <div class="status-badge ${data.status.toLowerCase()}">${
      data.status
    }</div>
            </div>
            <div class="card-body">
                <div class="schedule-info">
                    <div class="info-item">
                        <i class='bx bx-calendar'></i>
                        <span>${data.date}</span>
                    </div>
                    <div class="info-item">
                        <i class='bx bx-time-five'></i>
                        <span>${data.time}</span>
                    </div>
                    <div class="info-item">
                        <i class='bx bx-package'></i>
                        <span>Order #${data.orderId}</span>
                    </div>
                    <div class="info-item">
                        <i class='bx bx-leaf'></i>
                        <span>${data.amount} kg</span>
                    </div>
                </div>
            </div>
        `;
  }

  function setupEventListeners() {
    elements.prevBtn.addEventListener("click", () => {
      currentIndex =
        (currentIndex - 1 + scheduleData.length) % scheduleData.length;
      updateCard();
    });

    elements.nextBtn.addEventListener("click", () => {
      currentIndex = (currentIndex + 1) % scheduleData.length;
      updateCard();
    });
  }

  // Initialize
  elements.totalCardsSpan.textContent = scheduleData.length;
  setupEventListeners();
  updateCard();
}
