<?php
session_start();
require_once __DIR__ . '/config.php';
$base_url = getBaseUrl();

if (!isset($_SESSION['user_id'])) {
  header("Location: " . $base_url . "login.php");
  exit;
}
$level = $_SESSION['level'];
$nama = $_SESSION['nama'];
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LibSys - Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #f8fafc;
    }

    .sidebar-link.active {
      background-color: #eff6ff;
      color: #2563eb;
      font-weight: 700;
      border-right: 4px solid #2563eb;
    }
  </style>
</head>

<body class="antialiased text-gray-800">

  <div class="flex min-h-screen">
    <aside class="w-72 bg-white border-r border-gray-100 hidden lg:flex flex-col sticky top-0 h-screen">
      <div class="p-8">
        <div class="flex items-center gap-3 mb-10">
          <div class="w-10 h-10 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 flex items-center justify-center text-white font-black text-xl">L</div>
          <span class="font-black text-2xl tracking-tighter text-gray-900">LibSys.</span>
        </div>

        <nav class="space-y-1.5">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-4">Main Menu</p>

          <a href="<?= $base_url ?>dashboard.php" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 transition-all <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
          </a>

          <?php if ($level == 'admin'): ?>
            <a href="<?= $base_url ?>admin/manage_users.php" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 transition-all <?= $current_page == 'manage_users.php' ? 'active' : '' ?>">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              Kelola User
            </a>
            <a href="<?= $base_url ?>admin/manage_books.php" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 transition-all <?= $current_page == 'manage_books.php' ? 'active' : '' ?>">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              Data Buku
            </a>
          <?php endif; ?>

          <?php if ($level == 'petugas'): ?>
            <a href="<?= $base_url ?>petugas/peminjaman.php" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 transition-all <?= $current_page == 'peminjaman.php' ? 'active' : '' ?>">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
              Transaksi Baru
            </a>
            <a href="<?= $base_url ?>petugas/records.php" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 transition-all <?= $current_page == 'records.php' ? 'active' : '' ?>">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              Log Peminjaman
            </a>
          <?php endif; ?>

          <?php if ($level == 'anggota'): ?>
            <a href="<?= $base_url ?>anggota/riwayat.php" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-500 hover:bg-gray-50 transition-all <?= $current_page == 'riwayat.php' ? 'active' : '' ?>">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Riwayat Saya
            </a>
          <?php endif; ?>
        </nav>
      </div>

      <div class="mt-auto p-8 border-t border-gray-50">
        <a href="<?= $base_url ?>logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-bold transition-all italic">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Sign Out
        </a>
      </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-30">
        <button class="lg:hidden p-2 text-gray-600 bg-gray-50 rounded-lg">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>

        <div class="ml-auto flex items-center gap-4">
          <div class="text-right hidden sm:block">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest leading-none mb-1"><?= $level ?></p>
            <p class="text-sm font-bold text-gray-900 leading-none"><?= $nama ?></p>
          </div>
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100 uppercase">
            <?= substr($nama, 0, 1) ?>
          </div>
        </div>
      </header>

      <main class="p-4 md:p-8 lg:p-12 overflow-y-auto">