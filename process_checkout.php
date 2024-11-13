<?php
session_start();
include 'config.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $full_name = htmlspecialchars($_POST['full_name']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $postal_code = htmlspecialchars($_POST['postal_code']);
    $country = htmlspecialchars($_POST['country']);
    $total_price = 0;

    // Fetch cart items to calculate total and store order details
    $cart_query = "SELECT * FROM cart INNER JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = ?";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result();

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert new order
        $order_query = "INSERT INTO orders (user_id, full_name, address, city, postal_code, country, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $order_stmt = $conn->prepare($order_query);
        $order_stmt->bind_param("isssss", $user_id, $full_name, $address, $city, $postal_code, $country);
        $order_stmt->execute();
        $order_id = $conn->insert_id; // Get the last inserted order ID

        // Insert each cart item into order_items table
        while ($item = $cart_items->fetch_assoc()) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total_price += $price * $quantity;

            // Insert into order_items
            $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $order_item_stmt = $conn->prepare($order_item_query);
            $order_item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $order_item_stmt->execute();

            // Update product stock
            $update_stock_query = "UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?";
            $update_stock_stmt = $conn->prepare($update_stock_query);
            $update_stock_stmt->bind_param("iii", $quantity, $product_id, $quantity);
            $update_stock_stmt->execute();

            // Check if stock update was successful
            if ($update_stock_stmt->affected_rows == 0) {
                throw new Exception("Insufficient stock for product ID: $product_id");
            }
        }

        // Update the total price in orders table
        $update_total_query = "UPDATE orders SET total_price = ? WHERE order_id = ?";
        $update_total_stmt = $conn->prepare($update_total_query);
        $update_total_stmt->bind_param("di", $total_price, $order_id);
        $update_total_stmt->execute();

        // Clear user's cart after successful order
        $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
        $clear_cart_stmt = $conn->prepare($clear_cart_query);
        $clear_cart_stmt->bind_param("i", $user_id);
        $clear_cart_stmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect to confirmation page or display success message
        $_SESSION['order_success'] = "Order placed successfully!";
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['order_error'] = "Error placing order: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
} else {
    // If form wasn't submitted, redirect to checkout page
    header("Location: checkout.php");
    exit();
}
?>
