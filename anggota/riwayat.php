<?php
require '../includes/db.php';
include '../includes/header.php';

$stmt = $conn->prepare("
    SELECT b.judul, b.pengarang, p.tgl_pinjam, p.tgl_kembali, p.status
    FROM peminjaman p
    JOIN books b ON p.book_id = b.id
    WHERE p.user_id = ?
    ORDER BY p.tgl_pinjam DESC
");
$stmt->execute([$_SESSION['user_id']]);
$peminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung ringkasan untuk Anggota
$total_pinjam = count($peminjaman);
$sedang_dipinjam = count(array_filter($peminjaman, fn($i) => $i['status'] == 'dipinjam'));
?>

<div class="max-w-6xl mx-auto px-4 py-10">

  <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Halo, <?= explode(' ', $_SESSION['nama'])[0] ?>! 👋</h1>
      <p class="text-gray-500 mt-1 font-medium">Ini adalah daftar buku yang pernah dan sedang kamu baca.</p>
    </div>

    <div class="flex gap-4">
      <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm flex items-center gap-4">
        <div class="bg-blue-50 text-blue-600 p-3 rounded-xl text-xl">📚</div>
        <div>
          <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Baca</p>
          <p class="text-xl font-black text-gray-800"><?= $total_pinjam ?></p>
        </div>
      </div>
      <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm flex items-center gap-4">
        <div class="bg-amber-50 text-amber-600 p-3 rounded-xl text-xl">📖</div>
        <div>
          <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Aktif</p>
          <p class="text-xl font-black text-gray-800"><?= $sedang_dipinjam ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50/50 border-b border-gray-100">
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Informasi Buku</th>
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Waktu Pinjam</th>
            <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none text-center">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php if (empty($peminjaman)): ?>
            <tr>
              <td colspan="3" class="px-8 py-20 text-center">
                <div class="max-w-xs mx-auto">
                  <p class="text-4xl mb-4">📭</p>
                  <p class="text-gray-800 font-bold italic">Belum ada riwayat bacaan.</p>
                  <p class="text-gray-400 text-sm mt-1">Ayo mulai cari buku menarik di katalog!</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>

          <?php foreach ($peminjaman as $row): ?>
            <tr class="hover:bg-blue-50/20 transition-all duration-300">
              <td class="px-8 py-6">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-gray-900 mb-1"><?= $row['judul'] ?></span>
                  <span class="text-xs text-gray-400"><?= $row['pengarang'] ?? 'Pengarang tidak diketahui' ?></span>
                </div>
              </td>
              <td class="px-8 py-6">
                <div class="flex items-center gap-3 text-sm">
                  <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Mulai</span>
                    <span class="text-gray-700 font-medium"><?= date('d M Y', strtotime($row['tgl_pinjam'])) ?></span>
                  </div>
                  <div class="text-gray-200">→</div>
                  <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Selesai</span>
                    <span class="text-gray-700 font-medium italic">
                      <?= $row['tgl_kembali'] ? date('d M Y', strtotime($row['tgl_kembali'])) : 'Masih dibaca' ?>
                    </span>
                  </div>
                </div>
              </td>
              <td class="px-8 py-6 text-center">
                <?php
                $status = strtolower($row['status']);
                $isDone = ($status == 'kembali');
                ?>
                <span class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider inline-flex items-center gap-2
                                    <?= $isDone ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700 ring-4 ring-blue-50' ?>">
                  <span class="w-1.5 h-1.5 rounded-full <?= $isDone ? 'bg-emerald-500' : 'bg-blue-500 animate-pulse' ?>"></span>
                  <?= $status ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>