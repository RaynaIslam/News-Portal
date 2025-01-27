<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate email domain
    if (strpos($email, '.ruet.ac.bd') === false) {
        $message = "Invalid email.";
    } else {
        // Check if email already exists
        $check_email_query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_email_query);

        if ($result->num_rows > 0) {
            $message = "Email already registered. Please log in.";
        } else {
            // Insert new admin
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                $message = "Registration successful. Please log in.";
            } else {
                $message = "Error: " . $conn->error;
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
    <title>Admin Registration</title>
    <link rel="stylesheet" href="css/reg_styles.css">
</head>
<body>
    <div class="register-container">
        <h2>Admin Registration</h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Register</button>
        </form>

        <!-- Login Button -->
        <div class="login-link">
            <p>Already have an account?</p>
            <a href="login.php" class="btn-login">Log In</a>
        </div>
    </div>
</body>
</html>
