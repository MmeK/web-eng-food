// Get reference to the form element
const form = document.querySelector("#checkout-form");
const cancelButton = document.querySelector("#cancel-btn");
const cart = JSON.parse(localStorage.getItem("cart")) || [];
console.log(cart);

const checkout_table = document.querySelector("#checkout-items");
const total_price = document.querySelector("#total-price");
cancelButton.addEventListener("click", (event) => {
  localStorage.removeItem("cart");
  window.location = "index.php";
});

displayCartItems();

// Handle form submit event
form.addEventListener("submit", (event) => {
  event.preventDefault();

  // Get the form data
  const formData = new FormData(form);
  const total_price = cart.total;
  const food_items = cart.items;

  // Send the order to the server
  fetch("/php/order.php", {
    method: "POST",
    body: JSON.stringify({
      total_price,
      food_items,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to create order");
      }

      return response.json();
    })
    .then((data) => {
      // Show success message and redirect to order confirmation page
      alert("Order placed successfully!");
      localStorage.removeItem("cart");
      window.location.href = "/";
    })
    .catch((error) => {
      // Show error message
      alert("Failed to place order: " + error.message);
    });
});

function displayCartItems() {
  items = cart.items;
  newItems = [];
  items.forEach((item) => {
    if (newItems.findIndex((x) => x.id == item.id) == -1) {
      newItems.push({
        id: item.id,
        name: item.name,
        quantity: 1,
        price: item.price,
        total: parseInt(item.price),
      });
      console.log(item.id);
    } else {
      const index = newItems.findIndex((x) => x.id == item.id);
      newItems[index].quantity++;
      newItems[index].total += parseInt(item.price);
    }
  });
  newItems.forEach((item) => {
    const row = document.createElement("tr");

    const nameCell = document.createElement("td");
    nameCell.textContent = item.name;
    row.appendChild(nameCell);

    const priceCell = document.createElement("td");
    priceCell.textContent = "$" + item.price;
    row.appendChild(priceCell);

    const quantityCell = document.createElement("td");
    quantityCell.textContent = item.quantity;
    row.appendChild(quantityCell);

    const totalCell = document.createElement("td");
    totalCell.textContent = "$" + item.total;
    row.appendChild(totalCell);

    checkout_table.appendChild(row);
  });
  total_price.textContent =
    "Total Price of your order: $" +
    newItems.reduce((total, item) => total + item.total, 0);
}
