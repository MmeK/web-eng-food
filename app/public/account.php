<?php
session_start();
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'user')) {
    header('Location: index.php');
}
if ((isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'admin')) {
    header('Location: /admin/index.php');
    return;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>User Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
  </head>
  <body>
    <nav class="navbar p-3 navbar-expand-sm navbar-dark bg-primary">
      <a class="navbar-brand" href="#">User Account</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="/index.php">Home <span class="visually-hidden-focusable">(current)</span></a>
        </li>

          <li class="nav-item">
            <a class="nav-link" href="/php/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container">
  <h1 class="my-3" >My Account</h1>
  <form id="account-edit-form">
    <div class="mb-3">
      <label for="username">Username</label>
      <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
    </div>
    <div class="mb-3">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
    </div>
    <div class="mb-3">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
    </div>
    <div class="mb-3">
      <label for="location">Location</label>
      <input type="text" class="form-control" id="location" name="location" placeholder="Enter location">
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
  </form>
  <hr>
  <h3>Order History</h3>
  <table class="table" id="order-history-table">
  <thead>
    <tr>
      <th>Order Date</th>
      <th>Items</th>
      <th>Total</th>
      <th>Rating</th>
    </tr>
  </thead>
  <tbody>
    <!-- Rows will be added dynamically in JS -->
  </tbody>
</table>

</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" crossorigin="anonymous"></script>

    <script src="js/account.js"></script>
</body>
</html>
