// Get the table element
const table = document.querySelector("#order-history-table tbody");

// Fetch order history data from backend
fetch("/api/order-history")
  .then((response) => response.json())
  .then((data) => {
    // Loop through the data and add rows to the table
    data.forEach((order) => {
      const row = document.createElement("tr");

      const dateCell = document.createElement("td");
      dateCell.textContent = order.date;
      row.appendChild(dateCell);

      const itemsCell = document.createElement("td");
      itemsCell.textContent = order.items.join(", ");
      row.appendChild(itemsCell);

      const totalCell = document.createElement("td");
      totalCell.textContent = order.total;
      row.appendChild(totalCell);

      const rateCell = document.createElement("td");
      rateCell.innerHTML = `
        <form>
            <div class="form-group">
            <label for="rating">Rating:</label>
            <select class="form-control" id="rating-${order.id}">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            </div>
            <button type="button" class="btn btn-primary" onclick="submitRating(${order.id})">Submit</button>
        </form>
        `;
      row.appendChild(rateCell);

      table.appendChild(row);
    });
  });

// Submit the rating for an order
async function submitRating(orderId) {
  const rating = document.getElementById(`rating-${orderId}`).value;
  const response = await fetch(`/api/orders/${orderId}/rating`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      rating: rating,
    }),
  });
  if (response.ok) {
    alert("Rating submitted successfully");
  } else {
    alert("Failed to submit rating");
  }
}
