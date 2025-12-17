<?php
require 'config.php';
require 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Flash message handling
$message = "";
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

// Fetch announcements (with fallback if function fails)
$announcements = fetchAnnouncements($pdo, 10);
if (empty($announcements)) {
    try {
        $stmt = $pdo->prepare("SELECT a.*, u.full_name FROM announcements a LEFT JOIN users u ON a.posted_by = u.user_id ORDER BY a.posted_at DESC LIMIT 10");
        $stmt->execute();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $announcements = [];
    }
}

// Fetch upcoming events (with fallback)
$events = fetchUpcomingEvents($pdo, 10);
if (empty($events)) {
    try {
        $stmt = $pdo->prepare("SELECT e.*, u.full_name FROM events e LEFT JOIN users u ON e.posted_by = u.user_id WHERE e.event_date >= CURDATE() ORDER BY e.event_date ASC LIMIT 10");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $events = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Community Bulletin Board System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .burger div:nth-child(1).open { transform: rotate(-90deg) translateY(10px); }
    .burger div:nth-child(3).open { transform: rotate(90deg) translateY(-10px); }
  </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
  <!-- Header -->
  <header class="bg-green-700 text-white p-4 flex justify-between items-center relative z-20">
    <div id="burgerBtn" class="flex flex-col justify-around w-6 h-5 cursor-pointer transition-all duration-300 ease-in-out">
      <div class="w-full h-0.5 bg-white transition-all duration-300"></div>
      <div class="w-full h-0.5 bg-white transition-all duration-300"></div>
      <div class="w-full h-0.5 bg-white transition-all duration-300"></div>
    </div>
    <h1 class="text-xl font-bold">COMMUNITY BULLETIN BOARD SYSTEM</h1>
  </header>

  <!-- Left Sidebar Panel -->
  <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-green-50 to-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out z-30 border-r border-gray-200">
    <div class="p-6">
      <button id="closeSidebar" class="absolute top-4 right-4 text-green-600 hover:text-green-800 text-2xl font-bold transition-colors duration-200">&times;</button>
      <h2 class="text-xl font-bold mb-6 text-green-800 border-b border-green-200 pb-2">Menu</h2>
      <nav class="space-y-3">
        <a href="?content=announcement" class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-100 hover:text-green-900 rounded-lg transition-all duration-200 shadow-sm font-semibold">
          <span class="mr-3 text-lg"><img src="icons/dashboard.png" alt="Dashboard"></span> Dashboard
        </a>
        <a href="?content=manageEvent" class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-100 hover:text-green-900 rounded-lg transition-all duration-200 shadow-sm font-semibold">
          <span class="mr-3 text-lg"><img src="icons/events.png" alt="Events"></span> Manage Events
        </a>
        <a href="?content=manageAnnouncement" class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-100 hover:text-green-900 rounded-lg transition-all duration-200 shadow-sm font-semibold">
          <span class="mr-3 text-lg"><img src="icons/announce.png" alt="Announcements"></span> Manage Announcements
        </a>
        <a href="?content=userLogs" class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-100 hover:text-green-900 rounded-lg transition-all duration-200 shadow-sm font-semibold">
          <span class="mr-3 text-lg"><img src="icons/userlogs.png" alt="Logs"></span> User Logs
        </a>
        <a href="logout.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-100 hover:text-red-900 rounded-lg transition-all duration-200 shadow-sm font-semibold">
          <span class="mr-3 text-lg"><img src="icons/logout.png" alt="Logout"></span> Log Out
        </a>
      </nav>
    </div>
  </div>

  <!-- Overlay for sidebar -->
  <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-20"></div>

  <?php if ($message): ?>
    <div class="max-w-4xl mx-auto mt-4 px-4">
      <div class="bg-white p-3 rounded shadow text-sm text-green-700"><?= htmlspecialchars($message) ?></div>
    </div>
  <?php endif; ?>

  
  <!-- Main Content -->
  <main class="p-6 gap-6">
    <?php
    if (isset($_GET['content'])) {
        $content = $_GET['content'];
        if ($content === "announcement") {
            include './announcements.php';
        } elseif ($content === "manageEvent") {
            include './manage_events.php';
        } elseif ($content === "manageAnnouncement") {
            include './manage_announcements.php';
        } else {
            include './announcements.php'; // Default
        }
    } else {
        include './announcements.php'; // Default
    }
    ?>
  </main>

  <?php include 'modals.php'; ?>
  <?php include 'scripts.php'; ?>
</body>
</html>