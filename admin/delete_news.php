<?php
include('../db.php'); // Include the database connection file

// Search functionality
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Fetch news articles from the database
$query = "SELECT * FROM news";
if (!empty($search_query)) {
    $query .= " WHERE title LIKE '%$search_query%'";
}
$query .= " LIMIT 10"; // Display the first 10 articles
$result = mysqli_query($conn, $query);

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM news WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: delete_news.php"); // Refresh the page after deletion
    } else {
        echo "Error deleting news: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete News</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
</head>
<body>
    <div class="admin-container">
        <h2>Delete News</h2>
        
        <!-- Search form -->
        <form action="delete_news.php" method="GET">
            <input type="text" name="search" placeholder="Search by title" value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="submit" value="Search">
        </form>

        <!-- List of news articles -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($news = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $news['id']; ?></td>
                        <td><?php echo $news['title']; ?></td>
                        <td>
                            <a href="delete_news.php?delete_id=<?php echo $news['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this news?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
