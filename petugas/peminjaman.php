<?php
require '../includes/db.php';
require '../includes/config.php';

// Process redirects before any output
if (isset($_POST['pinjam'])) {
  $user_id = $_POST['user_id'];
  $book_id = $_POST['book_id'];
  $tgl_pinjam = date('Y-m-d');
  $stmt = $conn->prepare("INSERT INTO peminjaman (user_id,book_id,tgl_pinjam,status) VALUES (?,?,?,?)");
  $stmt->execute([$user_id, $book_id, $tgl_pinjam, 'dipinjam']);
  $stmt2 = $conn->prepare("UPDATE books SET stok = stok - 1 WHERE id = ?");
  $stmt2->execute([$book_id]);
  header("Location: " . getBaseUrl() . "petugas/peminjaman.php");
  exit;
}

if (isset($_GET['kembali'])) {
  $id = $_GET['kembali'];
  $stmt = $conn->prepare("UPDATE peminjaman SET tgl_kembali=?, status='kembali' WHERE id=?");
  $stmt->execute([date('Y-m-d'), $id]);
  $stmt2 = $conn->prepare("UPDATE books b JOIN peminjaman p ON b.id=p.book_id SET b.stok=b.stok+1 WHERE p.id=?");
  $stmt2->execute([$id]);
  header("Location: " . getBaseUrl() . "petugas/peminjaman.php");
  exit;
}

include '../includes/header.php';

// Proteksi Petugas
if ($_SESSION['level'] != 'petugas') {
  echo "
    <div class='flex flex-col items-center justify-center min-h-[60vh]'>
        <div class='bg-red-50 p-8 rounded-[2rem] border border-red-100 text-center'>
            <span class='text-5xl mb-4 inline-block'>⚠️</span>
            <h2 class='text-xl font-bold text-red-800'>Akses Petugas Diperlukan</h2>
            <p class='text-red-600/70 mt-1'>Maaf, Anda tidak memiliki otoritas untuk memproses peminjaman.</p>
        </div>
    </div>";
  include '../includes/footer.php';
  exit;
}

// Load data after header inclusion
$anggota = $conn->query("SELECT * FROM users WHERE level='anggota'")->fetchAll(PDO::FETCH_ASSOC);
$books = $conn->query("SELECT * FROM books WHERE stok > 0")->fetchAll(PDO::FETCH_ASSOC);

$peminjaman = $conn->query("
    SELECT p.id, u.nama, b.judul, p.tgl_pinjam, p.tgl_kembali, p.status
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN books b ON p.book_id = b.id
    ORDER BY p.tgl_pinjam DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-7xl mx-auto px-4 py-8">

  <div class="mb-10 text-center md:text-left">
    <h1 class="text-4xl font-black text-gray-900 tracking-tight">Sirkulasi Buku</h1>
    <p class="text-gray-500 font-medium">Catat peminjaman baru dan kelola pengembalian koleksi.</p>
  </div>

  <div class="bg-blue-600 rounded-[2.5rem] p-8 shadow-2xl shadow-blue-200 mb-12 relative overflow-hidden">
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-blue-500 rounded-full opacity-50"></div>

    <h2 class="text-white text-xl font-bold mb-6 flex items-center gap-2 relative z-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Transaksi Pinjam Baru
    </h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 relative z-10">
      <div class="relative">
        <select name="user_id" class="w-full bg-white/10 border border-white/20 text-white placeholder:text-blue-100 rounded-2xl px-5 py-4 outline-none focus:ring-4 focus:ring-white/10 transition-all appearance-none cursor-pointer" required>
          <option value="" class="text-gray-900">Pilih Anggota...</option>
          <?php foreach ($anggota as $a): ?>
            <option value="<?= $a['id'] ?>" class="text-gray-900"><?= $a['nama'] ?></option>
          <?php endforeach; ?>
        </select>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <div class="relative">
        <select name="book_id" class="w-full bg-white/10 border border-white/20 text-white rounded-2xl px-5 py-4 outline-none focus:ring-4 focus:ring-white/10 transition-all appearance-none cursor-pointer" required>
          <option value="" class="text-gray-900">Pilih Buku...</option>
          <?php foreach ($books as $b): ?>
            <option value="<?= $b['id'] ?>" class="text-gray-900"><?= $b['judul'] ?> (Stok: <?= $b['stok'] ?>)</option>
          <?php endforeach; ?>
        </select>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <button type="submit" name="pinjam" class="bg-white text-blue-600 font-black py-4 rounded-2xl hover:bg-blue-50 transition-all transform active:scale-95 shadow-xl shadow-blue-800/20">
        PROSES PINJAM
      </button>
    </form>
  </div>

  <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-400 font-bold text-xs uppercase tracking-[0.2em]">
            <th class="px-8 py-5">Peminjam & Buku</th>
            <th class="px-8 py-5">Tanggal</th>
            <th class="px-8 py-5">Status</th>
            <th class="px-8 py-5 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($peminjaman as $p): ?>
            <tr class="hover:bg-blue-50/20 transition-all group">
              <td class="px-8 py-5">
                <div class="flex flex-col">
                  <span class="font-bold text-gray-800 text-sm mb-0.5"><?= $p['nama'] ?></span>
                  <span class="text-xs text-blue-600 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <?= $p['judul'] ?>
                  </span>
                </div>
              </td>
              <td class="px-8 py-5">
                <div class="text-xs font-semibold text-gray-600 flex flex-col gap-1">
                  <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <?= date('d M Y', strtotime($p['tgl_pinjam'])) ?>
                  </span>
                  <?php if ($p['tgl_kembali']): ?>
                    <span class="flex items-center gap-2 text-emerald-500">
                      <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                      <?= date('d M Y', strtotime($p['tgl_kembali'])) ?>
                    </span>
                  <?php endif; ?>
                </div>
              </td>
              <td class="px-8 py-5">
                <?php
                $statusClass = $p['status'] == 'dipinjam'
                  ? 'bg-amber-100 text-amber-700'
                  : 'bg-emerald-100 text-emerald-700';
                ?>
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider <?= $statusClass ?>">
                  <?= $p['status'] ?>
                </span>
              </td>
              <td class="px-8 py-5 text-right">
                <?php if ($p['status'] == 'dipinjam'): ?>
                  <a href="?kembali=<?= $p['id'] ?>"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-emerald-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    KEMBALIKAN
                  </a>
                <?php else: ?>
                  <span class="text-gray-300 italic text-xs">Selesai</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>