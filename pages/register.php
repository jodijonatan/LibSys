<?php
session_start();
require '../includes/db.php';
require '../includes/config.php';

if (isset($_POST['register'])) {
  $nama     = $_POST['nama'];
  $email    = $_POST['email'];
  $password = $_POST['password'];
  $confirm  = $_POST['confirm_password'];
  $level    = 'anggota'; // Default level untuk pendaftaran mandiri

  // Validasi sederhana
  if ($password !== $confirm) {
    $error = "Konfirmasi password tidak cocok!";
  } else {
    // Cek apakah email sudah terdaftar
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->execute([$email]);

    if ($checkEmail->rowCount() > 0) {
      $error = "Email ini sudah digunakan!";
    } else {
      // Hash password untuk keamanan
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare("INSERT INTO users (nama, email, password, level) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$nama, $email, $hashedPassword, $level])) {
        $_SESSION['success'] = "Akun berhasil dibuat! Silakan login.";
        header("Location: " . getBaseUrl() . "login.php");
        exit;
      } else {
        $error = "Terjadi kesalahan saat mendaftar.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Baru - Perpustakaan Digital</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-[#f8fafc] flex items-center justify-center min-h-screen p-4">

  <div class="w-full max-w-[450px]">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-black text-gray-900 tracking-tight">Buat Akun</h1>
      <p class="text-gray-500 mt-2">Bergabunglah untuk mulai meminjam koleksi buku kami.</p>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100">

      <?php if (isset($error)): ?>
        <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-6 text-sm flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
          <input type="text" name="nama" required
            class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none placeholder:text-gray-400 text-sm"
            placeholder="Masukkan nama lengkap Anda">
        </div>

        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
          <input type="email" name="email" required
            class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none placeholder:text-gray-400 text-sm"
            placeholder="nama@email.com">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password</label>
            <input type="password" name="password" required
              class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-sm"
              placeholder="••••••••">
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi</label>
            <input type="password" name="confirm_password" required
              class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-sm"
              placeholder="••••••••">
          </div>
        </div>

        <div class="pt-2">
          <button type="submit" name="register"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-blue-200 transition-all transform active:scale-[0.98]">
            Daftar Sekarang
          </button>
        </div>
      </form>
    </div>

    <p class="text-center mt-8 text-sm text-gray-500">
      Sudah punya akun? <a href="<?= getBaseUrl() ?>login.php" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
    </p>
  </div>

</body>

</html>