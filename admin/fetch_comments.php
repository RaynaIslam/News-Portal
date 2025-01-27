<?php
include '../db.php';

$news_id = isset($_GET['news_id']) ? (int)$_GET['news_id'] : 0;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

$response = ['comments' => [], 'hasMore' => false];

if ($news_id > 0) {
    $query = "SELECT * FROM comments WHERE news_id = ? ORDER BY created_at DESC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $news_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response['comments'][] = $row;
    }

    // Check if more comments exist
    $query = "SELECT COUNT(*) AS total FROM comments WHERE news_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalComments = $result->fetch_assoc()['total'];

    $response['hasMore'] = ($offset + $limit) < $totalComments;
}

header('Content-Type: application/json');
echo json_encode($response);
