function fetchAwaitingInventoryCollections() {
  fetch(`${URLROOT}/inventory/getAwaitingInventoryCollections`)
    .then((response) => response.json())
    .then((data) => {
      populateCollectionApprovalTable(data);
    })
    .catch((error) => {
      console.error("Error fetching collections:", error);
    });
}

function populateCollectionApprovalTable(collections) {
  const tableBody = document.getElementById("collectionApprovalTable");
  tableBody.innerHTML = ""; // Clear existing rows

  collections.forEach((collection) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${collection.driver_id || "N/A"}</td>
            <td>${collection.collection_id}</td>
            <td>${collection.total_quantity} kg</td>
            <td>${new Date(collection.start_time).toLocaleString()}</td>
            <td>${collection.status}</td>
            <td>
                <button class="btn btn-primary" onclick="viewCollectionDetails(${
                  collection.collection_id
                })">View</button>
            </td>
        `;
    tableBody.appendChild(row);
  });
}

// Call the function to fetch collections when the page loads
document.addEventListener("DOMContentLoaded", function () {
  fetchAwaitingInventoryCollections();
});
