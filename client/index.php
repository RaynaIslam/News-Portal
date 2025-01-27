<?php
include '../db.php';

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT news.*, categories.name FROM news 
        JOIN categories ON news.category_id = categories.id";
if ($search_query) {
    $sql .= " WHERE news.title LIKE '%$search_query%' OR categories.name LIKE '%$search_query%'";
}
$sql .= " ORDER BY news.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Portal</title>
    <link rel="stylesheet" href="../css/styles.css">

</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo"><img src="../images/news.jpg"></div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#footer">Contact</a></li>
            <li><a href="../register.php">Admin Panel</a></li> <!-- Link to Admin Panel -->
        </ul>
        <form action="index.php" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search news..." value="<?= $search_query ?>">
            <button type="submit">Search</button>
        </form>
    </nav>
    <!-- News Section -->
<div class="news-container">
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="news-card">
            <?php if (!empty($row['image_path'])) { ?>
                <!-- Show image if available -->
                <img src="../<?= htmlspecialchars($row['image_path']) ?>" alt="News Image">
            <?php } ?>

            <?php if (!empty($row['video_path'])) { ?>
                <!-- Show video if available -->
                <video controls>
                    <source src="../<?= htmlspecialchars($row['video_path']) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php } ?>

            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= htmlspecialchars(substr($row['content'], 0, 150)) ?>...</p>
            <a href="news_details.php?id=<?= htmlspecialchars($row['id']) ?>">Read More</a>
            <p class="category">Category: <?= htmlspecialchars($row['name']) ?></p>
        </div>
    <?php } ?>
  </div>


    <!-- Footer -->
    <footer id="footer">
        <div class="footer-content">
            <p>RUET Khobor - Your Campus News Portal</p>
            <p>Contact us: <a href="mailto:2003161@student.ruet.ac.bd">Email</a>
            </p>
        </div>
    </footer>
</body>
</html>
