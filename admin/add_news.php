<?php
// Include database connection
include '../db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $imagePath = null;
    $videoPath = null;

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = "uploads/images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Handle video upload
    if (!empty($_FILES['video']['name'])) {
        $videoName = basename($_FILES['video']['name']);
        $videoPath = "uploads/videos/" . $videoName;
        move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);
    }

    $categoryName = $_POST['category'];
    // Fetch category ID based on the name
    $categoryQuery = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $categoryQuery->bind_param("s", $categoryName);
    $categoryQuery->execute();
    $categoryResult = $categoryQuery->get_result();
    
    if ($categoryResult->num_rows > 0) {
        $categoryId = $categoryResult->fetch_assoc()['id'];
    } else {
        die("Invalid category selected.");
    }
    
    // Insert news with the fetched category ID
    $stmt = $conn->prepare("INSERT INTO news (title, content, category_id, image_path, video_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $title, $content, $categoryId, $imagePath, $videoPath);
    
    if ($stmt->execute()) {
        echo "News added successfully.";
        header("Location: admin_panel.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
    <link rel="stylesheet" href="../css/add_news_styles.css">
</head>
<body>
    <div class="news-container">
        <h2>Add News</h2>
        <form action="add_news.php" method="POST" enctype="multipart/form-data">
            <!-- Title -->
            <label for="title">News Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter the news title" required>

            <!-- Content -->
            <label for="content">News Content:</label>
            <textarea id="content" name="content" rows="6" placeholder="Write the news content here..." required></textarea>

            <!-- Category -->
            <label for="category">Select Category:</label>
            <select id="category" name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Research">Research</option>
                <option value="Sports">Sports</option>
                <option value="Events">Events</option>
                <option value="General">General</option>
            </select>

            <!-- Images -->
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*">

            <!-- Videos -->
            <label for="video">Upload Video:</label>
            <input type="file" id="video" name="video" accept="video/*">

            <!-- Submit Button -->
            <button type="submit">Add News</button>
        </form>
    </div>
</body>
</html>

