<div class="main-container">
  <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
    <div class="container-fluid">
      <!-- <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php">RK Hotel</a> -->
      <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="align-items:center;">
          <li class="nav-item">
            <a class="nav-link active me-2" aria-current="page" href="index.php#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="index.php#about">About</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link me-2" href="index.php#reservation">Reservation</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link me-2" href="index.php#gallery">Gallery</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="index.php#contact">Contact Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="index.php#hotels">Hotels</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="index.php#reviews">Reviews</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="bookings.php">My Bookings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="preference.php">Prefences</a>
          </li>
          <?php
          if (isset($_SESSION['name'])) {
            // If session is set, display Logout button
            echo '<li class="nav-item">
                      <a class="nav-link me-2 btn btn-primary" href="components/logout.php">Logout</a>
                    </li>';
          } else {
            // If session is not set, display Login / Register button
            echo '<li class="nav-item">
                      <a class="nav-link me-2 btn btn-primary" href="login_register.php">Login / Register</a>
                    </li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>