<?php
include '../db.php';

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$results = [];

// Fetch results matching the search query
if ($search_query) {
    $sql = "SELECT news.*, categories.name AS category_name FROM news 
            JOIN categories ON news.category_id = categories.id 
            WHERE news.title LIKE ? OR news.content LIKE ? OR categories.name LIKE ?
            ORDER BY news.created_at DESC";

    $stmt = $conn->prepare($sql);
    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("sss", $like_query, $like_query, $like_query);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .search-results {
            padding: 20px;
        }

        .search-results h2 {
            margin-bottom: 20px;
        }

        .no-results {
            color: #777;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo">RUET Khobor</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#footer">Contact</a></li>
            <li><a href="../register.php">Admin Panel</a></li>
        </ul>
        <form action="search.php" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search news..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit">Search</button>
        </form>
    </nav>

    <!-- Search Results Section -->
    <div class="search-results">
        <h2>Search Results for "<?= htmlspecialchars($search_query) ?>"</h2>
        <?php if ($results->num_rows > 0) { ?>
            <div class="news-container">
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <div class="news-card">
                        <?php if (!empty($row['image_url'])) { ?>
                            <img src="../<?= htmlspecialchars($row['image_url']) ?>" alt="News Image">
                        <?php } ?>
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p><?= htmlspecialchars(substr($row['content'], 0, 150)) ?>...</p>
                        <a href="news_details.php?id=<?= htmlspecialchars($row['id']) ?>">Read More</a>
                        <p class="category">Category: <?= htmlspecialchars($row['category_name']) ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="no-results">No results found for your search query.</p>
        <?php } ?>
    </div>

    <!-- Footer -->
    <footer id="footer">
        <div class="footer-content">
            <p>RUET Khobor - Your Daily News Portal</p>
            <p>Contact us: contact@ruet.edu</p>
        </div>
    </footer>
</body>
</html>
