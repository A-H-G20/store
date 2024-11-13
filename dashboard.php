<?php
session_start();
include 'config.php'; // Include your database connection file

// Fetch products from the database
$products_query = "SELECT * FROM products";
$products_result = $conn->query($products_query);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifestyle Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="uploads/logo.png" rel="icon">

</head>
<body>

<!-- Navbar -->
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
           
            <?php if ($is_logged_in): ?>
                <a href="dashboard.php" class="text-white mx-3">Home</a>
                <a href="cart.php" class="text-white mx-3">Cart</a>
                <a href="logout.php" class="text-white mx-3">Logout</a>
            <?php else: ?>
                <a href="signup.php" class="text-white mx-3">Sign Up</a>
                <a href="login.php" class="text-white mx-3">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="welcome-section">
    <h1>Welcome to our LifeStyle Store!</h1>
    <p>We have the best cameras, watches, and shirts for you. No need to hunt around, we have all in one place.</p>
</div>

<!-- Product Cards Section -->
<div class="container">
    <div class="row">
        <?php while ($row = $products_result->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <!-- Wrap the entire card in a link -->
                <a href="product_detail.php?product_id=<?php echo $row['product_id']; ?>" class="text-decoration-none">
                    <div class="card">
                        <!-- Display product images dynamically with improved responsiveness -->
                        <img src="uploads/<?php echo $row['image1']; ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                         
                            <p class="card-text text-muted">Price: $<?php echo number_format($row['price'], 2); ?></p>

                            <?php if ($is_logged_in): ?>
    <a href="add_to_cart.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-primary btn-sm mb-2" style="background-color: #007bff; color: white; border-radius: 5px; padding: 10px 20px;">Add to Cart</a><br>
  
<?php else: ?><br>
    <p><a href="login.php"  style="background-color: #007bff; color: white; border-radius: 5px; padding: 10px 20px;">Login</a></p>


                               
                                <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<style>
    .custom-add-to-cart {
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    padding: 10px 20px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.custom-add-to-cart:hover {
    background-color: #0056b3;
}

.custom-buy-now {
    background-color: #28a745;
    color: white;
    border-radius: 5px;
    padding: 10px 20px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.custom-buy-now:hover {
    background-color: #1c7430;
}

.custom-login-message {
    background-color: #ffc107;
    color: black;
    border-radius: 5px;
    padding: 10px 20px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.custom-login-message:hover {
    background-color: #e0a800;
}

</style>