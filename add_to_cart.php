<?php
session_start();
include 'config.php'; // Include your database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the product_id from the query parameter
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Check if the product already exists in the cart
    $check_cart_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($check_cart_query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product already in cart, update the quantity
        $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $user_id, $product_id);
        $update_stmt->execute();
    } else {
        // Product not in cart, insert it with quantity 1
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
    }

    // Redirect back to the product page or cart page
    header("Location: cart.php");
    exit();
} else {
    // If no product_id is provided, redirect to the product page or error page
    header("Location: index.php");
    exit();
}

?>
