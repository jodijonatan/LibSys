<?php
require '../includes/db.php';
require '../includes/config.php';
include '../includes/header.php';

// Proteksi Halaman
if ($_SESSION['level'] != 'admin') {
  echo "
    <div class='flex items-center justify-center min-h-[60vh]'>
        <div class='text-center p-8 bg-red-50 rounded-2xl border border-red-100'>
            <div class='text-red-500 mb-4 inline-block p-4 bg-white rounded-full shadow-sm'>
                <svg xmlns='http://www.w3.org/2000/svg' class='h-12 w-12' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' />
                </svg>
            </div>
            <h2 class='text-xl font-bold text-gray-800'>Akses Ditolak</h2>
            <p class='text-gray-600 mt-2'>Maaf, hanya Admin yang dapat mengakses halaman ini.</p>
        </div>
    </div>";
  include '../includes/footer.php';
  exit;
}

// Logic Tambah User (Tetap Sama)
if (isset($_POST['tambah'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $level = $_POST['level'];

  $stmt = $conn->prepare("INSERT INTO users (nama,email,password,level) VALUES (?,?,?,?)");
  $stmt->execute([$nama, $email, $password, $level]);
  header("Location: " . getBaseUrl() . "admin/manage_users.php");
}

// Logic Hapus User (Tetap Sama)
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  if ($id != $_SESSION['user_id']) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . getBaseUrl() . "admin/manage_users.php");
  }
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-6xl mx-auto px-4 py-8">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Pengguna</h1>
      <p class="text-gray-500 mt-1">Manajemen akses dan hak suara anggota perpustakaan.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
      <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-8">
        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
          <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </span>
          Tambah User
        </h2>

        <form method="POST" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" name="email" placeholder="contoh@email.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Level Akses</label>
            <select name="level" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none appearance-none" required>
              <option value="anggota">Anggota</option>
              <option value="petugas">Petugas</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <button type="submit" name="tambah" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-100">
            Simpan User
          </button>
        </form>
      </div>
    </div>

    <div class="lg:col-span-2">
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Level</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php foreach ($users as $u): ?>
                <tr class="hover:bg-gray-50/80 transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-blue-600 font-bold">
                        <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                      </div>
                      <div>
                        <div class="text-sm font-bold text-gray-900"><?= $u['nama'] ?></div>
                        <div class="text-xs text-gray-500"><?= $u['email'] ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <?php
                    $color = $u['level'] == 'admin' ? 'bg-purple-100 text-purple-600' : ($u['level'] == 'petugas' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600');
                    ?>
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide <?= $color ?>">
                      <?= $u['level'] ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                      <a href="?hapus=<?= $u['id'] ?>"
                        onclick="return confirm('Yakin ingin menghapus user ini?')"
                        class="inline-flex items-center justify-center w-9 h-9 text-red-500 hover:bg-red-50 rounded-xl transition-colors"
                        title="Hapus User">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </a>
                    <?php else: ?>
                      <span class="text-xs italic text-gray-400">Anda</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php if (empty($users)): ?>
          <div class="p-10 text-center text-gray-400 text-sm">Belum ada data user.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>