$(document).ready(function () {
  $(".list-group-item-action").click(function (event) {
    $(this).addClass("active");
    $(this).siblings().removeClass("active");
    const tab = $(this).attr("tab");
    $(".col-md-9").addClass("d-none");
    $(".col-md-9").eq(tab).toggleClass("d-none");
  });
});

let menuList = document.querySelector("#menu-items-list");

reloadMenuItems();

function reloadMenuItems() {
  menuList.textContent = "";
  fetch("/php/food.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((item) => {
        const row = document.createElement("tr");

        const idCell = document.createElement("td");
        idCell.textContent = item.id;
        row.appendChild(idCell);

        const nameCell = document.createElement("td");
        nameCell.textContent = item.name;
        row.appendChild(nameCell);

        const descriptionCell = document.createElement("td");
        descriptionCell.textContent = item.description;
        row.appendChild(descriptionCell);

        const priceCell = document.createElement("td");
        priceCell.textContent = item.price;
        row.appendChild(priceCell);

        const quantityCell = document.createElement("td");
        quantityCell.textContent = item.quantity;
        row.appendChild(quantityCell);

        const ingredientsCell = document.createElement("td");
        ingredientsCell.textContent = item.ingredients;
        row.appendChild(ingredientsCell);

        const actionsCell = document.createElement("td");
        actionsCell.innerHTML = `
        <div class="d-flex flex-row" style="gap: 4px;">
          <button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
          <button class="btn btn-danger delete-btn">Delete</button>
        </div>
      `;
        row.appendChild(actionsCell);
        let deleteBtn = actionsCell.querySelector(".delete-btn");
        let editBtn = actionsCell.querySelector(".edit-btn");

        deleteBtn.addEventListener("click", () => {
          if (confirm("Are you sure you want to delete this item?")) {
            fetch(`/php/food.php`, {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "HTTP-X-REST-METHOD": "DELETE",
              },
              body: JSON.stringify({
                id: item.id,
              }),
            }).then((response) => {
              if (response.ok) {
                row.remove();
              }
            });
          }
        });
        editBtn.addEventListener("click", () => {
          // show edit modal
        });

        menuList.appendChild(row);
      });
    });
}

// Edit item
function editItem(element) {
  // Get the item ID from the button data attribute
  var itemId = $(element).data("id");

  // Show the edit modal
  // ...
}

$("#addItemForm").submit(function (event) {
  event.preventDefault();
  const formData = new FormData(this);

  // Validate form data
  let isValid = true;
  $(this)
    .find("input")
    .each(function () {
      if (!$(this).val()) {
        isValid = false;
        return false;
      }
    });

  if (!formData.get("image").type.startsWith("image")) {
    isValid = false;
    alert("Item image is required!");
    return;
  }

  if (!isValid) {
    alert("All fields are required!");
    return;
  }

  // Send data to backend
  fetch("/php/food.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Item added successfully!");
        // Close the modal
        document.getElementById("addItemForm").reset();
        $("#addModal").modal("hide");
        // Reload menu items
        reloadMenuItems();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error: " + error);
    });
});
