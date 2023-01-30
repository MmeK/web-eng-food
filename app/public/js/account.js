// Get the table element
const table = document.querySelector("#order-history-table tbody");
const accountEditForm = $("#account-edit-form");
// Fetch order history data from backend

fetch(`/php/user.php?uid=1`)
  .then((response) => response.json())
  .then((item) => {
    accountEditForm.find("#username").val(item.username);
    accountEditForm.find("#email").val(item.email);
    accountEditForm.find("#location").val(item.location);
  });

accountEditForm.submit((event) => {
  event.preventDefault();
  const formData = new FormData(document.getElementById("account-edit-form"));
  fetch("/php/user.php", {
    method: "POST",
    body: formData,
    headers: {
      "HTTP-X-REST-METHOD": "EDIT",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("User edited successfully!");
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error: " + error);
    });
});

fetch(`/php/order.php?uid=1`)
  .then((response) => response.json())
  .then((data) => {
    // Loop through the data and add rows to the table
    data.forEach((item) => {
      const row = document.createElement("tr");

      const timestampCell = document.createElement("td");
      timestampCell.textContent = item.timestamp;
      row.appendChild(timestampCell);

      const itemsCell = document.createElement("td");
      itemsCell.textContent = item.items.replaceAll(",", ", ");
      itemsCell.style = `
      max-width: 300px;
      `;
      row.appendChild(itemsCell);

      const total_priceCell = document.createElement("td");
      total_priceCell.textContent = item.total_price;
      row.appendChild(total_priceCell);

      const rateCell = document.createElement("td");
      rateCell.innerHTML = `
        <form class="container d-flex flex-row gap-4 ">
            <div class="form-group d-inline">
            <select class="form-control  " id="rating-${item.id}">
                <option value="1" ${
                  item.rating == 1 ? 'selected="selected"' : ""
                } >1</option>
                <option value="2" ${
                  item.rating == 2 ? 'selected="selected"' : ""
                } >2</option>
                <option value="3" ${
                  item.rating == 3 ? 'selected="selected"' : ""
                } >3</option>
                <option value="4" ${
                  item.rating == 4 ? 'selected="selected"' : ""
                } >4</option>
                <option value="5" ${
                  item.rating == 5 ? 'selected="selected"' : ""
                } >5</option>
            </select>
            </div>
            <button type="button" class="btn btn-primary d-inline" onclick="submitRating(${
              item.id
            })">Submit</button>
        </form>
        `;
      row.appendChild(rateCell);

      table.appendChild(row);
    });
  });

// Submit the rating for an order
async function submitRating(orderId) {
  const rating = document.getElementById(`rating-${orderId}`).value;
  const response = await fetch(`/php/order.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "HTTP-X-REST-METHOD": "EDIT",
    },
    body: JSON.stringify({
      rating: rating,
      order_id: orderId,
    }),
  });
  if (response.ok) {
    alert("Rating submitted successfully");
  } else {
    alert("Failed to submit rating");
  }
}
