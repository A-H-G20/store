<?php
session_start();
include '../config.php';

// Check if product ID is set in URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Delete product query
    $query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error deleting product.";
    }
} else {
    header("Location: manage_products.php");
    exit();
}
?>
