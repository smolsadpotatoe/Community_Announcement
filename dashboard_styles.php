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

              <!-- Reactions UI -->
              <div class="mt-2 flex items-center gap-4 text-lg">
                <button class="react-btn-heart flex items-center justify-center like-btn" data-id="<?= $ann['announcement_id'] ?>" aria-label="Like" style="background:transparent;border:none;outline:none;cursor:pointer;height:36px;width:36px;padding:0;">
                  <svg class="heart-icon transition-colors duration-200" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                    <path d="M12 21C12 21 4 13.36 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.36 16 21 16 21H12Z"/>
                  </svg>
                  <span class="reaction-counts ml-1 text-base text-gray-700 align-middle" id="reactions-<?= $ann['announcement_id'] ?>">0</span>
                </button>
                <span class="comment-btn-wrapper flex items-center">
                  <button class="show-comments-btn flex items-center justify-center comment-btn" data-id="<?= $ann['announcement_id'] ?>" aria-label="Comment" style="background:transparent;border:none;outline:none;cursor:pointer;height:36px;width:36px;padding:0;">
                    <svg class="comment-icon" width="28" height="28" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="display:block;">
                      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                  </button>
                  <span class="comment-count ml-1 text-base text-gray-700 align-middle" id="comment-count-<?= $ann['announcement_id'] ?>">0</span>
                </span>
              </div>
              <!-- Comment Modal Trigger Only, no inline form/list -->
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

<!-- Reactions & Comments JS -->
<style>
.heart-icon, .comment-icon {
  width: 28px;
  height: 28px;
  transition: stroke 0.2s, fill 0.2s;
}
.liked .heart-icon {
  stroke: #16a34a !important;
  fill: #16a34a !important;
}
.center-comments {
  display: flex;
  flex-direction: column;
  align-items: center;
}
@keyframes slideup { from { transform: translateY(100%); } to { transform: translateY(0); } }
.animate-slideup { animation: slideup 0.3s cubic-bezier(.4,0,.2,1); }
#comment-modal { transition: background 0.2s; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Load reactions and comments for each announcement
  document.querySelectorAll('.reaction-counts').forEach(function(el) {
    const id = el.id.match(/\d+/);
    if (id) loadReactionsAndComments(id[0]);
  });

  // Heart like button click
  document.querySelectorAll('.react-btn-heart').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const annId = this.dataset.id;
      // UI feedback: turn green immediately
      this.classList.toggle('liked');
      // Optimistically update count
      const rc = this.querySelector('.reaction-counts');
      let count = parseInt(rc.textContent) || 0;
      if (!this.classList.contains('liked')) {
        // If unliking, decrease
        count = Math.max(0, count - 1);
      } else {
        // If liking, increase
        count = count + 1;
      }
      rc.textContent = count;
      fetch('reactions_comments.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=react&announcement_id=${annId}&reaction=love`
      }).then(() => loadReactionsAndComments(annId));
    });
  });

  // Show comments in modal on button click
  document.querySelectorAll('.show-comments-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const annId = this.dataset.id;
      const modal = document.getElementById('comment-modal');
      const modalInner = document.getElementById('comment-modal-inner');
      modal.classList.remove('hidden');
      // Load modal content via AJAX
      fetch(`comment_modal.php?announcement_id=${annId}`)
        .then(r => r.text())
        .then(html => { modalInner.innerHTML = html; });
    });
  });

  // Hide modal on background or close button click
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('comment-modal');
    if (!modal.classList.contains('hidden')) {
      if (e.target === modal) modal.classList.add('hidden');
    }
  });

  function loadReactionsAndComments(annId) {
    fetch(`reactions_comments.php?announcement_id=${annId}`)
      .then(r => r.json())
      .then(data => {
        // Reactions
        const rc = document.getElementById('reactions-' + annId);
        let loveCount = 0;
        let userLiked = false;
        if (data.reactions) {
          data.reactions.forEach(r => {
            if (r.reaction_type === 'love') loveCount = parseInt(r.count);
          });
        }
        if (data.user_reacted && data.user_reacted.love) userLiked = true;
        rc.textContent = loveCount;
        // Set heart color if user liked
        const btn = rc.closest('.react-btn-heart');
        if (btn) {
          if (userLiked) {
            btn.classList.add('liked');
          } else {
            btn.classList.remove('liked');
          }
        }
      });
  }
      });
</script>