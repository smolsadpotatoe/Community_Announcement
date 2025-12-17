<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle announcement creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['announcement_title']);
    $content = trim($_POST['announcement_content']);
    $imgFilename = null;

    if (!empty($_FILES['announcement_image']) && $_FILES['announcement_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['announcement_image'];
        if ($file['size'] <= 5 * 1024 * 1024) { // 5MB
            $info = @getimagesize($file['tmp_name']);
            if ($info !== false) {
                $ext = image_type_to_extension($info[2], false);
                $uploadsDir = __DIR__ . '/uploads/announcements';
                if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
                $imgFilename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $dest = $uploadsDir . '/' . $imgFilename;
                move_uploaded_file($file['tmp_name'], $dest);
            }
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO announcements (title, content, posted_by, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $_SESSION['user_id'], $imgFilename]);
        $_SESSION['flash_message'] = "Announcement created successfully.";
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error creating announcement: " . $e->getMessage();
    }
    header('Location: dashboard.php');
    exit;
}

// If not a POST request, redirect back
header('Location: dashboard.php');
exit;
?>
<!-- No HTML needed here since it redirects -->