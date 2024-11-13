<?php
session_start();
include '../config.php';

// Check if product ID is set in URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch current product details
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} else {
    header("Location: manage_products.php");
    exit();
}

// Update product details if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Check if new images are uploaded
    $image1 = $product['image1'];
    $image2 = $product['image2'];

    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        $image1 = 'uploads/' . uniqid() . '_' . $_FILES['image1']['name'];
        move_uploaded_file($_FILES['image1']['tmp_name'], $image1);
    }

    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        $image2 = 'uploads/' . uniqid() . '_' . $_FILES['image2']['name'];
        move_uploaded_file($_FILES['image2']['tmp_name'], $image2);
    }

    // Update query with images
    $update_query = "UPDATE products SET name = ?, price = ?, description = ?, image1 = ?, image2 = ? WHERE product_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sdsssi", $name, $price, $description, $image1, $image2, $product_id);

    if ($update_stmt->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<?php 
    include 'header.php';
    ?>
    <div class="container mt-5">
        <h1>Edit Product</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="image1">Image 1</label>
                <input type="file" class="form-control" id="image1" name="image1">
                <?php if ($product['image1']): ?>
                    <img src="<?php echo htmlspecialchars($product['image1']); ?>" alt="Image 1" width="100">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="image2">Image 2</label>
                <input type="file" class="form-control" id="image2" name="image2">
                <?php if ($product['image2']): ?>
                    <img src="<?php echo htmlspecialchars($product['image2']); ?>" alt="Image 2" width="100">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>
