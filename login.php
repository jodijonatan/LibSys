<?php
session_start();
require 'includes/db.php';
require 'includes/config.php';

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['level']   = $user['level'];
    $_SESSION['nama']    = $user['nama'];

    header("Location: " . getBaseUrl() . "dashboard.php");
    exit;
  } else {
    $error = "Email atau password yang Anda masukkan salah.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk ke Perpustakaan Digital</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-[#f8fafc] flex items-center justify-center min-h-screen p-4">

  <div class="w-full max-w-[400px]">

    <div class="text-center mb-8">
      <div class="bg-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="Vector D 12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900">Selamat Datang</h1>
      <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk mengakses koleksi buku.</p>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">

      <?php if (isset($error)): ?>
        <div class="flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-3 rounded-xl mb-6 text-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Alamat Email</label>
          <input type="email" name="email" required
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none placeholder:text-gray-400 text-sm"
            placeholder="nama@email.com">
        </div>

        <div>
          <div class="flex justify-between mb-1.5 ml-1">
            <label class="text-sm font-medium text-gray-700">Kata Sandi</label>
            <a href="#" class="text-xs text-blue-600 hover:underline">Lupa password?</a>
          </div>
          <input type="password" name="password" required
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none placeholder:text-gray-400 text-sm"
            placeholder="••••••••">
        </div>

        <div class="flex items-center mb-2 ml-1">
          <input type="checkbox" id="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
          <label for="remember" class="ml-2 text-sm text-gray-600">Ingat perangkat ini</label>
        </div>

        <button type="submit" name="login"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98]">
          Masuk Sekarang
        </button>
      </form>
    </div>

    <p class="text-center mt-8 text-sm text-gray-500">
      Belum punya akun? <a href="<?= getBaseUrl() ?>pages/register.php" class="text-blue-600 font-semibold hover:underline">Daftar gratis</a>
    </p>
  </div>

</body>

</html>