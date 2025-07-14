<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="sticky-top">
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold fs-4" href="index.php">Legacy Sportwear</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'jersey.php') ? 'active' : ''; ?>" href="jersey.php">Jerseys</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'accessories.php') ? 'active' : ''; ?>" href="accessories.php">Accessories</a>
          </li>
        </ul>

        <form class="d-flex me-3" role="search" method="GET" action="search.php">
          <input class="form-control me-2" type="search" name="keyword" placeholder="Search products..." aria-label="Search" style="max-width: 400px;" required>
          <button class="btn btn-outline-primary" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>

        <?php if (isset($_SESSION['username'])): ?>
          <div class="dropdown me-3">
            <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="profil.php">Profil</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="login.php" class="btn btn-link text-dark me-3">
            <i class="bi bi-person"></i>
          </a>
        <?php endif; ?>

        <a href="cart.php" class="btn btn-link text-dark position-relative">
          <i class="bi bi-cart fs-5"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
            0
          </span>
        </a>
      </div>
    </div>
  </nav>
</header>
