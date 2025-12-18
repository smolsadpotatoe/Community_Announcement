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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community Bulletin Board System</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Custom CSS -->
  <style>
    .burger div:nth-child(1).open { transform: rotate(-90deg) translateY(10px); }
    .burger div:nth-child(3).open { transform: rotate(90deg) translateY(-10px); }
    
    /* Active navigation item */
    .nav-item.active {
      background-color: #f0fdf4;
      color: #166534;
      border-left: 4px solid #16a34a;
    }
    
    /* Smooth transitions */
    .page-transition {
      transition: opacity 0.3s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans flex flex-col">
  
  <!-- Include Header Component -->
  <?php include 'header-component.html'; ?>
  
  <!-- Flash Message -->
  <?php if ($message): ?>
    <div class="container mx-auto px-4 mt-4">
      <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500">
        <div class="flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-3"></i>
          <p class="text-green-700"><?php echo htmlspecialchars($message); ?></p>
        </div>
      </div>
    </div>
  <?php endif; ?>
  
  <!-- Main Content -->
  <main class="flex-1 container mx-auto px-4 py-8">
    <!-- Page Title -->
    <div class="mb-8">
      <?php
      $pageTitle = "Dashboard";
      if (isset($_GET['content'])) {
        switch ($_GET['content']) {
          case 'manageEvent':
            $pageTitle = "Manage Events";
            break;
          case 'manageAnnouncement':
            $pageTitle = "Manage Announcements";
            break;
          case 'userLogs':
            $pageTitle = "User Logs";
            break;
          case 'announcement':
          default:
            $pageTitle = "Announcements";
        }
      }
      ?>
      <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo $pageTitle; ?></h2>
      <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?>!</p>
    </div>
    
    <!-- Dynamic Content Area -->
    <div class="page-transition">
      <?php
      if (isset($_GET['content'])) {
          $content = $_GET['content'];
          if ($content === "announcement") {
              include './dashboard_styles.php';
          } elseif ($content === "manageEvent") {
              include './manage_events.php';
          } elseif ($content === "manageAnnouncement") {
              include './manage_announcements.php';
          } elseif ($content === "userLogs") {
              include './user_logs.php';
          } else {
              include './dashboard_styles.php';
          }
      } else {
          include './dashboard_styles.php';
      }
      ?>
    </div>
  </main>
  
  <!-- Include Footer Component -->
  <?php include 'footer.php'; ?>
  
  <!-- Include Modals and Scripts -->
  <?php include 'modals.php'; ?>
  <?php include 'scripts.php'; ?>
  
  <!-- Component Loader Script (Alternative method if includes don't work) -->
  <script>
    // Alternative method to load components if PHP includes fail
    document.addEventListener('DOMContentLoaded', function() {
      // Function to load component
      function loadComponent(containerId, filePath) {
        fetch(filePath)
          .then(response => {
            if (!response.ok) {
              console.error(`Failed to load ${filePath}`);
              return;
            }
            return response.text();
          })
          .then(html => {
            if (html) {
              document.getElementById(containerId).innerHTML = html;
            }
          })
          .catch(error => {
            console.error('Error loading component:', error);
          });
      }
      
      // Uncomment if you want to use JS loading instead of PHP includes
      // loadComponent('header-container', 'header-component.html');
      // loadComponent('footer-container', 'footer-com ponent.html');
      
      // Update active nav item
      const currentPage = new URLSearchParams(window.location.search).get('content') || 'announcement';
      document.querySelectorAll('.nav-item').forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes(currentPage)) {
          item.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>