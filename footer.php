<!-- footer-component.html -->
<footer class="bg-green-800 text-white mt-auto">
  <!-- Main Footer -->
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <!-- Brand/Logo Section -->
      <div class="lg:col-span-2">
        <div class="flex items-center mb-4">
          <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
            <i class="fas fa-bullhorn text-green-700 text-xl"></i>
          </div>
          <h2 class="text-2xl font-bold">Community Bulletin Board</h2>
        </div>
        <p class="text-green-100 mb-4">
          Stay connected with your community. Get the latest announcements, events, and updates all in one place.
        </p>
        <div class="flex space-x-4">
          <a href="#" class="text-white hover:text-green-200 transition duration-300">
            <i class="fab fa-facebook-f text-xl"></i>
          </a>
          <a href="#" class="text-white hover:text-green-200 transition duration-300">
            <i class="fab fa-twitter text-xl"></i>
          </a>
          <a href="#" class="text-white hover:text-green-200 transition duration-300">
            <i class="fab fa-instagram text-xl"></i>
          </a>
          <a href="#" class="text-white hover:text-green-200 transition duration-300">
            <i class="fab fa-linkedin-in text-xl"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-lg font-bold mb-4 border-b border-green-700 pb-2">Quick Links</h3>
        <ul class="space-y-2">
          <li>
            <a href="dashboard.php?content=announcement" class="text-green-100 hover:text-white transition duration-300 flex items-center">
              <i class="fas fa-chevron-right text-xs mr-2"></i> Dashboard
            </a>
          </li>
          <li>
            <a href="dashboard.php?content=manageEvent" class="text-green-100 hover:text-white transition duration-300 flex items-center">
              <i class="fas fa-chevron-right text-xs mr-2"></i> Manage Events
            </a>
          </li>
          <li>
            <a href="dashboard.php?content=manageAnnouncement" class="text-green-100 hover:text-white transition duration-300 flex items-center">
              <i class="fas fa-chevron-right text-xs mr-2"></i> Announcements
            </a>
          </li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div>
        <h3 class="text-lg font-bold mb-4 border-b border-green-700 pb-2">Contact Us</h3>
        <ul class="space-y-3">
          <li class="flex items-start">
            <i class="fas fa-map-marker-alt mr-3 mt-1 text-green-300"></i>
            <span class="text-green-100">123 Community St, Barangay Centro, City</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-phone mr-3 text-green-300"></i>
            <span class="text-green-100">(032) 123-4567</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-envelope mr-3 text-green-300"></i>
            <span class="text-green-100">info@communitybb.com</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-clock mr-3 text-green-300"></i>
            <span class="text-green-100">Mon-Fri: 8:00 AM - 5:00 PM</span>
          </li>
        </ul>
      </div>
    </div>


  <!-- Bottom Footer -->
  <div class="bg-green-900 py-4">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="text-green-200 text-sm mb-4 md:mb-0">
          &copy; <?php echo date('Y'); ?> Community Bulletin Board System. All rights reserved.
        </div>
        <div class="flex space-x-6 text-sm">
          <a href="privacy.php" class="text-green-200 hover:text-white transition duration-300">Privacy Policy</a>
          <a href="terms.php" class="text-green-200 hover:text-white transition duration-300">Terms of Service</a>
          <a href="sitemap.php" class="text-green-200 hover:text-white transition duration-300">Sitemap</a>
          <a href="help.php" class="text-green-200 hover:text-white transition duration-300">Help Center</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Back to Top Button -->
  <button 
    id="backToTop" 
    class="fixed bottom-8 right-8 bg-green-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-green-700 transition duration-300 hidden z-40"
    aria-label="Back to top"
  >
    <i class="fas fa-chevron-up"></i>
  </button>
</footer>

<script>
  // Back to top functionality
  document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTop');
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopBtn.classList.remove('hidden');
      } else {
        backToTopBtn.classList.add('hidden');
      }
    });
    
    // Scroll to top when clicked
    if (backToTopBtn) {
      backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }
  });
</script>

<style>
  /* Optional additional styles */
  footer a:hover {
    transform: translateX(3px);
    transition: transform 0.2s ease;
  }
  
  .social-icon:hover {
    transform: translateY(-3px);
    transition: transform 0.3s ease;
  }
</style>