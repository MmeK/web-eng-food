let cart = {
  items: [], // Items in the cart
  total: 0, // Total cost of items in the cart
  count: 0, // Number of items in the cart
};

if (localStorage.getItem("cart")) {
  cart = JSON.parse(localStorage.getItem("cart"));
} else {
  localStorage.setItem("cart", JSON.stringify(cart));
}

// Fetch menu items from the server
fetch("/php/food.php")
  .then((response) => response.json())
  .then((data) => {
    // Get the menu items container
    const menuItemsContainer = document.getElementById("menu-items");

    // Loop through the data and generate HTML for each menu item
    data.forEach((item) => {
      const menuItemHTML = `
            <div class="col-md-4 menu-item">
                <img src="${item.image}" alt="${item.name}" class="img-fluid">
                <h4>${item.name}</h4>
                <p>${item.description}</p>
                <div class="menu-item-footer d-flex align-items-center flex-row gap-2 mb-3">
                    <span class="d-block menu-item-quantity">${item.quantity}</span>
                    <span class="d-block menu-item-price">$${item.price}</span>
                    <button class="d-block add-to-cart-btn btn btn-primary" onclick="addToCart" data-item-price=${item.price} data-item-id="${item.id}">Add to Cart</button>
                </div>
            </div>
        `;
      menuItemsContainer.innerHTML += menuItemHTML;
    });
  })
  .catch((error) => console.error(error));

// Handle registration form submit
const registerForm = document.getElementById("register-form");
registerForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  // Get form data
  const formData = new FormData(registerForm);
  let isValid = true;
  if (formData.get("password-confirm") != formData.get("password")) {
    isValid = false;
    alert("passwords are not the same");
    return;
  }
  if (!isValid) {
    alert("All fields are required!");
    return;
  }
  // Send request to server
  const response = await fetch("/php/register.php", {
    method: "POST",
    body: formData,
  });

  // Parse response as JSON
  const data = await response.json();
  // Check for success
  if (response.status == 201) {
    // Show success message
    if (!alert(data.message)) {
      registerForm.reset();
      document.location.reload();
    }
  } else {
    // Show error message
    alert(data.error);
  }
});

const loginForm = document.getElementById("login-form");
loginForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  // Get form data
  const formData = new FormData(loginForm);
  // Send request to server

  const response = await fetch("/php/login.php", {
    method: "POST",
    body: formData,
  });

  // Parse response as JSON
  const data = await response.json();
  console.log(data);
  // Check for success
  if (response.status == 200) {
    // Show success message
    if (!alert(data.message)) {
      loginForm.reset();
      document.location.reload();
    }
  } else {
    // Show error message
    alert(data.error);
  }
});

function addToCart(event) {
  const { id, price } = event.target.dataset;
  console.log(id, price);
}

// Create a reference to the floating action button and badge
const fab = document.querySelector(".fixed-action-btn");
const badge = document.querySelector("#cart-item-count");

// Get the number of items in the cart from local storage
let itemsInCart = JSON.parse(localStorage.getItem("cart")) || [];
badge.textContent = itemsInCart.length;

// Show or hide the badge based on the number of items in the cart
if (itemsInCart.length > 0) {
  badge.style.display = "block";
} else {
  badge.style.display = "none";
}

// Listen for a click on the floating action button
fab.addEventListener("click", function () {
  // Redirect the user to the checkout page
  window.location.href = "checkout.php";
});

// Listen for updates to the cart and update the badge
window.addEventListener("storage", function (event) {
  if (event.key === "cart") {
    itemsInCart = JSON.parse(event.newValue);
    badge.textContent = itemsInCart.length;

    if (itemsInCart.length > 0) {
      badge.style.display = "block";
    } else {
      badge.style.display = "none";
    }
  }
});
