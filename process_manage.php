<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $type = $_POST['type']; 
    $action = $_POST['action'];
    $userId = $_SESSION['user_id'];

    if ($action === 'delete') {
        $id = $_POST['id'];
        if ($type === 'event') {
            $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ? AND posted_by = ?");
        } else {
            $stmt = $pdo->prepare("DELETE FROM announcements WHERE announcement_id = ? AND posted_by = ?");
        }
        $stmt->execute([$id, $userId]);
    } else {
        // ADD or EDIT logic
        if ($type === 'event') {
            $title = trim($_POST['event_title']);
            $desc = trim($_POST['event_description']);
            $date = $_POST['event_date'];
            $loc = trim($_POST['event_location']);

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, posted_by) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $desc, $date, $loc, $userId]);
            } else {
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, event_date=?, location=? WHERE event_id=? AND posted_by=?");
                $stmt->execute([$title, $desc, $date, $loc, $id, $userId]);
            }
        } elseif ($type === 'announcement') {
            $title = trim($_POST['announcement_title']);
            $content = trim($_POST['announcement_content']);
            $img = null;

            if (!empty($_FILES['announcement_image']['name'])) {
                $img = time() . '_' . $_FILES['announcement_image']['name'];
                move_uploaded_file($_FILES['announcement_image']['tmp_name'], "uploads/announcements/" . $img);
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO announcements (title, content, posted_by, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $content, $userId, $img]);
            } else {
                $id = $_POST['id'];
                if ($img) {
                    $stmt = $pdo->prepare("UPDATE announcements SET title=?, content=?, image=? WHERE announcement_id=? AND posted_by=?");
                    $stmt->execute([$title, $content, $img, $id, $userId]);
                } else {
                    $stmt = $pdo->prepare("UPDATE announcements SET title=?, content=? WHERE announcement_id=? AND posted_by=?");
                    $stmt->execute([$title, $content, $id, $userId]);
                }
            }
        }
    }
}
// Dynamic redirect based on what you were managing
$redirect = ($type === 'event') ? 'manageEvents' : 'manageAnnouncements';
header("Location: dashboard.php?content=" . $redirect);
exit;