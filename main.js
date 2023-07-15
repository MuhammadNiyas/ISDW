// main.js

// Function to show/hide password
function showHidePassword() {
  var passwordInput = document.getElementById("password");
  var passwordToggle = document.getElementById("password-toggle");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    passwordToggle.innerText = "Hide";
  } else {
    passwordInput.type = "password";
    passwordToggle.innerText = "Show";
  }
}

