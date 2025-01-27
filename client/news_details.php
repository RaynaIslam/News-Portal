<?php
include '../db.php';

$news_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch news details
$news_query = "SELECT news.*, categories.name FROM news 
               JOIN categories ON news.category_id = categories.id 
               WHERE news.id = $news_id";
$news_result = $conn->query($news_query);
$news = $news_result->fetch_assoc();

// Fetch the latest comment
$comments_query = "SELECT * FROM comments WHERE news_id = $news_id ORDER BY created_at DESC LIMIT 1";
$latest_comment_result = $conn->query($comments_query);
$latest_comment = $latest_comment_result->fetch_assoc();

// Fetch all comments (for "See More" functionality)
$all_comments_query = "SELECT * FROM comments WHERE news_id = $news_id ORDER BY created_at DESC LIMIT 5, 100"; // Fetch more comments after the latest one
$all_comments_result = $conn->query($all_comments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news['title']) ?></title>
    <link rel="stylesheet" href="../css/news_details_styles.css">
</head>
<body>
    <div class="news-details">
        <h1><?= htmlspecialchars($news['title']) ?></h1>
        <p class="category">Category: <?= htmlspecialchars($news['name']) ?></p>
        
        <!-- Display image if available -->
        <?php if (isset($news['image_path']) && $news['image_path']) { ?>
            <img src="../<?= htmlspecialchars($news['image_path']) ?>" alt="News Image">
        <?php } elseif (isset($news['video_path']) && $news['video_path']) { ?>
            <!-- Display video if available -->
            <video controls>
                <source src="../<?= htmlspecialchars($news['video_path']) ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        <?php } else { ?>
            <!-- Message when neither image nor video is available -->
            <p>No media available for this news article.</p>
        <?php } ?>
        
        <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>
    </div>

    <!-- Latest Comment -->
    <div class="comments-section">
        <h3>Latest Comment</h3>
        <?php if ($latest_comment) { ?>
            <div class="comment">
                <p><strong><?= htmlspecialchars($latest_comment['user_email']) ?></strong></p>
                <p><?= nl2br(htmlspecialchars($latest_comment['comment_text'])) ?></p>
                <p><small><?= htmlspecialchars($latest_comment['created_at']) ?></small></p>
            </div>
        <?php } else { ?>
            <p>No comments yet.</p>
        <?php } ?>
        
        <!-- See More Comments Button -->
        <a href="news_details.php?id=<?= $news_id ?>&show_all_comments=true" class="see-more-comments">See More Comments</a>
    </div>

    <!-- All Comments (If "See More" is clicked) -->
    <?php if (isset($_GET['show_all_comments']) && $_GET['show_all_comments'] == 'true') { ?>
        <h3>All Comments</h3>
        <div class="comments-list">
            <?php while ($comment = $all_comments_result->fetch_assoc()) { ?>
                <div class="comment">
                    <p><strong><?= htmlspecialchars($comment['user_email']) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                    <p><small><?= htmlspecialchars($comment['created_at']) ?></small></p>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <!-- Comment Form -->
    <h3>Post a Comment</h3>
    <form action="../comment_process.php" method="POST">
        <input type="hidden" name="news_id" value="<?= $news_id ?>">
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="comment_text" placeholder="Add a comment..." required></textarea>
        <button type="submit">Post Comment</button>
    </form>
</body>
</html>
