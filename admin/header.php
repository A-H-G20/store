<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Default Title'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar / Header Section -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="admin_dashboard.php">Lifestyle Store</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <!-- Home Link -->
      <li class="nav-item">
        <a class="nav-link" href="admin_dashboard.php">Home</a>
      </li>

      <!-- Admin/Management Links (Visible only for admins) -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link" href="manage_orders.php">Manage Orders</a>
        </li>
       
      <?php endif; ?>

      <!-- User Links (Visible for logged-in users) -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="manage_products.php">Manage Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_admin.php">Add Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Logout</a>
        </li>
      <?php else: ?>
        
      <?php endif; ?>
    </ul>
  </div>
</nav>
