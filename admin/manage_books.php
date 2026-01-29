<?php
require '../includes/db.php';
require '../includes/config.php';
include '../includes/header.php';

if ($_SESSION['level'] != 'admin') {
  echo "
    <div class='flex flex-col items-center justify-center min-h-[60vh] text-center'>
        <div class='bg-red-50 p-6 rounded-3xl border border-red-100'>
            <span class='text-4xl mb-4 inline-block'>🚫</span>
            <h2 class='text-xl font-bold text-gray-800'>Akses Terbatas</h2>
            <p class='text-gray-500'>Halaman ini hanya untuk administrator.</p>
        </div>
    </div>";
  include '../includes/footer.php';
  exit;
}

// Logic Tambah & Hapus tetap sama...
if (isset($_POST['tambah'])) {
  $judul = $_POST['judul'];
  $pengarang = $_POST['pengarang'];
  $tahun = $_POST['tahun'];
  $stok = $_POST['stok'];
  $stmt = $conn->prepare("INSERT INTO books (judul,pengarang,tahun,stok) VALUES (?,?,?,?)");
  $stmt->execute([$judul, $pengarang, $tahun, $stok]);
  header("Location: " . getBaseUrl() . "admin/manage_books.php");
}

if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
  $stmt->execute([$id]);
  header("Location: " . getBaseUrl() . "admin/manage_books.php");
}

$books = $conn->query("SELECT * FROM books ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$total_buku = count($books);
?>

<div class="max-w-7xl mx-auto px-4 py-8">

  <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
    <div>
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Buku</h1>
      <p class="text-gray-500 mt-1">Kelola koleksi pustaka, stok, dan data pengarang.</p>
    </div>
    <div class="flex gap-4">
      <div class="bg-blue-600 px-6 py-3 rounded-2xl text-white shadow-lg shadow-blue-100">
        <p class="text-xs opacity-80 uppercase font-bold tracking-wider">Total Koleksi</p>
        <p class="text-2xl font-bold"><?= $total_buku ?> <span class="text-sm font-normal">Judul</span></p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <div class="lg:col-span-1">
      <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-8">
        <h3 class="text-lg font-bold text-gray-800 mb-6 tracking-tight">Tambah Koleksi</h3>
        <form method="POST" class="space-y-4">
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Informasi Utama</label>
            <input type="text" name="judul" placeholder="Judul Buku" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none text-sm" required>
            <input type="text" name="pengarang" placeholder="Nama Pengarang" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none text-sm">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun</label>
              <input type="number" name="tahun" placeholder="2024" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none text-sm">
            </div>
            <div class="space-y-1">
              <label class="text-xs font-bold text-gray-500 uppercase ml-1">Stok</label>
              <input type="number" name="stok" placeholder="0" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none text-sm" required>
            </div>
          </div>

          <button type="submit" name="tambah" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 rounded-xl transition-all shadow-xl shadow-gray-200 flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Simpan Buku
          </button>
        </form>
      </div>
    </div>

    <div class="lg:col-span-3">
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Buku</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Tahun</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none text-center">Stok</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php foreach ($books as $b): ?>
                <tr class="hover:bg-blue-50/30 transition-colors group">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                      <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-500 transition-all shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-bold text-gray-900 leading-tight mb-1"><?= $b['judul'] ?></p>
                        <p class="text-xs text-gray-500 font-medium italic"><?= $b['pengarang'] ?: 'Tanpa Pengarang' ?></p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm text-gray-600 font-medium"><?= $b['tahun'] ?: '-' ?></span>
                  </td>
                  <td class="px-6 py-4 text-center">
                    <?php
                    $stokClass = ($b['stok'] <= 2) ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600';
                    ?>
                    <span class="px-3 py-1 rounded-full text-xs font-bold <?= $stokClass ?>">
                      <?= $b['stok'] ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                      <a href="?hapus=<?= $b['id'] ?>"
                        onclick="return confirm('Hapus buku ini dari katalog?')"
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                        title="Hapus Buku">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>