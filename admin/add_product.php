<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Paths for uploaded images
    $image1 = $_FILES['image1'];
    $image2 = $_FILES['image2'];
    
    // Define upload directory
    $uploadDir = '../uploads/';
    
    // Check if the directory exists, if not, create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // 0777 is the permission level
    }

    // Process first image
    $image1Name = uniqid() . '_' . basename($image1['name']);
    $image1Path = $uploadDir . $image1Name;
    if (move_uploaded_file($image1['tmp_name'], $image1Path)) {
        echo "Image 1 uploaded successfully.<br>";
    } else {
        echo "Error uploading Image 1.<br>";
    }

    // Process second image
    $image2Name = uniqid() . '_' . basename($image2['name']);
    $image2Path = $uploadDir . $image2Name;
    if (move_uploaded_file($image2['tmp_name'], $image2Path)) {
        echo "Image 2 uploaded successfully.<br>";
    } else {
        echo "Error uploading Image 2.<br>";
    }

    // Insert product into database with only image names (not full path)
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image1, image2) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $name, $price, $description, $image1Name, $image2Name);
    
    if ($stmt->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error adding product to the database.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<?php 
    include 'header.php';
?>
    <div class="container mt-5">
        <h1>Add New Product</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="image1">Image 1</label>
                <input type="file" class="form-control-file" id="image1" name="image1" required>
            </div>
            <div class="form-group">
                <label for="image2">Image 2</label>
                <input type="file" class="form-control-file" id="image2" name="image2" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>
</html>
