$(document).ready(function () {
  $(".list-group-item-action").click(function (event) {
    $(this).addClass("active");
    $(this).siblings().removeClass("active");
    const tab = $(this).attr("tab");
    $(".col-md-9").addClass("d-none");
    $(".col-md-9").eq(tab).toggleClass("d-none");
  });
});

const menuList = document.querySelector("#menu-items-list");
const ordersList = document.querySelector("#order-items-list");
const usersList = document.querySelector("#user-items-list");

reloadMenuItems();
reloadOrders();
reloadUsers();

function reloadUsers() {
  usersList.textContent = "";
  fetch("/php/user.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((item) => {
        const row = document.createElement("tr");

        const idCell = document.createElement("td");
        idCell.textContent = item.id;
        row.appendChild(idCell);

        const usernameCell = document.createElement("td");
        usernameCell.textContent = item.username;
        row.appendChild(usernameCell);

        const emailCell = document.createElement("td");
        emailCell.textContent = item.email;
        row.appendChild(emailCell);

        const locationCell = document.createElement("td");
        locationCell.textContent = item.location;
        row.appendChild(locationCell);

        // <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckedDisabled" checked disabled>
        const is_adminCell = document.createElement("td");
        const adminCheck = document.createElement("input");
        adminCheck.setAttribute("type", "checkbox");
        item.is_admin && adminCheck.toggleAttribute("checked");
        adminCheck.setAttribute("disabled", true);
        adminCheck.className += ". form-check-input";
        is_adminCell.appendChild(adminCheck);
        row.appendChild(is_adminCell);

        usersList.appendChild(row);
      });
    });
}

function reloadOrders() {
  ordersList.textContent = "";
  fetch("/php/order.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((item) => {
        const row = document.createElement("tr");

        const idCell = document.createElement("td");
        idCell.textContent = item.id;
        row.appendChild(idCell);

        const usernameCell = document.createElement("td");
        usernameCell.textContent = item.username;
        row.appendChild(usernameCell);

        const total_priceCell = document.createElement("td");
        total_priceCell.textContent = item.total_price;
        row.appendChild(total_priceCell);

        const timestampCell = document.createElement("td");
        timestampCell.textContent = item.timestamp;
        row.appendChild(timestampCell);

        const ratingCell = document.createElement("td");
        ratingCell.textContent = item.rating;
        row.appendChild(ratingCell);

        ordersList.appendChild(row);
      });
    });
}

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
          const editItemForm = $("#editItemForm");
          editItemForm.find("#name").val(item.name);
          editItemForm.find("#price").val(item.price);
          editItemForm.find("#quantity").val(item.quantity);
          editItemForm.find("#ingredients").val(item.ingredients);
          editItemForm.find("#description").val(item.description);
          editItemForm.find("#image-preview").attr({ src: item.image });
          editItemForm.find("#editItemBtn").click(function (event) {
            event.preventDefault();
            // Validate form data
            const formData = new FormData(
              document.getElementById("editItemForm")
            );
            formData.append("id", item.id);
            fetch("/php/food.php", {
              method: "POST",
              body: formData,
              headers: {
                "HTTP-X-REST-METHOD": "EDIT",
              },
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.success) {
                  alert("Item edited successfully!");
                  // Close the modal
                  document.getElementById("editItemForm").reset();
                  $("#editModal").modal("hide");
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
        });

        menuList.appendChild(row);
      });
    });
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
