document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("supplierRegForm");

  // Add phone number formatting
  document.querySelectorAll('input[type="tel"]').forEach((input) => {
    input.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.length > 10) {
        value = value.substr(0, 10);
      }
      e.target.value = value;
    });
  });

  // Enhanced validation for phone numbers
  function validatePhoneNumber(number) {
    const phoneRegex = /^(?:7|0)[0-9]{9}$/;
    return phoneRegex.test(number);
  }

  // File size validation
  function validateFileSize(input) {
    const file = input.files[0];
    const maxSize = input.dataset.maxSize || 5; // Default 5MB if not specified

    if (file && file.size > maxSize * 1024 * 1024) {
      alert(`File size must be less than ${maxSize}MB`);
      input.value = "";
      return false;
    }
    return true;
  }

  // Add file input listeners
  document.querySelectorAll('input[type="file"]').forEach((input) => {
    input.addEventListener("change", function () {
      validateFileSize(this);
    });
  });

  // Account number validation
  function validateAccountNumber(number) {
    const accountRegex = /^[0-9]{5,20}$/;
    return accountRegex.test(number);
  }

  // Error handling functions
  function showError(input, message) {
    let errorElement = input.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains("error-message")) {
      errorElement = document.createElement("small");
      errorElement.classList.add("error-message");
      input.parentNode.insertBefore(errorElement, input.nextSibling);
    }
    errorElement.textContent = message;
    errorElement.style.color = "red";
  }

  function clearError(input) {
    const errorElement = input.nextElementSibling;
    if (errorElement && errorElement.classList.contains("error-message")) {
      errorElement.remove();
    }
  }

  // Form submission handler
  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    let isValid = true;

    // Validate all required inputs
    this.querySelectorAll("input, select").forEach((input) => {
      if (input.type === "number") {
        const value = parseFloat(input.value);
        const min = parseFloat(input.min);
        const max = parseFloat(input.max);

        if (input.hasAttribute("required") && (isNaN(value) || value === "")) {
          isValid = false;
          showError(input, "This field is required");
        } else if (!isNaN(min) && value < min) {
          isValid = false;
          showError(input, `Minimum value is ${min}`);
        } else if (!isNaN(max) && value > max) {
          isValid = false;
          showError(input, `Maximum value is ${max}`);
        } else {
          clearError(input);
        }
      } else if (input.hasAttribute("required") && !input.value) {
        isValid = false;
        showError(input, "This field is required");
      } else if (
        input.type === "tel" &&
        input.value &&
        !validatePhoneNumber(input.value)
      ) {
        isValid = false;
        showError(input, "Invalid phone number format");
      } else if (
        input.id === "accountNumber" &&
        input.value &&
        !validateAccountNumber(input.value)
      ) {
        isValid = false;
        showError(input, "Invalid account number format");
      } else {
        clearError(input);
      }
    });

    if (!isValid) {
      return;
    }

    try {
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.textContent = "Submitting...";

      const response = await fetch(this.action, {
        method: "POST",
        body: new FormData(this),
      });

      const data = await response.json();

      if (data.success) {
        window.location.href = data.redirect || "/dashboard";
      } else {
        throw new Error(data.message || "Submission failed");
      }
    } catch (error) {
      alert("Error: " + error.message);
    } finally {
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.disabled = false;
      submitBtn.textContent = "Submit Application";
    }
  });

  // Number input handlers
  document.querySelectorAll('input[type="number"]').forEach((input) => {
    input.addEventListener("input", function () {
      const value = parseFloat(this.value);
      const min = parseFloat(this.min);
      const step = parseFloat(this.step);

      if (!isNaN(value)) {
        const steps = Math.round((value - min) / step);
        const newValue = min + steps * step;
        this.value = newValue.toFixed(2);
      }
    });
  });
});
