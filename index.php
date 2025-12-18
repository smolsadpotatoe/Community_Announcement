<?php
// Simple landing page using Tailwind CDN
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Community Announcement</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-50 text-gray-800">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <a href="index.php" class="text-xl font-semibold text-green-800">Community Announcement</a>
          <nav class="space-x-4">
            <a href="login.php" class="text-sm text-gray-600 hover:text-green-700">Login</a>
            <a href="register.php" class="text-sm text-gray-600 hover:text-green-700">Register</a>
            <a href="announcements.php" class="text-sm text-gray-600 hover:text-green-700">Announcements</a>
          </nav>
        </div>
      </div>
    </header>

    <main class="mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">Community Announcements Made Simple</h1>
            <p class="mt-6 text-lg text-gray-600">Create, manage and share announcements and events with your neighborhood or organization quickly and easily.</p>
            <div class="mt-8 flex items-center space-x-4">
              <a href="create_announcement.php" class="inline-flex items-center px-6 py-3 bg-green-800 text-white rounded-md shadow hover:bg-green-900">Create Announcement</a>
              <a href="announcements.php" class="text-sm text-green-700 hover:underline">Browse announcements</a>
            </div>
          </div>
          <div>
            <div class="bg-white rounded-lg shadow-lg p-6">
              <h3 class="text-xl font-semibold">Upcoming Event</h3>
              <p class="mt-2 text-gray-600">No events currently scheduled — create one to get started.</p>
              <div class="mt-4">
                <a href="create_event.php" class="text-green-700 hover:underline">Create event</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Features -->
    <section class="py-16 bg-green-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900">Features</h2>
        <p class="mt-2 text-gray-600">Everything you need to manage community announcements and events.</p>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900">Easy Posting</h3>
            <p class="mt-2 text-gray-600">Quickly create announcements with images and attachments.</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900">Event Management</h3>
            <p class="mt-2 text-gray-600">Schedule events, RSVP, and keep everyone informed.</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900">Access Control</h3>
            <p class="mt-2 text-gray-600">Roles and authentication to control who can post.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900">What people say</h2>
        <p class="mt-2 text-gray-600">Trusted by community organizers.</p>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-700">"This made coordinating our neighborhood so much easier."</p>
            <p class="mt-4 text-sm text-gray-500">— Alex</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-700">"Simple, clean, and reliable for posting events."</p>
            <p class="mt-4 text-sm text-gray-500">— Priya</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-700">"Our board uses it to share weekly announcements."</p>
            <p class="mt-4 text-sm text-gray-500">— Marcus</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA / Newsletter -->
    <section class="py-12 bg-gray-100">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-xl font-bold text-gray-900">Stay informed</h3>
        <p class="mt-2 text-gray-600">Subscribe for updates about new features and community events.</p>

        <form class="mt-6 flex items-center justify-center space-x-2" action="register.php" method="get">
          <input type="email" name="email" required placeholder="Your email" class="w-72 px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-600" />
          <button type="submit" class="px-4 py-2 bg-green-800 text-white rounded-md hover:bg-green-900">Subscribe</button>
        </form>
      </div>
    </section>

    <footer class="mt-20 bg-white border-t">
      <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 text-sm text-gray-500">
        © <?php echo date('Y'); ?> Community Announcement — Built with Tailwind
      </div>
    </footer>
  </body>
</html>
