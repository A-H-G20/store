<?php
session_start();
include '../config.php';

// Check if an order ID and status are passed
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Fetch the current order details to display
    $query = "SELECT * FROM orders WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
    }
}

// Handle the form submission to update the status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];

    // Update the order status in the database
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    if ($stmt = $conn->prepare($update_query)) {
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect back to the orders page
            header("Location: manage_orders.php");
            exit();
        } else {
            echo "Error updating status.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<?php 
    include 'header.php';
    ?>
    <div class="container mt-5">
        <h1>Update Order Status</h1>
        <?php if (isset($order)): ?>
            <form action="update_order_status.php?id=<?php echo $order['id']; ?>" method="POST">
                <div class="form-group">
                    <label for="order_id">Order ID</label>
                    <input type="text" class="form-control" id="order_id" name="order_id" value="<?php echo $order['id']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="status">Current Status: <?php echo $order['status']; ?></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        <?php else: ?>
            <p>No order found to update.</p>
        <?php endif; ?>
    </div>
</body>
</html>
