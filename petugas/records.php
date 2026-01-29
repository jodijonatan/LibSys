<?php
require '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['level'] != 'petugas') {
  echo "
    <div class='flex flex-col items-center justify-center min-h-[50vh] animate-pulse'>
        <div class='bg-amber-50 border-2 border-amber-100 p-10 rounded-[3rem] text-center'>
            <div class='text-6xl mb-4'>🚧</div>
            <h2 class='text-2xl font-black text-amber-800 tracking-tight'>Akses Terbatas</h2>
            <p class='text-amber-700/60 mt-2 font-medium'>Halaman ini hanya untuk modul Petugas Operasional.</p>
        </div>
    </div>";
  include '../includes/footer.php';
  exit;
}

$records = $conn->query("
    SELECT u.nama, b.judul, p.tgl_pinjam, p.tgl_kembali, p.status
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN books b ON p.book_id = b.id
    ORDER BY p.tgl_pinjam DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-7xl mx-auto px-6 py-10">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
    <div>
      <span class="text-blue-600 font-bold text-sm tracking-[0.2em] uppercase mb-2 block">Laporan Sistem</span>
      <h1 class="text-4xl font-black text-gray-900 tracking-tight">Record Peminjaman</h1>
      <p class="text-gray-500 mt-2 text-lg">Arsip lengkap transaksi keluar masuk koleksi buku.</p>
    </div>

    <div class="bg-white border border-gray-100 p-2 rounded-[2rem] flex items-center gap-1 shadow-sm">
      <button onclick="window.print()" class="px-6 py-3 bg-gray-900 hover:bg-black text-white rounded-[1.5rem] font-bold text-sm transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Export PDF / Print
      </button>
    </div>
  </div>

  <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-50/80 border-b border-gray-100">
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Identitas Peminjam</th>
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Koleksi Buku</th>
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Periode Sessi</th>
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($records as $r): ?>
            <tr class="group hover:bg-blue-50/30 transition-all duration-300">
              <td class="px-8 py-6">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100">
                    <?= strtoupper(substr($r['nama'], 0, 1)) ?>
                  </div>
                  <div class="flex flex-col">
                    <span class="text-sm font-black text-gray-900"><?= $r['nama'] ?></span>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Anggota Aktif</span>
                  </div>
                </div>
              </td>

              <td class="px-8 py-6">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-gray-800 group-hover:text-blue-700 transition-colors"><?= $r['judul'] ?></span>
                  <span class="text-xs text-gray-400 italic font-medium">Katalog Pustaka</span>
                </div>
              </td>

              <td class="px-8 py-6">
                <div class="flex items-center gap-3">
                  <div class="text-center">
                    <p class="text-[9px] font-black text-blue-500 uppercase tracking-tighter mb-0.5">Check-out</p>
                    <p class="text-xs font-bold text-gray-700"><?= date('d/m/y', strtotime($r['tgl_pinjam'])) ?></p>
                  </div>
                  <div class="h-6 w-px bg-gray-200"></div>
                  <div class="text-center">
                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter mb-0.5">Check-in</p>
                    <p class="text-xs font-bold text-gray-700">
                      <?= $r['tgl_kembali'] ? date('d/m/y', strtotime($r['tgl_kembali'])) : '<span class="text-gray-300">—</span>' ?>
                    </p>
                  </div>
                </div>
              </td>

              <td class="px-8 py-6 text-center">
                <?php
                $status = strtolower($r['status']);
                $color = ($status == 'kembali') ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700';
                ?>
                <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] shadow-sm <?= $color ?>">
                  <?= $status ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($records)): ?>
            <tr>
              <td colspan="4" class="px-8 py-20 text-center">
                <p class="text-gray-400 font-medium italic">Tidak ada rekaman data peminjaman saat ini.</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>