<?php
session_start();
include 'config.php'; // Include your database connection file

// Fetch products from the database
$products_query = "SELECT * FROM products";
$products_result = $conn->query($products_query);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

$user_id = $_SESSION['user_id'];

// Fetch the cart items for the logged-in user
$cart_query = "SELECT c.id, p.name, p.price, c.quantity FROM cart c
               JOIN products p ON c.product_id = p.product_id
               WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<style>
    /* Custom dark background */
.custom-bg-dark {
    background-color: #111; /* Darker black background */
}

/* Add spacing between each navbar item */
.navbar a {
    margin-left: 15px; /* Adjusts the space between links */
    margin-right: 15px;
}

</style>
<nav class="navbar navbar-expand-lg navbar-dark custom-bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Lifestyle Store</a>
        <div class="ml-auto">
            <a href="dashboard.php" class="text-white mx-3">Home</a>
            <?php if ($is_logged_in): ?>
                <a href="cart.php" class="text-white mx-3">Cart</a>
                <a href="logout.php" class="text-white mx-3">Logout</a>
            <?php else: ?>
                <a href="signup.php" class="text-white mx-3">Sign Up</a>
                <a href="login.php" class="text-white mx-3">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<div class="container mt-5">
    <h1>Your Cart</h1>

    <?php if ($result->num_rows > 0): ?>
        <form action="update_cart.php" method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    while ($row = $result->fetch_assoc()):
                        $product_total = $row['price'] * $row['quantity'];
                        $total_price += $product_total;
                    ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <input type="number" name="cart_items[<?php echo $row['id']; ?>]" 
                                       value="<?php echo $row['quantity']; ?>" min="1" class="form-control" 
                                       style="width: 80px;">
                            </td>
                            <td>$<?php echo number_format($product_total, 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Update Cart</button>
        </form>
        <hr>
        <h3>Total Price: $<?php echo number_format($total_price, 2); ?></h3>
        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
