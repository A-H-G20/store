<?php
session_start();
include 'config.php'; // Include your database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_items'])) {
    // Iterate over cart items and update the quantity
    foreach ($_POST['cart_items'] as $cart_item_id => $quantity) {
        // Ensure the quantity is a positive integer
        $quantity = intval($quantity);
        if ($quantity > 0) {
            // Update the quantity in the cart table
            $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("iii", $quantity, $user_id, $cart_item_id);
            $stmt->execute();
        }
    }

    // Redirect to the cart page after updating the cart
    header("Location: cart.php");
    exit();
}

// Redirect to cart page if no cart items are provided
header("Location: cart.php");
exit();
?>
