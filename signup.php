<?php
session_start();
require 'config.php'; // Include the DB connection

function generateUsername($name)
{
    // Generate a simple username by appending a random number to the name
    return strtolower($name) . rand(100, 999);
}

function validatePassword($password)
{
    // Check password length
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    // Check for uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must include at least one uppercase letter.";
    }

    // Check for lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must include at least one lowercase letter.";
    }

    // Check for digit
    if (!preg_match('/\d/', $password)) {
        return "Password must include at least one digit.";
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $role = 'user';

    // Basic validation
    if (empty($name) || empty($email) || empty($gender) || empty($address) || empty($phone_number) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Validate password
        $passwordValidation = validatePassword($password);
        if ($passwordValidation !== true) {
            $error = $passwordValidation;
        } else {
            // Check if the email or phone number already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone_number = ?");
            $stmt->bind_param("ss", $email, $phone_number);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Email or phone number already exists
                $error = "This email or phone number is already registered!";
            } else {
                // Generate a random username
                $username = generateUsername($name);

                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare("INSERT INTO users (name, username, email, gender, address, phone_number, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
                $stmt->bind_param("sssssss", $name, $username, $email, $gender, $address, $phone_number, $hashed_password);

                if ($stmt->execute()) {
                    // Registration successful, redirect to login or home page
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/signup.css">
    <link href="uploads/logo.png" rel="icon">
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="uploads/logo.png" alt="Logo">
        </div>
        <h1>Register Now</h1>
        <form method="POST" action="">
            <div class="form-container">
                <div class="right-side">
                    <label for="name">Full Name:*</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:*</label>
                    <input type="email" id="email" name="email" required>

                    <label for="gender">Gender:*</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="left-side">
                    <label for="address">Address:*</label>
                    <input type="text" id="address" name="address" required>

                    <label for="phone_number">Phone Number:*</label>
                    <input type="text" id="phone_number" name="phone_number" required>

                    <label for="password">Password:*</label>
                    <input type="password" id="password" name="password" required placeholder="Must be at least 8 char 1 capital 1 small letter 1 digits ">
                </div>
            </div>

            <button type="submit">Register</button>
            <div id="log">
                <p><a href="login.php">Already have an account?</a></p>
            </div>
        </form>
    </div>
    <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
</body>

</html>
