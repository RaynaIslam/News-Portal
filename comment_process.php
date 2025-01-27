<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $news_id = $_POST['news_id'];
    $email = $_POST['email'];
    $comment_text = $_POST['comment_text'];

    // Use prepared statements to avoid SQL injection and syntax issues
    $sql = "INSERT INTO comments (news_id, user_email, comment_text, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iss", $news_id, $email, $comment_text); // "i" for integer, "s" for strings
        if ($stmt->execute()) {
            header("Location: client/news_details.php?id=$news_id");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>
