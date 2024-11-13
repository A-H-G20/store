<?php
session_start();
include '../config.php';

// Check if the user is already logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // Redirect to login page if not an admin
    header("Location: login.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "Username or Email already exists.";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert the new admin into the users table
            $insert_query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')";
            if ($stmt = $conn->prepare($insert_query)) {
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                if ($stmt->execute()) {
                    $success_message = "Admin added successfully.";
                } else {
                    $error_message = "Error adding admin: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = "Error preparing the query.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="uploads/logo.png" rel="icon">
</head>
<body>
<?php 
    include 'header.php';
    ?>
    <div class="container mt-5">
        <h1>Add Admin</h1>

        <!-- Display error or success message -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Admin creation form -->
        <form action="add_admin.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Admin</button>
        </form>
    </div>
</body>
</html>
