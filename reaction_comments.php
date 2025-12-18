<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $announcementId = (int)($_POST['announcement_id'] ?? 0);
    if ($action === 'react') {
        $reaction = $_POST['reaction'] ?? '';
        if (!in_array($reaction, ['like','love','wow','sad','angry'])) {
            echo json_encode(['error' => 'Invalid reaction']);
            exit;
        }
        // Check if user already reacted
        $stmt = $pdo->prepare("SELECT reaction_id FROM reactions WHERE user_id=? AND announcement_id=? AND reaction_type=?");
        $stmt->execute([$userId, $announcementId, $reaction]);
        $existing = $stmt->fetch();
        if ($existing) {
            // If already reacted, remove reaction (unlike)
            $stmt = $pdo->prepare("DELETE FROM reactions WHERE reaction_id=?");
            $stmt->execute([$existing['reaction_id']]);
            echo json_encode(['success' => true, 'removed' => true]);
        } else {
            // Add reaction
            $stmt = $pdo->prepare("INSERT INTO reactions (user_id, announcement_id, reaction_type) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $announcementId, $reaction]);
            echo json_encode(['success' => true, 'added' => true]);
        }
        exit;
    } elseif ($action === 'comment') {
        $comment = trim($_POST['comment'] ?? '');
        $parentId = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        if ($comment === '') {
            echo json_encode(['error' => 'Empty comment']);
            exit;
        }
        if ($parentId) {
            $stmt = $pdo->prepare("INSERT INTO comments (user_id, announcement_id, comment_text, parent_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $announcementId, $comment, $parentId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO comments (user_id, announcement_id, comment_text) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $announcementId, $comment]);
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

// GET: fetch reactions and comments
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $announcementId = (int)($_GET['announcement_id'] ?? 0);
    // Reactions count
    $stmt = $pdo->prepare("SELECT reaction_type, COUNT(*) as count FROM reactions WHERE announcement_id=? GROUP BY reaction_type");
    $stmt->execute([$announcementId]);
    $reactions = $stmt->fetchAll();
    // Comments (top-level)
    $stmt = $pdo->prepare("SELECT c.comment_id, c.comment_text, c.commented_at, u.full_name FROM comments c LEFT JOIN users u ON c.user_id = u.user_id WHERE c.announcement_id=? AND (c.parent_id IS NULL OR c.parent_id=0) ORDER BY c.commented_at ASC");
    $stmt->execute([$announcementId]);
    $comments = $stmt->fetchAll();
    // Replies for each comment
    foreach ($comments as &$comment) {
        $stmt = $pdo->prepare("SELECT c.comment_text, c.commented_at, u.full_name FROM comments c LEFT JOIN users u ON c.user_id = u.user_id WHERE c.parent_id=? ORDER BY c.commented_at ASC");
        $stmt->execute([$comment['comment_id']]);
        $comment['replies'] = $stmt->fetchAll();
    }
    // Check if current user has reacted (for heart/like)
    $stmt = $pdo->prepare("SELECT reaction_type FROM reactions WHERE announcement_id=? AND user_id=? LIMIT 1");
    $stmt->execute([$announcementId, $userId]);
    $userReacted = $stmt->fetch();
    $user_reacted = [ 'love' => false ];
    if ($userReacted && $userReacted['reaction_type'] === 'love') {
        $user_reacted['love'] = true;
    }
    echo json_encode(['reactions' => $reactions, 'comments' => $comments, 'user_reacted' => $user_reacted]);
    exit;
}
