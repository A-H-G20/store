<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if the product_id is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details from the database
    $product_query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // If no product is found, redirect to the main page
        header("Location: index.php");
        exit();
    }
} else {
    // If no product_id is provided, redirect to the main page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Your custom CSS -->
    <link rel="stylesheet" href="css/info.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center"><?php echo $product['name']; ?></h1>

    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?php echo $product['image1']; ?>" alt="Product Image 1" class="img-fluid">
        </div>
        <div class="col-md-6">
            <img src="uploads/<?php echo $product['image2']; ?>" alt="Product Image 2" class="img-fluid">
        </div>
    </div>

    <div class="product-details mt-4">
        <p><strong>Price: </strong>$<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Description: </strong><?php echo nl2br($product['description']); ?></p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="add_to_cart.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm mb-2">Add to Cart</a>
           
        <?php else: ?>
            <p><a href="login.php" class="btn btn-warning" style="background-color: #007bff; color: white; border-radius: 5px; padding: 10px 20px;">Login 
                
            </a></p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
