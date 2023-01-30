<?php
session_start();
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'user')) {
    header('Location: index.php?code=401');
}
if ((isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'admin')) {
    header('Location: /admin/index.php');
    return;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Restaurant Project</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script
      src="https://kit.fontawesome.com/4991f65360.js"
      crossorigin="anonymous"
    ></script>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="/css/checkout.css" />
  </head>

  <body>
    <nav class="navbar px-4 navbar-expand-lg">
      <a class="navbar-brand" href="#">Restaurant Project</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="/index.php"
              >Home <span class="visually-hidden-focusable">(current)</span></a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/index.php#menu">Menu</a>
          </li>
          <?php if (!isset($_SESSION['logged_in'])) {
    header('Location: /index.php');

} elseif ($_SESSION['logged_in'] == 'admin') {
    header('Location: /admin/index.php');
} elseif ($_SESSION['logged_in'] == 'user') {?>
          <li class="nav-item">
            <a class="nav-link" href="/php/logout.php">Logout</a>
          </li>
          <?php }?>
        </ul>
      </div>
    </nav>
    <div class="container mt-5">
      <h2 class="text-center">Checkout</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody id="checkout-items"></tbody>
      </table>
      <h4 class="text-center" id="total-price"></h4>
      <hr />

      <form id="checkout-form">
  <div class="container d-flex justify-content-between form-group">
    <button type="submit" class="btn btn-primary">Place Order</button>
    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel Order</button>

  </div>
</form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="js/checkout.js"></script>
  </body>
</html>
