<script>
// --- SIDEBAR LOGIC ---
const burgerBtn = document.getElementById('burgerBtn');
const closeSidebar = document.getElementById('closeSidebar');
const sidebar = document.getElementById('sidebar');

if (burgerBtn && sidebar) {
    burgerBtn.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
    });
}

if (closeSidebar && sidebar) {
    closeSidebar.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
    });
}

// --- MODAL LOGIC (Handling Image Previews) ---
function openModal(type, action, id = null, title = '', content = '', date = '', loc = '', img = '') {
    if (type === 'event') {
        const modal = document.getElementById('eventModal');
        document.getElementById('eventAction').value = action;
        document.getElementById('eventId').value = id;
        document.getElementById('mEventTitle').value = title;
        document.getElementById('mEventDate').value = date;
        document.getElementById('mEventLoc').value = loc;
        document.getElementById('mEventDesc').value = content;
        modal.classList.remove('hidden');
    } else {
        const modal = document.getElementById('annModal');
        document.getElementById('annAction').value = action;
        document.getElementById('annId').value = id;
        document.getElementById('mAnnTitle').value = title;
        document.getElementById('mAnnContent').value = content;
        
        // Handling the previous photo display
        const preview = document.getElementById('annImagePreview');
        if (img && action === 'edit') {
            preview.src = 'uploads/announcements/' + img;
            preview.classList.remove('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
        }
        modal.classList.remove('hidden');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// --- SEARCH LOGIC (Works for both Cards) ---
document.addEventListener('input', function (e) {
    if (e.target && e.target.id === 'searchInput') {
        const term = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.content-item');

        items.forEach(item => {
            const title = item.getAttribute('data-title').toLowerCase();
            const date = item.getAttribute('data-date').toLowerCase();
            
            if (title.includes(term) || date.includes(term)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
});
</script>