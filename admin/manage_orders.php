<?php
session_start();
include '../config.php';

// Fetch all orders
$orders = $conn->query("SELECT o.id, u.username, p.name AS product_name, o.quantity, o.status
                        FROM orders o
                        JOIN users u ON o.user_id = u.id
                        JOIN products p ON o.product_id = p.product_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<?php 
    include 'header.php';
    ?>
    <div class="container mt-5">
        <h1>Manage Orders</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a href="update_order_status.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Update Status</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
