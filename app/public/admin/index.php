<?php
session_start();
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'admin')) {
    header('Location: /index.php');
    return;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
  </head>
  <body>
    <nav class="navbar p-3 navbar-expand-sm navbar-dark bg-primary">
      <a class="navbar-brand" href="#">Admin Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="/php/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="mx-2 my-4 container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="list-group">
            <a href="#" tab="0" class="list-group-item list-group-item-action active">
              Menu
            </a>
            <a href="#" tab="1" class="list-group-item list-group-item-action">Orders</a>
            <a href="#" tab="2" class="list-group-item list-group-item-action">Users</a>
          </div>
        </div>
        <div class="col-md-9">
          <div class="mx-2 card">
            <div class="card-body">
                <div id="menu-items" class="tab-pane">
                    <h3 class="text-center">Menu Items</h3>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Ingredients</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="menu-items-list">
                            <!-- menu items will be fetched and appended here -->
                        </tbody>
                        </table>
                    </div>
                    <button id="add-menu-item" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-primary">Add Item</button>
                    </div>
                </div>
                <!-- Add Menu Item Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Menu Item</h5>
                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <form id="addItemForm" enctype="multipart/form-data">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ingredients">Ingredients</label>
                                <textarea class="form-control" id="ingredients" name="ingredients" required></textarea>
                            </div>
                            <div class="form-image mb-3">
                                <label  class="image-label" for="image">Item Image</label>
                                <input type="file" class="form-control-file" id="image" name="image" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="addItemBtn">Add Item</button>
                        </div>
                        </form>

                    </div>
                </div>
                </div>

                <!-- Edit Menu Item Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Menu Item</h5>
                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <form id="editItemForm" enctype="multipart/form-data">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ingredients">Ingredients</label>
                                <textarea class="form-control" id="ingredients" name="ingredients" required></textarea>
                            </div>
                            <div class="form-image mb-3">
                                <label  class="image-label" for="image">Item Image</label>
                                <input type="file" class="form-control-file" id="image" name="image" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="editItemBtn">Edit Item</button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-9 d-none">
          <div class="mx-2 card">
            <div class="card-body">
                Orders
            </div>
          </div>
        </div>
        <div class="col-md-9 d-none">
          <div class="mx-2 card">
            <div class="card-body">
                Users
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" crossorigin="anonymous"></script>
    <script src="../js/admin.js"></script>
</body>
</html>







<!--

<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card-group">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Menu Items</h4>
          </div>
          <div class="card-body">
            <!-- Tab content for menu items goes here
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Users</h4>
          </div>
          <div class="card-body">
            <!-- Tab content for users goes here
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Orders</h4>
          </div>
          <div class="card-body">
            <!-- Tab content for orders goes here
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
