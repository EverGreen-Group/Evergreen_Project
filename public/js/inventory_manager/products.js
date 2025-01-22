function previewImage(input) {
  const preview = document.getElementById("imagePreview");
  const file = input.files[0];

  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.innerHTML = `<img src="${e.target.result}" alt="Product Preview">`;
    };
    reader.readAsDataURL(file);
  }
}

document.querySelector(".image-preview").addEventListener("click", function () {
  document.getElementById("productImage").click();
});

async function submitProductForm(event) {
  event.preventDefault();

  const form = document.getElementById("createProductForm");
  const formData = new FormData(form);

  const productData = {
    tea_type: formData.get("tea_type"),
    grade: formData.get("grade"),
    product_name: formData.get("product_name"),
    price_per_kg: parseFloat(formData.get("price_per_kg")),
    initial_stock: parseFloat(formData.get("initial_stock")),
    description: formData.get("description"),
  };

  try {
    // Handle image upload
    const imageFile = formData.get("product_image");
    if (imageFile) {
      const imageFormData = new FormData();
      imageFormData.append("product_image", imageFile);

      const imageResponse = await fetch(`${URLROOT}/products/uploadImage`, {
        method: "POST",
        body: imageFormData,
      });

      const imageResult = await imageResponse.json();
      if (imageResult.success) {
        productData.image_path = imageResult.image_path;
      }
    }

    // Send product data
    const response = await fetch(`${URLROOT}/products/add`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(productData),
    });

    const result = await response.json();

    if (result.success) {
      closeModal("addProductModal");
      location.reload();
    } else {
      alert("Failed to add product");
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred");
  }
}

function previewImage(input) {
  const preview = document.getElementById("imagePreview");
  const file = input.files[0];

  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.innerHTML = `<img src="${e.target.result}" alt="Product Preview">`;
    };
    reader.readAsDataURL(file);
  }
}

document.querySelector(".image-preview").addEventListener("click", function () {
  document.getElementById("productImage").click();
});

document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("weeklyRevenueChart").getContext("2d");

  const data = {
    labels: [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ],
    datasets: [
      {
        label: "Daily Revenue",
        data: [337500, 425000, 385000, 562000, 298000, 445000, 378000],
        borderColor: "#36A2EB",
        backgroundColor: "rgba(54, 162, 235, 0.1)",
        tension: 0.4,
        fill: true,
        pointBackgroundColor: "#36A2EB",
        pointBorderColor: "#fff",
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
      },
    ],
  };

  const config = {
    type: "line",
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          padding: 12,
          titleFont: {
            size: 14,
            weight: "bold",
          },
          bodyFont: {
            size: 13,
          },
          callbacks: {
            label: function (context) {
              return "Revenue: Rs. " + context.parsed.y.toLocaleString();
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return "Rs. " + value.toLocaleString();
            },
          },
          grid: {
            color: "rgba(0, 0, 0, 0.05)",
          },
        },
        x: {
          grid: {
            display: false,
          },
        },
      },
    },
  };

  new Chart(ctx, config);
});

// fetch Products for the inventory table

async function fetchProducts() {
  try {
    const response = await fetch(`${URLROOT}/products/getAllProducts`);
    const products = await response.json();

    const tbody = document.querySelector("#productTable tbody");
    tbody.innerHTML = ""; // Clear existing rows

    products.forEach((product) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${product.product_id}</td>
                <td>${product.tea_type}</td>
                <td>${product.grade}</td>
                <td>Rs. ${parseFloat(product.price_per_kg).toFixed(2)}</td>
                <td>${product.weight} kg</td>
                <td>${product.last_updated}</td>
                <td>
                    <span class="status ${
                      product.is_available ? "completed" : "pending"
                    }">
                        ${product.is_available ? "In Stock" : "Out of Stock"}
                    </span>
                </td>
                <td class="actions">
                    <button class="btn-action view" onclick="viewProduct('${
                      product.product_id
                    }')">
                        <i class='bx bx-show'></i>
                    </button>
                    <button class="btn-action edit" onclick="editProduct('${
                      product.product_id
                    }')">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="btn-action export" onclick="exportProduct('${
                      product.product_id
                    }')">
                        <i class='bx bx-export'></i>
                    </button>
                </td>
            `;
      tbody.appendChild(row);
    });
  } catch (error) {
    console.error("Error fetching products:", error);
  }
}

// Call fetchProducts on page load
document.addEventListener("DOMContentLoaded", fetchProducts);

// Add this new function for product cards
async function fetchProductCards() {
  try {
    const response = await fetch(`${URLROOT}/products/getAllProducts`);
    const products = await response.json();

    const bagsGrid = document.querySelector(".bags-grid");
    if (!bagsGrid) return;
    bagsGrid.innerHTML = "";

    products.forEach((product) => {
      let statusClass = "completed";
      let statusText = "In Stock";

      if (product.current_stock <= product.low_stock_threshold) {
        statusClass = "pending";
        statusText = "Low Stock";
      }

      // Use the same path construction as in product details
      const imagePath = product.image_path
        ? `${URLROOT}/public/uploads/products/${product.image_path}`
        : `${URLROOT}/public/uploads/products/default_image_path2.png`;

      const card = document.createElement("div");
      card.className = "bag-card";
      card.onclick = () => showProductDetails(product.product_id);

      card.innerHTML = `
        <div class="bag-icon">
          <img src="${imagePath}" 
               alt="${product.name}" 
               class="product-image">
        </div>
        <div class="bag-info">
          <h4>${product.name}</h4>
          <p class="product-grade">Grade: ${product.grade}</p>
          <div class="stock-info">
            <span class="quantity">${product.weight} kg</span>
            <span class="status ${statusClass}">${statusText}</span>
          </div>
          <div class="price-info">
            <span>Rs. ${parseFloat(product.price_per_kg).toFixed(2)}</span>
          </div>
        </div>
      `;

      bagsGrid.appendChild(card);
    });
  } catch (error) {
    console.error("Error fetching product cards:", error);
  }
}

// Update the DOMContentLoaded event listener to call both functions
document.addEventListener("DOMContentLoaded", () => {
  fetchProducts(); // Existing table function
  fetchProductCards(); // New cards function

  // Optional: Refresh both views periodically
  setInterval(() => {
    fetchProducts();
    fetchProductCards();
  }, 30000); // Refresh every 30 seconds
});

// Function to show product details modal (you can implement this)
async function showProductDetails(productId) {
  try {
    currentProductId = productId; // Set the current product ID
    const response = await fetch(
      `${URLROOT}/products/getProductDetails/${productId}`
    );
    const product = await response.json();

    if (product) {
      document.getElementById("modalProductImage").src = product.image_path
        ? `${URLROOT}/public/uploads/products/${product.image_path}`
        : `${URLROOT}/public/uploads/products/default_image_path.png`;
      document.getElementById("modalProductName").innerText = product.name;
      document.getElementById(
        "modalProductGrade"
      ).innerText = `Grade: ${product.grade}`;
      document.getElementById(
        "modalProductPrice"
      ).innerText = `Rs. ${parseFloat(product.price_per_kg).toFixed(2)}/kg`;
      document.getElementById(
        "modalProductStock"
      ).innerText = `Available Stock: ${product.current_stock} kg`;
      document.getElementById("modalProductDescription").innerText =
        product.description;

      document.getElementById("productDetailsModal").style.display = "block";
    }
  } catch (error) {
    console.error("Error fetching product details:", error);
  }
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none"; // Hide the modal
}

////////////////////////////////////////////////////////////

let currentProductId = null;
let editedFields = new Set();
let editedValues = {}; // Store edited values

function toggleEdit(fieldId) {
  const displayElement = document.getElementById(fieldId);
  const editElement = document.getElementById(fieldId + "Edit");
  const saveButton = document.getElementById("saveChangesBtn");

  if (editElement.style.display === "none") {
    // Switching to edit mode
    displayElement.style.display = "none";
    editElement.style.display = "block";

    // Use previously edited value if it exists, otherwise use display value
    editElement.value =
      editedValues[fieldId] ||
      displayElement.innerText.replace("Rs. ", "").replace("/kg", "");
    saveButton.style.display = "block";
    editedFields.add(fieldId);
  } else {
    // Switching back to display mode
    displayElement.style.display = "block";
    editElement.style.display = "none";
    // Store the edited value
    editedValues[fieldId] = editElement.value;
  }
}

async function saveProductChanges() {
  const updateData = {};

  // Only include fields that were edited
  if (editedFields.has("modalProductName")) {
    updateData.name = document.getElementById("modalProductNameEdit").value;
  }
  if (editedFields.has("modalProductPrice")) {
    updateData.price_per_kg = document.getElementById(
      "modalProductPriceEdit"
    ).value;
  }
  if (editedFields.has("modalProductDescription")) {
    updateData.description = document.getElementById(
      "modalProductDescriptionEdit"
    ).value;
  }

  // If nothing was edited, return
  if (Object.keys(updateData).length === 0) {
    return;
  }

  try {
    const response = await fetch(
      `${URLROOT}/products/updateProduct/${currentProductId}`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(updateData),
      }
    );

    const data = await response.json();
    if (data.success) {
      // Update only the edited fields in the display
      if (updateData.name) {
        document.getElementById("modalProductName").innerText = updateData.name;
      }
      if (updateData.price_per_kg) {
        document.getElementById(
          "modalProductPrice"
        ).innerText = `Rs. ${parseFloat(updateData.price_per_kg).toFixed(
          2
        )}/kg`;
      }
      if (updateData.description) {
        document.getElementById("modalProductDescription").innerText =
          updateData.description;
      }

      // Hide all edit inputs and show all display elements
      editedFields.forEach((fieldId) => {
        document.getElementById(fieldId + "Edit").style.display = "none";
        document.getElementById(fieldId).style.display = "block";
      });

      // Clear the edited fields set and values after successful save
      editedFields.clear();
      editedValues = {};
      document.getElementById("saveChangesBtn").style.display = "none";

      // Refresh the products display
      fetchProductCards();
    }
  } catch (error) {
    console.error("Error updating product:", error);
  }
}

async function deleteProduct() {
  if (confirm("Are you sure you want to delete this product?")) {
    try {
      const response = await fetch(
        `${URLROOT}/products/deleteProduct/${currentProductId}`,
        {
          method: "POST",
        }
      );

      const data = await response.json();
      if (data.success) {
        closeModal("productDetailsModal");
        fetchProductCards();
      }
    } catch (error) {
      console.error("Error deleting product:", error);
    }
  }
}
