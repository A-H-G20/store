<?php
session_start();



// Database connection
include '../config.php';

// Fetch totals for summary display
$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
    <?php 
    include 'header.php';
    ?>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the admin dashboard. Here you can manage products and orders.</p>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text"><?php echo $total_products; ?></p>
                        <a href="manage_products.php" class="btn btn-primary">Manage Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text"><?php echo $total_orders; ?></p>
                        <a href="manage_orders.php" class="btn btn-primary">Manage Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
