document.addEventListener("DOMContentLoaded", function () {
  const toggles = document.querySelectorAll(".password-toggle");

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const passwordInput = this.parentElement;
      const input = passwordInput.querySelector("input");

      if (input.type === "password") {
        input.type = "text";
        this.classList.remove("bx-hide");
        this.classList.add("bx-show");
      } else {
        input.type = "password";
        this.classList.remove("bx-show");
        this.classList.add("bx-hide");
      }
    });
  });
});
