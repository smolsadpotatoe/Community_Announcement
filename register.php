<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    // Hash password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $hashedPassword, $full_name, $email]);
        $message = "Registration successful! You can now <a href='login.php' class='text-blue-600 underline'>login</a>.";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
    <?php if ($message): ?>
      <p class="mb-4 text-green-600"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" class="w-full mb-4 px-4 py-2 border rounded" required>
      <input type="password" name="password" placeholder="Password" class="w-full mb-4 px-4 py-2 border rounded" required>
      <input type="text" name="full_name" placeholder="Full Name" class="w-full mb-4 px-4 py-2 border rounded">
      <input type="email" name="email" placeholder="Email" class="w-full mb-4 px-4 py-2 border rounded">
      <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">REGISTER</button>
    </form>
    <p class="mt-4 text-sm text-center">Already have an account? <a href="login.php" class="text-blue-600 underline">Login</a></p>
  </div>
</body>
</html>