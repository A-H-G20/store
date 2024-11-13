<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$cart_query = "SELECT * FROM cart INNER JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

// Calculate total price
$total = 0;
while ($item = $cart_items->fetch_assoc()) {
    $total += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Custom styles if any -->
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Checkout</h2>

    <!-- Order Summary -->
    <div class="order-summary my-4">
        <h4>Order Summary</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cart_items->data_seek(0); // Reset result pointer to re-use the result set
                while ($item = $cart_items->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <th>$<?php echo number_format($total, 2); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Shipping Information Form -->
    <div class="shipping-info">
        <h4>Shipping Information</h4>
        <form action="process_checkout.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" required>
            </div>
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
