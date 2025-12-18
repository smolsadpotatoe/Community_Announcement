<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About - Community Bulletin Board System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: #f0fdf4;
    }
    .brand-bg {
      background:rgb(235, 245, 240);
    }
    .brand-accent {
      color:hsl(123, 71.70%, 19.40%);
    }
    .brand-btn {
      background:rgb(9, 116, 35);
      color: #fff;
    }
    .brand-btn:hover {
      background:rgb(10, 99, 32);
    }
    .brand-curve {
      border-radius: 2.5rem;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center brand-bg">
  <div class="w-full max-w-4xl mx-auto shadow-xl brand-curve overflow-hidden relative" style="background:rgb(247, 247, 247);">
    <div class="absolute right-0 top-0 h-full w-1/3 flex items-end justify-end">
      <svg width="100%" height="100%" viewBox="0 0 300 400" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M200 0 Q300 200 200 400 Q100 200 200 0" fill="#047857" fill-opacity="0.8"/>
      </svg>
    </div>
    <div class="p-10 relative z-10">
      <div class="flex items-center justify-between mb-8">
        <div class="text-3xl font-extrabold brand-accent">COMMUNITY BULLETIN</div>
        <a href="login.php" class="px-4 py-2 rounded-full brand-btn font-semibold shadow">Sign in</a>
      </div>
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">Connecting People, Sharing Announcements, <span class="brand-accent">Building Community</span></h1>
      <p class="text-lg text-gray-700 mb-8 max-w-2xl">Our Community Bulletin Board System is designed to bring people together, foster communication, and keep everyone informed. We believe in the power of connection and the importance of sharing news, events, and opportunities in a safe, welcoming space.</p>
      <div class="mb-8">
        <span class="inline-block px-4 py-2 bg-green-700 text-white rounded-full text-lg font-semibold shadow">"Bringing the community closer, one post at a time."</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h2 class="text-xl font-bold mb-2 brand-accent">Contact Us</h2>
          <p class="text-gray-700 mb-1">Email: <a href="mailto:community@yourdomain.com" class="underline text-green-800">community@yourdomain.com</a></p>
          <p class="text-gray-700 mb-1">Phone: <a href="tel:+1234567890" class="underline text-green-800">+1 234 567 890</a></p>
          <p class="text-gray-700">Address: 123 Community Lane, Isabel, Australia</p>
        </div>
        
      </div>
    </div>
  </div>
</body>
</html>
