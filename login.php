<?php
require 'config.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        // Prepared Statement: prevents SQL Injection
        $stmt = $pdo->prepare("SELECT user_id, username, password, role FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Password Hashing: Securely verify the plain text password against the stored hash
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            // Keep error message vague for security
            $message = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $message = "An error occurred. Please try again later.";
        // Log the actual error for debugging, but don't show it to the user
        error_log($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Community Bulletin Board</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="max-w-4xl w-full p-8">
    <div class="bg-white rounded-2xl shadow-2xl p-10 md:p-16 relative overflow-hidden">
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-green-700 flex items-center justify-center text-white font-bold">VSU</div>
          <div class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Visayas State<br/>University</div>
        </div>
        <nav class="text-sm text-gray-600 hidden md:flex gap-6 font-medium">
          <a href="#" class="hover:text-green-700 transition-colors">HOME</a>
          <a href="#" class="hover:text-green-700 transition-colors">ABOUT</a>
        </nav>
      </header>

      <div class="md:flex md:items-center md:justify-between gap-12">
        <div class="md:w-1/2 text-center md:text-left">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-green-800 leading-tight">
            COMMUNITY <br class="hidden md:block">BULLETIN BOARD
          </h1>
          <p class="mt-4 text-gray-600 font-medium">Stay updated with the latest campus events and announcements.</p>
        </div>

        <div class="md:w-1/2 mt-8 md:mt-0 flex items-center justify-center">
          <div class="w-full max-w-md">
            <?php if ($message): ?>
              <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                <?= htmlspecialchars($message) ?>
              </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Username</label>
                <input type="text" name="username" placeholder="Enter your username" 
                       class="w-full px-6 py-4 rounded-xl bg-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-600 focus:bg-white transition-all" required>
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Password</label>
                <input type="password" name="password" placeholder="Enter your password" 
                       class="w-full px-6 py-4 rounded-xl bg-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-600 focus:bg-white transition-all" required>
              </div>
              <button type="submit" class="w-full bg-green-800 text-white py-4 rounded-xl font-bold tracking-wide hover:bg-green-900 shadow-lg shadow-green-900/20 transform transition-transform active:scale-[0.98]">
                LOGIN
              </button>
              
              <div class="text-center mt-4">
                <a href="#" class="text-xs text-gray-500 hover:text-green-700 transition-colors">Forgot password?</a>
              </div>
              
              <div class="flex items-center justify-center gap-4 mt-6 pt-6 border-t border-gray-100">
                <span class="text-sm text-gray-600">Don't have an account?</span>
                <a href="register.php" class="px-5 py-2 bg-yellow-400 text-xs font-bold text-yellow-900 rounded-full hover:bg-yellow-500 transition-all uppercase tracking-tighter">Register Now</a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-green-50 rounded-full opacity-50 pointer-events-none"></div>
    </div>
  </div>
</body>
</html>