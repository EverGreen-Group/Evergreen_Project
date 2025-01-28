// Call the function to fetch collections when the page loads
document.addEventListener("DOMContentLoaded", function () {
  fetchAwaitingInventoryCollections();
});

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

function viewCollectionDetails(collectionId) {
  fetch(`${URLROOT}/inventory/getCollectionDetails/${collectionId}`)
    .then((response) => response.json())
    .then((data) => {
      // Populate collection information
      document.getElementById("collectionInfo").innerHTML = `
                <strong>Collection ID:</strong> ${data.collection_id}<br>
                <strong>Status:</strong> ${data.status}<br>
                <strong>Total Quantity:</strong> ${data.total_quantity} kg<br>
                <strong>Start Time:</strong> ${new Date(
                  data.start_time
                ).toLocaleString()}<br>
                <strong>End Time:</strong> ${new Date(
                  data.end_time
                ).toLocaleString()}<br>
            `;

      // Populate suppliers' details
      const supplierTableBody = document.getElementById("supplierDetailsTable");
      supplierTableBody.innerHTML = ""; // Clear existing rows

      const supplierRequests = data.suppliers.map((supplier) => {
        const row = document.createElement("tr");
        row.innerHTML = `
                    <td>${supplier.full_name}</td>
                    <td>${supplier.quantity} kg</td>
                    <td>${supplier.notes || "N/A"}</td>

                    <td id="bagsCount-${supplier.supplier_id}">Loading...</td>
                    <td id="approvedBagsCount-${
                      supplier.supplier_id
                    }">Loading...</td>
                    <td>
                        <button class="btn btn-primary" onclick="viewSupplierBags(${
                          supplier.supplier_id
                        }, ${collectionId})">View Bags</button>
                    </td>
                `;
        supplierTableBody.appendChild(row);

        // Fetch total and approved bags for each supplier
        return fetch(`${URLROOT}/inventory/getBagsCountBySupplier`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            supplierId: supplier.supplier_id,
            collectionId: collectionId,
          }),
        })
          .then((response) => response.json())
          .then((bagsCount) => {
            const totalBags = bagsCount.total_bags || 0;
            const approvedBags = bagsCount.approved_bags || 0;
            document.getElementById(
              `bagsCount-${supplier.supplier_id}`
            ).innerHTML = `${totalBags}`;
            document.getElementById(
              `approvedBagsCount-${supplier.supplier_id}`
            ).innerHTML = `${approvedBags}`;
          })
          .catch((error) => {
            console.error("Error fetching bags count:", error);
          });
      });

      // Set the onclick for the finalize button
      const finalizeButton = document.querySelector("#finalizeButton");
      finalizeButton.setAttribute(
        "onclick",
        `finalizeCollection(${collectionId})`
      );

      // Wait for all supplier requests to complete
      Promise.all(supplierRequests).then(() => {
        // Show the modal after all data is loaded
        document.getElementById("viewCollectionModal").style.display = "block";
      });
    })
    .catch((error) => {
      console.error("Error fetching collection details:", error);
    });
}

function approveSupplier(supplierId) {
  // Implement the logic to approve the supplier
  console.log("Approving supplier with ID:", supplierId);
  // You can add your approval logic here
}

function viewSupplierBags(supplierId, collectionId) {
  event.preventDefault();
  console.log("Opening bag modal for supplier ID:", supplierId);

  // Prepare the data to be sent in the request body
  const requestData = {
    supplierId: supplierId,
    collectionId: collectionId,
  };

  fetch(`${URLROOT}/inventory/getBagsBySupplier`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(requestData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      const bagTableBody = document.getElementById("bagDetailsTable");
      bagTableBody.innerHTML = ""; // Clear existing rows

      data.forEach((bag) => {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>${bag.bag_id}</td>
        <td>${bag.capacity_kg} kg</td>
        <td>${bag.actual_weight_kg || "N/A"}</td>
        <td>${bag.leaf_age || "N/A"}</td>
        <td>${bag.name || "N/A"}</td>
        <td>${bag.moisture_level || "N/A"}</td>
        <td>${bag.action || "N/A"}</td>
        <td><button class="btn btn-primary" onclick="inspectBagDetails(${
          bag.bag_id
        })">Inspect</button></td>
      `;
        bagTableBody.appendChild(row);
      });

      // Hide the parent modal
      document.getElementById("viewCollectionModal").style.display = "none"; // Hide the parent modal

      // Show the bag modal
      document.getElementById("viewBagModal").style.display = "block"; // Show the bag modal
      console.log("Bag modal displayed");
    })
    .catch((error) => {
      console.error("Error fetching bag details:", error);
    });
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

function inspectBagDetails(bagId) {
  event.preventDefault();
  fetch(`${URLROOT}/inventory/getBagDetails/${bagId}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      // Populate the inspectBagModal with the fetched data
      document.getElementById("bagCollectionId").innerText = data.collection_id;
      document.getElementById("inspectBagId").innerText = data.bag_id;
      document.getElementById("inspectCapacity").innerText =
        data.actual_weight_kg;
      document.getElementById("inspectBagWeight").innerText = data.capacity_kg;
      document.getElementById("inspectStatus").innerText = data.action;
      document.getElementById("inspectSupplier").innerText = data.supplier_id;
      document.getElementById("inspectMoisture").innerText =
        data.moisture_level;
      document.getElementById("inspectLeafAge").innerText = data.leaf_age;
      document.getElementById("inspectDeductionNotes").innerText =
        data.deduction_notes;

      // Set the QR image source
      document.getElementById(
        "inspectQrImage"
      ).src = `/Evergreen_Project/uploads/qr_codes/${data.bag_id}.png`; // Assuming the QR code filename is based on bag_id

      if (data.action === "approved") {
        // Disable or hide the buttons if the action is approved
        document.querySelector(".button-group").style.display = "none"; // Hide the button group
        // Alternatively, you can disable the buttons instead of hiding
        // document.querySelector('.btn.btn-primary').disabled = true;
        // document.querySelector('.btn.btn-secondary').disabled = true;
      } else {
        // Show the button group if the action is not approved
        document.querySelector(".button-group").style.display = "flex"; // Show the button group
      }

      // Show the inspectBagModal
      document.getElementById("inspectBagModal").style.display = "block";
    })
    .catch((error) => {
      console.error("Error fetching bag details:", error);
    });
}

/////////////////////////////////////////////////////////////////////////////////////

// BAG APPROVING PART

function approveBag() {
  const bagId = document.getElementById("inspectBagId").innerText;
  const collectionId = document.getElementById("bagCollectionId").innerText; // Assuming you have this element in the modal
  const supplierId = document.getElementById("inspectSupplier").innerText; // Assuming you have this element in the modal

  // Prepare the data to be sent in the request body
  const inspectionData = {
    bag_id: bagId,
    collection_id: collectionId,
    supplier_id: supplierId,
  };

  // AJAX request to approve the bag
  fetch(`${URLROOT}/inventory/approveBag`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(inspectionData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      console.log("Bag approved successfully:", data);
      // Optionally, you can close the modal or refresh the data
      closeModal("inspectBagModal");
    })
    .catch((error) => {
      console.error("Error approving bag:", error);
    });
}

async function finalizeCollection(collectionId) {
  try {
    // Check if all bags are approved
    const response = await fetch(`${URLROOT}/inventory/checkAllBagsApproved`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ collectionId: collectionId }),
    });

    const data = await response.json();

    if (data.allApproved) {
      // Proceed to finalize the collection
      const finalizeResponse = await fetch(
        `${URLROOT}/inventory/finalizeCollection`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ collectionId: collectionId }),
        }
      );

      const finalizeData = await finalizeResponse.json();

      if (finalizeData.success) {
        alert("Collection finalized and added to inventory successfully!");
        closeModal("viewCollectionModal");
        // Optionally refresh the inventory view
      } else {
        alert("Failed to finalize collection: " + finalizeData.message);
      }
    } else {
      alert(
        "Not all bags are approved. Please approve all bags before finalizing."
      );
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred while processing your request.");
  }
}
