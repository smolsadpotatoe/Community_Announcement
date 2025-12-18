<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['event_title'] ?? '');
    $description = trim($_POST['event_description'] ?? '');
    $event_date = trim($_POST['event_date'] ?? '');
    $location = !empty($_POST['event_location']) ? trim($_POST['event_location']) : null;

    // Validate
    if (empty($title) || empty($description) || empty($event_date)) {
        $_SESSION['flash_message'] = "All required fields must be filled.";
        // Use the correct parameter name (check if it's manageEvent or manageEvents)
        header('Location: dashboard.php?content=manageEvents');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, posted_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $description, $event_date, $location, $_SESSION['user_id']]);
        
        $_SESSION['flash_message'] = "Event created successfully!";
        $_SESSION['flash_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['flash_message'] = "Error: " . $e->getMessage();
        $_SESSION['flash_type'] = "error";
    }
    
    // Use consistent parameter name
    header('Location: dashboard.php?content=manageEvent');
    exit;
}

// If not POST, redirect with consistent parameter
header('Location: dashboard.php?content=manageEvents');
exit;
?>