<?php
require 'config.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="max-w-4xl w-full p-8">
    <div class="bg-white rounded-2xl shadow-2xl p-10 md:p-16 relative overflow-hidden">
      <header class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-green-700 flex items-center justify-center text-white font-bold">VSU</div>
          <div class="text-sm font-semibold text-gray-700">VISAYAS STATE<br/>UNIVERSITY</div>
        </div>
        <nav class="text-sm text-gray-600 hidden md:flex gap-6">
          <a href="#" class="hover:text-gray-800">HOME</a>
          <a href="#" class="hover:text-gray-800">ABOUT</a>
        </nav>
      </header>

      <div class="md:flex md:items-center md:justify-between">
        <div class="md:w-1/2 text-center md:text-left">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-green-800 leading-tight">COMMUNITY BULLETIN<br/>BOARD SYSTEM</h1>
        </div>

        <div class="md:w-1/2 mt-8 md:mt-0 flex items-center justify-center">
          <div class="w-full max-w-md">
            <?php if ($message): ?>
              <p class="mb-4 text-red-600 text-center"><?= $message ?></p>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
              <div>
                <input type="text" name="username" placeholder="Username" class="w-full px-6 py-4 rounded-xl bg-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
              </div>
              <div>
                <input type="password" name="password" placeholder="Password" class="w-full px-6 py-4 rounded-xl bg-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
              </div>
              <div>
                <button type="submit" class="w-full bg-green-800 text-white py-4 rounded-xl font-semibold hover:bg-green-900">LOGIN</button>
              </div>
              <div class="text-center mt-2">
                <a href="#" class="text-sm text-gray-600 hover:underline">Forgot password?</a>
              </div>
              <div class="flex items-center justify-center gap-4 mt-4">
                <span class="text-sm text-gray-700">Don't have an account?</span>
                <a href="register.php" class="px-4 py-2 bg-yellow-400 text-sm font-semibold rounded-full hover:bg-yellow-500">REGISTER</a>
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