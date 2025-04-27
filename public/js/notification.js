// notification.js - Updated for better presentation
const notifications = {
  show: function (message, type = "success") {
    const container = document.getElementById("notification-container");
    if (!container) {
      console.error("Notification container not found");
      return;
    }

    // Clear any existing notifications
    container.innerHTML = "";

    const notification = document.createElement("div");
    notification.className = `notification-popup ${type}`;

    // Add icon based on notification type
    let icon = "✓";
    if (type === "error") icon = "✕";
    if (type === "warning") icon = "⚠";
    if (type === "info") icon = "ℹ";

    notification.innerHTML = `<strong>${icon} ${message}</strong>`;

    container.appendChild(notification);

    // Show with animation
    setTimeout(() => notification.classList.add("show"), 10);

    // Auto-hide after 4 seconds (longer display time)
    setTimeout(() => {
      notification.style.opacity = "0";
      notification.style.transform = "translateY(30px)";
      setTimeout(() => notification.remove(), 500);
    }, 4000);
  },
};

// Check for PHP session-based flash messages
document.addEventListener("DOMContentLoaded", function () {
  // This will be populated by PHP when the page loads
  const flashMessageElement = document.getElementById("php-flash-message");
  if (flashMessageElement) {
    const message = flashMessageElement.getAttribute("data-message");
    const type = flashMessageElement.getAttribute("data-type");

    if (message) {
      notifications.show(message, type || "success");
    }
  }
});
