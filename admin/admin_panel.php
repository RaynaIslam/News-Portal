<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
</head>
<body>
    <div class="admin-container">
        <h1>Welcome, Admin</h1>
        <div class="admin-links">
            <a href="add_news.php" class="btn">Add News</a>
            <a href="edit_news.php" class="btn">Edit News</a>
            <a href="delete_news.php" class="btn">Delete News</a>
            <a href="../client/index.php" class="btn logout">Logout</a> 
        </div>
    </div>
</body>
</html>
