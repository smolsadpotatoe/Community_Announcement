<?php
require 'config.php';
session_start();
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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create Announcement
    if ($_POST['form_type'] === 'announcement') {
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

    // Create Event
    if ($_POST['form_type'] === 'event') {
        $title = trim($_POST['event_title']);
        $description = trim($_POST['event_description']);
        $event_date = trim($_POST['event_date']);
        $location = !empty($_POST['event_location']) ? trim($_POST['event_location']) : null;
        $imgFilename = null;

        if (!empty($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['event_image'];
            if ($file['size'] <= 5 * 1024 * 1024) {
                $info = @getimagesize($file['tmp_name']);
                if ($info !== false) {
                    $ext = image_type_to_extension($info[2], false);
                    $uploadsDir = __DIR__ . '/uploads/events';
                    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
                    $imgFilename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                    $dest = $uploadsDir . '/' . $imgFilename;
                    move_uploaded_file($file['tmp_name'], $dest);
                }
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, posted_by, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $event_date, $location, $_SESSION['user_id'], $imgFilename]);
            $_SESSION['flash_message'] = "Event created successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Error creating event: " . $e->getMessage();
        }
        header('Location: dashboard.php');
        exit;
    }
}

// Fetch announcements
$announcements = [];
try {
    $stmt = $pdo->prepare("SELECT a.*, u.full_name FROM announcements a LEFT JOIN users u ON a.posted_by = u.user_id ORDER BY a.posted_at DESC LIMIT 10");
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// Fetch upcoming events
$events = [];
try {
    $stmt = $pdo->prepare("SELECT e.*, u.full_name FROM events e LEFT JOIN users u ON e.posted_by = u.user_id WHERE e.event_date >= CURDATE() ORDER BY e.event_date ASC LIMIT 10");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Community Bulletin Board System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

  <!-- Header -->
  <header class="bg-green-700 text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">COMMUNITY BULLETIN BOARD SYSTEM</h1>
    <div class="space-x-4">
      <button id="openEventBtn" class="bg-white text-green-700 px-3 py-1 rounded hover:bg-green-100">Create Event</button>
      <button id="openAnnBtn" class="bg-white text-green-700 px-3 py-1 rounded hover:bg-green-100">Create Announcement</button>
    </div>
  </header>

  <?php if ($message): ?>
    <div class="max-w-4xl mx-auto mt-4 px-4">
      <div class="bg-white p-3 rounded shadow text-sm text-green-700"><?= htmlspecialchars($message) ?></div>
    </div>
  <?php endif; ?>

  <!-- Tabs -->
  <nav class="bg-white shadow p-4 flex gap-6 text-gray-700 font-medium">
    <button class="hover:text-green-700">Announcements</button>
    <button class="hover:text-green-700">Targeted Posts</button>
    <button class="hover:text-green-700">Upcoming Events</button>
  </nav>

  <!-- Main Content -->
  <main class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Announcements Panel -->
    <section class="bg-white p-4 rounded shadow col-span-2">
      <h2 class="text-lg font-semibold mb-4">Recent Announcements</h2>
        <div class="space-y-4">
          <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $ann): ?>
              <div class="border-b pb-4">
                <h3 class="text-md font-bold"><?= htmlspecialchars($ann['title']) ?></h3>
                <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($ann['content'])) ?></p>
                 <!-- ✅ Display image if available -->
                <?php if (!empty($ann['image'])): ?>
                  <img src="uploads/announcements/<?= htmlspecialchars($ann['image']) ?>"
                      alt="Announcement image"
                      class="mt-2 rounded w-full max-h-64 object-cover">
                <?php endif; ?>

                <div class="mt-2 flex gap-4 text-sm text-green-700">
                  <span class="text-gray-500">Posted by <?= htmlspecialchars($ann['full_name'] ?? 'Unknown') ?> • <?= htmlspecialchars(date('M j, Y H:i', strtotime($ann['posted_at']))) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-sm text-gray-600">No announcements yet. Be the first to post one.</p>
          <?php endif; ?>
        </div>
    </section>

    <!-- Calendar + Events Panel -->
    <aside class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-semibold mb-4">Upcoming Events</h2>
      <ul class="space-y-3 text-sm">
        <?php if (!empty($events)): ?>
          <?php foreach ($events as $ev): ?>
            <li>
              <strong><?= htmlspecialchars($ev['title']) ?></strong><br>
              <span class="text-gray-600"><?= htmlspecialchars(date('F j, Y', strtotime($ev['event_date']))) ?><?= !empty($ev['location']) ? ' • ' . htmlspecialchars($ev['location']) : '' ?></span>
              <div class="text-xs text-gray-500">Posted by <?= htmlspecialchars($ev['full_name'] ?? 'Unknown') ?></div>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="text-gray-600">No upcoming events.</li>
        <?php endif; ?>
      </ul>
      <!-- Placeholder for calendar widget -->
      <div class="mt-6">
        <p class="text-sm text-gray-500">[Calendar Widget Placeholder]</p>
      </div>
    </aside>

  </main>

  <!-- Modals -->
  <!-- Event Modal -->
  <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg w-full max-w-xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Create Event</h3>
        <button id="closeEventBtn" class="text-gray-600 hover:text-gray-800">✕</button>
      </div>
      <form method="POST" class="space-y-4">
        <input type="hidden" name="form_type" value="event">
        <div>
          <label class="text-sm text-gray-700">Title</label>
          <input name="event_title" required class="w-full mt-1 px-4 py-2 border rounded" />
        </div>
        <div>
          <label class="text-sm text-gray-700">Date</label>
          <input name="event_date" type="date" required class="w-full mt-1 px-4 py-2 border rounded" />
        </div>
        <div>
          <label class="text-sm text-gray-700">Location</label>
          <input name="event_location" type="text" class="w-full mt-1 px-4 py-2 border rounded" />
        </div>
        <div>
          <label class="text-sm text-gray-700">Description</label>
          <textarea name="event_description" rows="4" class="w-full mt-1 px-4 py-2 border rounded"></textarea>
        </div>
        <div class="flex justify-end gap-3">
          <button type="button" id="cancelEvent" class="px-4 py-2 rounded border">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded">Create Event</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Announcement Modal -->
  <div id="annModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg w-full max-w-lg p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Create Announcement</h3>
        <button id="closeAnnBtn" class="text-gray-600 hover:text-gray-800">✕</button>
      </div>
      <form method="POST" class="space-y-4">
        <input type="hidden" name="form_type" value="announcement">
        <div>
          <label class="text-sm text-gray-700">Title</label>
          <input name="announcement_title" required class="w-full mt-1 px-4 py-2 border rounded" />
        </div>
        <div>
          <label class="text-sm text-gray-700">Content</label>
          <textarea name="announcement_content" rows="5" class="w-full mt-1 px-4 py-2 border rounded"></textarea>
        </div>
        <div>
          <label class="text-sm text-gray-700">Event Image (optional)</label>
          <input type="file" name="event_image" accept="image/*" class="w-full mt-1 px-4 py-2 border rounded" />
        </div>
        <div class="flex justify-end gap-3">
          <button type="button" id="cancelAnn" class="px-4 py-2 rounded border">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded">Create Announcement</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal JS -->
  <script>
    const openEventBtn = document.getElementById('openEventBtn');
    const eventModal = document.getElementById('eventModal');
    const closeEventBtn = document.getElementById('closeEventBtn');
    const cancelEvent = document.getElementById('cancelEvent');

    const openAnnBtn = document.getElementById('openAnnBtn');
    const annModal = document.getElementById('annModal');
    const closeAnnBtn = document.getElementById('closeAnnBtn');
    const cancelAnn = document.getElementById('cancelAnn');

    function openModal(modal){ modal.classList.remove('hidden'); }
    function closeModal(modal){ modal.classList.add('hidden'); }

    openEventBtn && openEventBtn.addEventListener('click', ()=> openModal(eventModal));
    closeEventBtn && closeEventBtn.addEventListener('click', ()=> closeModal(eventModal));
    cancelEvent && cancelEvent.addEventListener('click', ()=> closeModal(eventModal));
    eventModal && eventModal.addEventListener('click', (e)=> { if(e.target === eventModal) closeModal(eventModal); });

    openAnnBtn && openAnnBtn.addEventListener('click', ()=> openModal(annModal));
    closeAnnBtn && closeAnnBtn.addEventListener('click', ()=> closeModal(annModal));
    cancelAnn && cancelAnn.addEventListener('click', ()=> closeModal(annModal));
    annModal && annModal.addEventListener('click', (e)=> { if(e.target === annModal) closeModal(annModal); });
  </script>

</body>
</html>
