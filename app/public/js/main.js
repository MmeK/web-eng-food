const urlParams = new URLSearchParams(window.location.search);
const code = urlParams.get("code");
if (code) {
  message = "";
  switch (code) {
    case "401":
      message = "Please Log in before checkout.";
  }
  if (!alert(message)) {
    window.location.replace("/");
  }
}
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
      item.quantity -= cart.items.filter(
        (itemInCart) => item.id === itemInCart.id
      ).length;

      const updateUI = function (count) {
        quantityElement.textContent = count;
        if (count <= 0) {
          addToCartBtn.toggleAttribute("disabled");
        }
      };

      // Create elements separately and append it to parent
      const itemElement = document.createElement("div");
      itemElement.classList.add(
        "card",
        "col-lg-5",
        "menu-item",
        "px-2",
        "text-right",
        "mb-3"
      );

      // Create the label elements for image, heading, description, ingredients, ratings and itemElement

      const descriptionLabelElement = document.createElement("label");
      descriptionLabelElement.textContent = "Description:";

      const ingredientsLabelElement = document.createElement("label");
      ingredientsLabelElement.textContent = "Ingredients:";

      const ratingsLabelElement = document.createElement("label");
      ratingsLabelElement.textContent = "Rating:";

      // Create the item elements
      const imageElement = document.createElement("img");
      imageElement.classList.add("img-fluid");
      imageElement.src = item.image;
      imageElement.alt = item.name;

      const headingElement = document.createElement("h4");
      headingElement.textContent = item.name;

      const descriptionElement = document.createElement("p");
      descriptionElement.textContent = item.description;

      const ingredientsElement = document.createElement("p");
      ingredientsElement.textContent = item.ingredients;

      // Create ratingsElement and set it's textContent
      const ratingsElement = document.createElement("p");
      ratingsElement.textContent = item.rating ? item.rating : "No Rating";

      const menuItemFooter = document.createElement("div");
      menuItemFooter.classList.add(
        "d-flex",
        "menu-item-footer",
        "align-items-center",
        "flex-row",
        "gap-2",
        "mb-3"
      );

      const quantityLabelElement = document.createElement("label");
      quantityLabelElement.textContent = "Count:";

      const quantityElement = document.createElement("p");
      quantityElement.classList.add("d-block", "menu-item-quantity");
      quantityElement.textContent = item.quantity;

      const priceLabelElement = document.createElement("label");
      priceLabelElement.textContent = "Price:";

      const priceElement = document.createElement("p");
      priceElement.classList.add("d-block", "menu-item-price");
      priceElement.textContent = "$" + item.price;

      const addToCartBtn = document.createElement("button");
      addToCartBtn.classList.add(
        "d-block",
        "add-to-cart-btn",
        "btn",
        "btn-primary",
        "mb-2"
      );

      // Append all elements to the parent itemElement
      itemElement.append(
        imageElement,
        headingElement,
        descriptionLabelElement,
        descriptionElement,
        ingredientsLabelElement,
        ingredientsElement,
        ratingsLabelElement,
        ratingsElement,
        menuItemFooter,
        quantityLabelElement,
        quantityElement,
        priceLabelElement,
        priceElement,
        addToCartBtn
      );

      addToCartBtn.addEventListener("click", () => addToCart(item, updateUI));
      addToCartBtn.textContent = "Add to Cart";

      // append elements
      updateUI(item.quantity);

      menuItemsContainer.appendChild(itemElement);
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

function addToCart(item, callbackUI) {
  // Add item to cart
  if (item.quantity > 0) {
    cart.items.push(item);
    // Update total and count
    cart.total += parseInt(item.price);
    cart.count++;
    item.quantity--;
    //TODO: UPDATE UI

    localStorage.setItem("cart", JSON.stringify(cart));
    window.dispatchEvent(new Event("storage"));
  }
  callbackUI(item.quantity);
}

// Create a reference to the floating action button and badge
const fab = document.querySelector(".fixed-action-btn");
const badge = document.querySelector("#cart-item-count");

// Get the number of items in the cart from local storage
let itemsInCart = JSON.parse(localStorage.getItem("cart")).items || [];
badge.textContent = itemsInCart.length;

if (itemsInCart.length > 0) {
  badge.classList.remove("d-none");
  badge.classList.add("d-block");
} else {
  badge.classList.remove("d-block");
  badge.classList.add("d-none");
}

// Listen for a click on the floating action button
fab.addEventListener("click", function () {
  // Redirect the user to the checkout page
  window.location.href = "/checkout.php";
});

// Listen for updates to the cart and update the badge
window.addEventListener("storage", function (event) {
  itemsInCart = JSON.parse(localStorage.getItem("cart"));
  console.log(itemsInCart);

  badge.textContent = itemsInCart.items.length;
  console.log(badge.textContent);

  if (itemsInCart.items.length > 0) {
    badge.classList.remove("d-none");
    badge.classList.add("d-block");
  } else {
    badge.classList.remove("d-block");
    badge.classList.add("d-none");
  }
});
