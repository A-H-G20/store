<?php
session_start();
include('config.php');

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];
    
    // Delete item from the cart
    $stmt = $mysqli->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: cart.php");
exit();
?>
