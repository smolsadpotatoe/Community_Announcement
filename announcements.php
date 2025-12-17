<main class="p-6 grid grid-cols-4 md:grid-cols-3 gap-6">

<section class="bg-white p-4 rounded shadow col-span-2">
      <h2 class="text-lg font-semibold mb-4 text-green-700">Recent Announcements</h2>
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

    <aside class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-semibold mb-4 text-green-700">Upcoming Events</h2>
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