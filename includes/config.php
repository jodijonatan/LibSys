<?php
// Fungsi untuk mendapatkan base URL project (absolute path dari root)
function getBaseUrl() {
  $script_name = $_SERVER['SCRIPT_NAME'];
  // Ambil direktori dari script yang sedang dijalankan
  $script_dir = dirname($script_name);
  
  // Contoh kasus:
  // /perpustakaan/admin/manage_users.php -> script_dir = /perpustakaan/admin -> base = /perpustakaan/
  // /perpustakaan/dashboard.php -> script_dir = /perpustakaan -> base = /perpustakaan/
  // /dashboard.php -> script_dir = / -> base = /
  
  // Normalisasi: pastikan tidak ada trailing slash di awal
  $script_dir = rtrim($script_dir, '/');
  if (empty($script_dir)) {
    return '/';
  }
  
  // Jika script_dir mengandung subdirectory (admin, anggota, petugas, pages)
  // Maka base adalah parent directory
  $subdirs = ['/admin', '/anggota', '/petugas', '/pages'];
  foreach ($subdirs as $subdir) {
    if (strpos($script_dir, $subdir) !== false) {
      // Hapus subdirectory dan semua yang setelahnya
      $pos = strpos($script_dir, $subdir);
      $base = substr($script_dir, 0, $pos);
      return rtrim($base, '/') . '/';
    }
  }
  
  // Jika tidak ada subdirectory, berarti script_dir adalah base
  return $script_dir . '/';
}
?>
