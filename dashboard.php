<?php
require 'includes/db.php';
include 'includes/header.php';

$nama = $_SESSION['nama'];
$level = $_SESSION['level'];

// Ambil Data Statistik secara Dinamis
$total_buku = $conn->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_pinjam = $conn->query("SELECT COUNT(*) FROM peminjaman WHERE status='dipinjam'")->fetchColumn();
$total_users = $conn->query("SELECT COUNT(*) FROM users WHERE level='anggota'")->fetchColumn();

// Ambil Aktivitas Terbaru (Log Peminjaman Terakhir)
$recent_activities = $conn->query("
    SELECT u.nama, b.judul, p.tgl_pinjam 
    FROM peminjaman p 
    JOIN users u ON p.user_id = u.id 
    JOIN books b ON p.book_id = b.id 
    ORDER BY p.id DESC LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="space-y-8">
  <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
    <div class="relative z-10">
      <h1 class="text-3xl font-black text-gray-900 tracking-tight">Selamat Datang, <?= explode(' ', $nama)[0] ?>! ✨</h1>
      <p class="text-gray-500 mt-2 font-medium">Sistem manajemen perpustakaan siap digunakan. Apa agenda Anda hari ini?</p>

      <div class="flex gap-3 mt-6">
        <?php 
        require_once 'includes/config.php';
        $base_url = getBaseUrl();
        ?>
        <?php if ($level == 'petugas'): ?>
          <a href="<?= $base_url ?>petugas/peminjaman.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 italic">Input Pinjaman Baru</a>
        <?php elseif ($level == 'admin'): ?>
          <a href="<?= $base_url ?>admin/manage_users.php" class="bg-gray-900 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-black transition-all shadow-lg shadow-gray-100 italic">Kelola Akses Pengguna</a>
        <?php else: ?>
          <a href="<?= $base_url ?>anggota/riwayat.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-blue-700 transition-all italic">Cek Riwayat Saya</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="hidden md:block opacity-10 transform scale-150 rotate-12 absolute -right-4">
      <svg class="w-64 h-64 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9 4.804A7.995 7.995 0 0111 4v12a7.995 7.995 0 00-2 .804V4.804zm3 0A7.995 7.995 0 0114 4v12a7.995 7.995 0 00-2 .804V4.804zm-3 0V16a7.995 7.995 0 00-2-.804V4.804A7.995 7.995 0 019 4.804z"></path>
      </svg>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 flex items-center gap-5 shadow-sm group hover:border-blue-200 transition-all">
      <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">📚</div>
      <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Koleksi Buku</p>
        <h3 class="text-2xl font-black text-gray-900"><?= $total_buku ?> <span class="text-xs font-normal text-gray-400">Judul</span></h3>
      </div>
    </div>
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 flex items-center gap-5 shadow-sm group hover:border-amber-200 transition-all">
      <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 text-2xl group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">📖</div>
      <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sirkulasi Aktif</p>
        <h3 class="text-2xl font-black text-gray-900"><?= $total_pinjam ?> <span class="text-xs font-normal text-gray-400">Dipinjam</span></h3>
      </div>
    </div>
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 flex items-center gap-5 shadow-sm group hover:border-emerald-200 transition-all">
      <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">👥</div>
      <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Anggota</p>
        <h3 class="text-2xl font-black text-gray-900"><?= $total_users ?> <span class="text-xs font-normal text-gray-400">Orang</span></h3>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
      <h3 class="text-xl font-black text-gray-900 mb-6">Aktivitas Terkini</h3>
      <div class="space-y-6">
        <?php foreach ($recent_activities as $act): ?>
          <div class="flex items-center justify-between group">
            <div class="flex items-center gap-4">
              <div class="w-2 h-2 rounded-full bg-blue-500"></div>
              <div>
                <p class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition-colors"><?= $act['nama'] ?></p>
                <p class="text-xs text-gray-400 italic">Meminjam "<?= $act['judul'] ?>"</p>
              </div>
            </div>
            <span class="text-[10px] font-bold text-gray-300 uppercase"><?= date('H:i', strtotime($act['tgl_pinjam'])) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-100">
      <div class="relative z-10 h-full flex flex-col justify-between">
        <h3 class="text-xl font-bold italic leading-tight">"Buku adalah jendela dunia, dan Anda adalah kuncinya."</h3>
        <div class="mt-10">
          <p class="text-blue-100 text-xs font-medium opacity-80 mb-4 tracking-wide uppercase">Peringatan Hari Ini</p>
          <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
            <p class="text-xs italic leading-relaxed">Cek daftar pengembalian yang terlambat untuk menjaga ketersediaan stok buku tetap stabil.</p>
          </div>
        </div>
      </div>
      <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>