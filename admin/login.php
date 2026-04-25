<?php
session_start();
require_once '../config/db.php';

if(isAdmin()) redirect('../admin/dashboard.php');

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if($username && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['nama_lengkap'] ?? $user['username'];
            redirect('../admin/dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Username dan password wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SID Desa Darmakradenan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif']}}}}</script>
    <style>
        body{font-family:'Plus Jakarta Sans',sans-serif;}
        .hero-bg{background:linear-gradient(135deg,#064e3b 0%,#047857 50%,#059669 100%);}
    </style>
</head>
<body class="min-h-screen hero-bg flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-landmark text-white text-3xl"></i>
            </div>
            <h1 class="text-white font-extrabold text-xl">SID Desa Darmakradenan</h1>
            <p class="text-green-200 text-sm mt-1">Panel Admin Desa</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-extrabold text-gray-800 mb-1">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mb-6">Masuk untuk mengelola data desa</p>

            <?php if($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="username" 
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               placeholder="Username admin"
                               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                               autofocus required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" name="password" id="pass"
                               placeholder="Password"
                               class="w-full border border-gray-200 rounded-xl pl-10 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                               required>
                        <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-700 to-green-600 hover:from-green-800 hover:to-green-700 text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 transition mt-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-gray-100 text-center text-xs text-gray-400">
                <a href="../index.php" class="text-green-600 hover:underline flex items-center justify-center gap-1">
                    <i class="fas fa-arrow-left"></i> Kembali ke halaman utama
                </a>
            </div>
        </div>

        <p class="text-center text-green-200 text-xs mt-6">
            Default: admin / password (ubah setelah login pertama)
        </p>
    </div>

    <script>
    function togglePass() {
        const p = document.getElementById('pass');
        const i = document.getElementById('eye-icon');
        if(p.type === 'password') { p.type = 'text'; i.className = 'fas fa-eye-slash text-sm'; }
        else { p.type = 'password'; i.className = 'fas fa-eye text-sm'; }
    }
    </script>
</body>
</html>
