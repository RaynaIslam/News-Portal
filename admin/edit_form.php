<?php
include('../db.php'); // Include the database connection file

if (isset($_GET['id'])) {
    $news_id = $_GET['id'];

    // Fetch the news details from the database
    $query = "SELECT * FROM news WHERE id = '$news_id'";
    $result = mysqli_query($conn, $query);
    $news = mysqli_fetch_assoc($result);

    // Fetch all categories for the dropdown
    $categories_query = "SELECT * FROM categories";
    $categories_result = mysqli_query($conn, $categories_query);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get updated details from the form
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

        $image = $_FILES['image']['name'];
        $video = $_FILES['video']['name'];

        $update_query = "UPDATE news SET title = '$title', content = '$content', category_id = '$category_id'";

        // Handle image upload
        if ($image) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_path = "../uploads/" . $image;
            move_uploaded_file($image_tmp, $image_path);
            $update_query .= ", image = '$image'";
        }

        // Handle video upload
        if ($video) {
            $video_tmp = $_FILES['video']['tmp_name'];
            $video_path = "../uploads/videos/" . $video;
            move_uploaded_file($video_tmp, $video_path);
            $update_query .= ", video = '$video'";
        }

        $update_query .= " WHERE id = '$news_id'";

        if (mysqli_query($conn, $update_query)) {
            header("Location: edit_news.php"); // Redirect after updating
            exit();
        } else {
            echo "Error updating news: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: edit_news.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link rel="stylesheet" href="../css/edit_form_styles.css"> <!-- Link to the separate CSS file -->
</head>
<body>
    <div class="edit-form-container">
        <h2>Edit News Article</h2>
        <form action="edit_form.php?id=<?php echo $news_id; ?>" method="POST" enctype="multipart/form-data" class="edit-form">
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>

            <label for="content">Content:</label>
            <textarea name="content" rows="6" required><?php echo htmlspecialchars($news['content']); ?></textarea>

            <label for="category_id">Category:</label>
            <select name="category_id" required>
                <?php
                // Populate the dropdown with categories
                while ($category = mysqli_fetch_assoc($categories_result)) {
                    $selected = $category['id'] == $news['category_id'] ? 'selected' : '';
                    echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                }
                ?>
            </select>

            <label for="image">Image:</label>
            <input type="file" name="image">
            <?php if ($news['image_path']): ?>
                <p>Current Image: <img src="../uploads/<?php echo $news['image']; ?>" alt="Current Image" style="max-width: 150px;"></p>
            <?php endif; ?>

            <label for="video">Video:</label>
            <input type="file" name="video">
            <?php if ($news['video_path']): ?>
                <p>Current Video: <video src="../uploads/videos/<?php echo $news['video']; ?>" controls style="max-width: 150px;"></video></p>
            <?php endif; ?>

            <div class="form-actions">
                <input type="submit" value="Update News" class="btn update-btn">
                <a href="edit_news.php" class="btn cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
