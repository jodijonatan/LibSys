<?php
require '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['level'] != 'admin') {
  echo "
    <div class='flex flex-col items-center justify-center min-h-[60vh]'>
        <div class='bg-orange-50 border border-orange-100 p-8 rounded-3xl text-center max-w-sm'>
            <div class='text-4xl mb-4'>🔒</div>
            <h2 class='text-xl font-bold text-gray-800'>Area Terbatas</h2>
            <p class='text-gray-500 mt-2'>Hanya admin yang memiliki izin untuk melihat riwayat transaksi.</p>
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

<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Log Aktivitas</h1>
      <p class="text-gray-500 mt-1">Pantau semua transaksi peminjaman dan pengembalian buku.</p>
    </div>
    <div class="flex gap-2">
      <button onclick="window.print()" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak Laporan
      </button>
    </div>
  </div>

  <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-50/50 border-b border-gray-100">
            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Peminjam</th>
            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Informasi Buku</th>
            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Timeline</th>
            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($records as $r): ?>
            <tr class="hover:bg-gray-50/50 transition-colors group">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-sm">
                    <?= strtoupper(substr($r['nama'], 0, 1)) ?>
                  </div>
                  <span class="font-semibold text-gray-800 text-sm"><?= $r['nama'] ?></span>
                </div>
              </td>

              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-gray-900 leading-none mb-1"><?= $r['judul'] ?></span>
                  <span class="text-[11px] text-gray-400 uppercase font-medium tracking-tight">ID Transaksi: #<?= rand(1000, 9999) ?></span>
                </div>
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-4 text-sm text-gray-600">
                  <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-blue-500 uppercase">Pinjam</span>
                    <span class="font-medium"><?= date('d M Y', strtotime($r['tgl_pinjam'])) ?></span>
                  </div>
                  <div class="h-8 w-[1px] bg-gray-100"></div>
                  <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-emerald-500 uppercase">Kembali</span>
                    <span class="font-medium italic">
                      <?= $r['tgl_kembali'] ? date('d M Y', strtotime($r['tgl_kembali'])) : '<span class="text-gray-300">Belum Ada</span>' ?>
                    </span>
                  </div>
                </div>
              </td>

              <td class="px-6 py-4 text-center">
                <?php
                $isReturned = strtolower($r['status']) == 'kembali';
                $statusStyles = $isReturned
                  ? 'bg-emerald-50 text-emerald-600 ring-emerald-500/20'
                  : 'bg-amber-50 text-amber-600 ring-amber-500/20';
                ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset <?= $statusStyles ?>">
                  <span class="w-1.5 h-1.5 rounded-full <?= $isReturned ? 'bg-emerald-600' : 'bg-amber-600' ?>"></span>
                  <?= ucfirst($r['status']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($records)): ?>
            <tr>
              <td colspan="4" class="px-6 py-20 text-center text-gray-400 italic">
                Belum ada riwayat peminjaman ditemukan.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>