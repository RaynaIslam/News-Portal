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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
</head>
<body>
    <div class="admin-container">
        <h2>Edit News</h2>
        
        <!-- Search form -->
        <form action="edit_news.php" method="GET">
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
                            <a href="edit_form.php?id=<?php echo $news['id']; ?>" class="btn">Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
