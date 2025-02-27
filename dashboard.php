<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
  <div class="bg-black/10 p-6 border-1 border-gray-300 rounded-sm max-w-md w-full">
    <div class="flex flex-col items-center">
      <img class="w-24 h-24 rounded-full border-2 border-gray-300" src="<?php echo $user['picture']; ?>" alt="Profile Picture">
      <h1 class="mt-4 text-2xl font-bold text-gray-800">Welcome, <?php echo $user['name']; ?>!</h1>
      <p class="text-gray-600"><?php echo $user['email']; ?></p>
    </div>
    <div class="mt-6 text-center">
      <a href="logout.php" class="inline-block px-6 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 transition duration-200">Logout</a>
    </div>
  </div>
</body>
</html>
