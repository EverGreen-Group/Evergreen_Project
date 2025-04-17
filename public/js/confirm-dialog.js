// Custom confirmation dialog to replace browser's default confirm
function customConfirm(message, yesCallback, noCallback) {
  // Create overlay
  const overlay = document.createElement("div");
  overlay.className = "confirm-overlay";

  // Create dialog container
  const dialog = document.createElement("div");
  dialog.className = "confirm-dialog";

  // Create dialog content
  const content = document.createElement("div");
  content.className = "confirm-content";

  // Create message
  const messageEl = document.createElement("p");
  messageEl.className = "confirm-message";
  messageEl.textContent = message;

  // Create buttons container
  const buttonsContainer = document.createElement("div");
  buttonsContainer.className = "confirm-buttons";

  // Create Yes button
  const yesBtn = document.createElement("button");
  yesBtn.className = "confirm-btn confirm-yes";
  yesBtn.textContent = "Yes";
  yesBtn.addEventListener("click", function () {
    // Remove dialog
    document.body.removeChild(overlay);
    // Execute callback if provided
    if (typeof yesCallback === "function") {
      yesCallback();
    }
  });

  // Create No button
  const noBtn = document.createElement("button");
  noBtn.className = "confirm-btn confirm-no";
  noBtn.textContent = "No";
  noBtn.addEventListener("click", function () {
    // Remove dialog
    document.body.removeChild(overlay);
    // Execute callback if provided
    if (typeof noCallback === "function") {
      noCallback();
    }
  });

  // Assemble the dialog
  buttonsContainer.appendChild(noBtn);
  buttonsContainer.appendChild(yesBtn);
  content.appendChild(messageEl);
  content.appendChild(buttonsContainer);
  dialog.appendChild(content);
  overlay.appendChild(dialog);

  // Add to DOM
  document.body.appendChild(overlay);
}

// Initialize confirm dialogs for elements with data-confirm attribute
document.addEventListener("DOMContentLoaded", function () {
  // Find all delete forms and buttons with data-confirm attribute
  const deleteButtons = document.querySelectorAll("[data-confirm]");

  deleteButtons.forEach(function (button) {
    // Remove any existing onsubmit handlers from parent forms
    const form = button.closest("form");
    if (form) {
      form.removeAttribute("onsubmit");
    }

    // Add click handler to the button
    button.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const message = this.getAttribute("data-confirm") || "Are you sure?";

      customConfirm(message, function () {
        // If it's in a form, submit the form
        if (form) {
          form.submit();
        }
      });
    });
  });
});
