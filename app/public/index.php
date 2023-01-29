<?php
session_start();
if ((isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'admin')) {
    header('Location: /admin/index.php');
    return;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Restaurant Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/icons/css/line-awesome-font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <nav class="navbar px-4 navbar-expand-lg ">
        <a class="navbar-brand" href="#">Restaurant Project</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#menu">Menu</a>
                </li>
                <?php if (!isset($_SESSION['logged_in'])) {?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal" href="#">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="modal" data-bs-target="#registerModal" href="#">Register</a>
                </li>
                <?php } elseif ($_SESSION['logged_in'] == 'admin') {?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/php/logout.php">Logout</a>
                </li>
                <?php } elseif ($_SESSION['logged_in'] == 'user') {?>
                <li class="nav-item">
                    <a class="nav-link" href="/checkout.php">Checkout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/php/logout.php">Logout</a>
                </li>
                <?php }?>
            </ul>
        </div>
    </nav>
    <!-- Carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
        <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
        <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="images/image-1.jpg" alt="First slide">
            <div class="carousel-caption d-md-block">
            <h5>Delicious Pizza</h5>
            <p>Our pizza is made with the freshest ingredients and cooked to perfection.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="images/image-2.jpg" alt="Second slide">
            <div class="carousel-caption d-md-block">
            <h5>Juicy Burgers</h5>
            <p>Our burgers are made with 100% beef and grilled to perfection.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="images/image-3.jpg" alt="Third slide">
            <div class="carousel-caption d-md-block">
            <h5>Fresh Salad</h5>
            <p>Our salads are made with the freshest greens and topped with your favorite ingredients.</p>
            </div>
        </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only"></span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only"></span>
        </a>
    </div>

<div class="modal" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login/Register</h4>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <form id="login-form">

      <div class="modal-body">
          <div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email">
          </div>
          <div class="mb-3">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" name="password" id="password">
          </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Login</button>

        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
      </form>

    </div>
  </div>
</div>


<div class="modal" id="registerModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Register</h4>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form id="register-form">
          <div class="mb-3">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="password">Confirm Password:</label>
            <input type="password" class="form-control" placeholder="Re-enter your password" name="password-confirm" id="password-confirm" required>
          </div>
          <div class="mb-3">
            <label for="location">Location:</label>
            <input type="text" class="form-control" placeholder="Enter your location" name="location" id="location" required>
          </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="registerBtn">Register</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>

    </div>
  </div>
</div>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large red">
    <i class="las la-2x la-shopping-cart"></i>
  </a>
  <span class="d-none position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-item-count">0</span>
</div>



    <div id="menu" class="my-4 container">
        <div class="row">
            <h2 class="text-center">Our Menu</h2>
            <div id="menu-items" class="row">
                <!-- menu items will be dynamically loaded here -->
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</body>
</html>