<?php
session_start();
include '../config.php';

$query = "SELECT product_id, name, price, description FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<?php 
    include 'header.php';
    ?>
    <div class="container mt-5">
        <h1>Manage Products</h1>
        <a href="add_product.php" class="btn btn-success mb-3">Add Product</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo isset($product['product_id']) ? $product['product_id'] : 'N/A'; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td>Rs. <?php echo $product['price']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td>
                        <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm">Edit</a>

                        <a href="delete_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
