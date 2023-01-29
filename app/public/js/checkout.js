// Get reference to the form element
const form = document.querySelector("#checkout-form");
const cancelButton = document.querySelector("#cancel-btn");
const items = JSON.parse(localStorage.getItem("cart")) || [];

const checkout_table = document.querySelector("#checkout-items");

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
  const total = formData.get("total");

  // Send the order to the server
  fetch("/api/create_order", {
    method: "POST",
    body: JSON.stringify({
      items,
      total,
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
      window.location.href = "/order_confirmation.html";
    })
    .catch((error) => {
      // Show error message
      alert("Failed to place order: " + error.message);
    });
});

function displayCartItems() {
  let total = 0;
  items.forEach((item) => {
    const row = document.createElement("tr");
    const nameCell = document.createElement("td");
    nameCell.textContent = item.name;
    row.appendChild(nameCell);

    const priceCell = document.createElement("td");
    priceCell.textContent = item.price;
    row.appendChild(priceCell);
    total += parseFloat(item.price);

    const quantityCell = document.createElement("td");
    quantityCell.textContent = item.quantity;
    row.appendChild(quantityCell);

    checkout_table.appendChild(row);
  });
}
