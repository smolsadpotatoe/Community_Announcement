<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            $message = "Password updated successfully! You can now <a href='login.php' class='text-blue-600 underline'>login</a>.";
        } else {
            $message = "No user found with that email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Forgot Password</h2>
    <?php if ($message): ?>
      <p class="mb-4 text-green-600"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" class="w-full mb-4 px-4 py-2 border rounded" required>
      <input type="password" name="new_password" placeholder="New Password" class="w-full mb-4 px-4 py-2 border rounded" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full mb-4 px-4 py-2 border rounded" required>
      <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Change Password</button>
    </form>
    <p class="mt-4 text-sm text-center">Remembered your password? <a href="login.php" class="text-blue-600 underline">Login</a></p>
  </div>
</body>
</html>