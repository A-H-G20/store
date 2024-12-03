<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect shipping information
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    
    // Fetch cart items for the user
    $cart_query = "SELECT * FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result();

    if ($cart_items->num_rows > 0) {
        $conn->begin_transaction(); // Start transaction
        
        try {
            // Loop through each cart item and save to orders table
            while ($item = $cart_items->fetch_assoc()) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $status = 'Pending'; // Default order status
                $order_date = date('Y-m-d H:i:s'); // Current timestamp

                $order_query = "INSERT INTO orders (user_id, product_id, quantity, status, order_date, full_name, address, city, postal_code, country)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_order = $conn->prepare($order_query);
                $stmt_order->bind_param(
                    "iiisssssss",
                    $user_id,
                    $product_id,
                    $quantity,
                    $status,
                    $order_date,
                    $full_name,
                    $address,
                    $city,
                    $postal_code,
                    $country
                );
                $stmt_order->execute();
            }

            // Clear the user's cart
            $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
            $stmt_clear_cart = $conn->prepare($clear_cart_query);
            $stmt_clear_cart->bind_param("i", $user_id);
            $stmt_clear_cart->execute();

            $conn->commit(); // Commit transaction
            header("Location: cart.php"); // Redirect to a success page
            exit();
        } catch (Exception $e) {
            $conn->rollback(); // Rollback transaction in case of error
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Your cart is empty.";
    }
} else {
    header("Location: checkout.php"); // Redirect back to checkout if the form is not submitted
    exit();
}
?>
