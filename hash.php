<?php
// hash.php
if (isset($_POST['password'])) {
  $password = $_POST['password'];
  $hash = password_hash($password, PASSWORD_DEFAULT);
  echo "<p>Password asli: <b>$password</b></p>";
  echo "<p>Password hash: <b>$hash</b></p>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Password Hash</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h1 class="text-2xl font-bold mb-4 text-center">Password Hash Generator</h1>
    <form method="POST">
      <input type="text" name="password" placeholder="Masukkan password" class="w-full p-2 mb-4 border rounded" required>
      <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Generate Hash</button>
    </form>
  </div>
</body>

</html>