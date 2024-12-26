document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".bag-assignment-form");
  const input = document.querySelector(".bag-id-input");
  const addButton = document.querySelector(".add-bag-btn");
  const bagsList = document.querySelector(".assigned-bags-list");
  const assignedBags = new Set();

  // Add bag when button is clicked
  addButton.addEventListener("click", function () {
    const bagId = input.value.trim();

    if (!bagId) return;

    if (assignedBags.has(bagId)) {
      alert("This bag is already in the list");
      return;
    }

    // Add to our tracking Set
    assignedBags.add(bagId);

    // Remove "no bags" message if it exists
    const noBagsMessage = bagsList.querySelector(".no-bags-message");
    if (noBagsMessage) {
      noBagsMessage.remove();
    }

    // Create and add the bag element
    const bagElement = document.createElement("div");
    bagElement.className = "assigned-bag";
    bagElement.innerHTML = `
      <span>Bag #${bagId}</span>
      <button type="button" class="remove-bag-btn">×</button>
      <input type="hidden" name="bags[]" value="${bagId}">
    `;

    // Add remove functionality
    bagElement.querySelector(".remove-bag-btn").onclick = function () {
      assignedBags.delete(bagId);
      bagElement.remove();
      if (assignedBags.size === 0) {
        bagsList.innerHTML =
          '<p class="no-bags-message">No bags assigned yet</p>';
      }
    };

    // Add to list
    bagsList.appendChild(bagElement);

    // Clear input
    input.value = "";
    input.focus();
  });

  // Form validation
  form.onsubmit = function (e) {
    if (assignedBags.size === 0) {
      e.preventDefault();
      alert("Add at least one bag");
    }
  };
});
