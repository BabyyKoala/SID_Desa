<?php
// admin/layout.php - Admin Layout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek keamanan admin
if (function_exists('isAdmin') && !isAdmin()) {
    header("Location: login.php");
    exit;
} elseif (!function_exists('isAdmin') && !isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_role = $_SESSION['admin_role'] ?? 'staf'; // Default ke staf jika kosong
$current = basename($_SERVER['PHP_SELF']);

// FITUR RBAC: Logika Proteksi Halaman (Cegah akses via URL langsung)
$restricted_pages = [
    'super_admin' => [], // Super admin bebas ke mana saja
    'staf' => ['kelola-penduduk.php', 'kelola-transparansi.php'], // Staf tidak boleh akses data kependudukan & uang
    'kepala_desa' => ['kelola-penduduk.php', 'kelola-surat.php', 'kelola-pengaduan.php', 'kelola-berita.php', 'kelola-umkm.php', 'kelola-potensi.php', 'kelola-lembaga.php'] // Kades hanya lihat dashboard & transparansi
];

// Jika user mencoba mengakses halaman terlarang
if (in_array($current, $restricted_pages[$admin_role])) {
    echo "<script>alert('Akses Ditolak! Anda tidak memiliki izin ke halaman ini.'); window.location='dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' — Admin SID Desa Darmakradenan' : 'Admin SID Desa Darmakradenan' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' } }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link { transition: all .15s; }
        .sidebar-link:hover, .sidebar-link.active { background: #ecfdf5; color: #059669; }
        .sidebar-link.active { font-weight: 700; border-right: 4px solid #16a34a; }
        
        .badge-diproses { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-selesai { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-masuk { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        
        #sidebar { transition: transform .25s ease; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

<div class="lg:hidden bg-white border-b px-4 h-14 flex items-center justify-between fixed top-0 left-0 right-0 z-50 shadow-sm">
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-primary-700 rounded-lg flex items-center justify-center">
            <i class="fas fa-landmark text-white text-xs"></i>
        </div>
        <span class="font-bold text-primary-800 text-sm">SID Darmakradenan</span>
    </div>
    <button onclick="toggleSidebar()" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-30 z-30 lg:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 z-40 transform -translate-x-full lg:translate-x-0 flex flex-col">
    <div class="p-5 border-b">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-700 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-landmark text-white"></i>
            </div>
            <div>
                <div class="font-extrabold text-primary-800 text-sm">SID Desa</div>
                <div class="text-[10px] bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider inline-block mt-1">
                    <?= str_replace('_', ' ', $admin_role) ?>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="dashboard.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt w-5 text-center text-sm"></i> Dashboard
        </a>

        <?php if($admin_role === 'super_admin'): ?>
        <a href="kelola-penduduk.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-penduduk.php' ? 'active' : '' ?>">
            <i class="fas fa-users w-5 text-center text-sm"></i> Data Penduduk
        </a>
        <a href="kelola-admin.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-admin.php' ? 'active' : '' ?>">
            <i class="fas fa-user-shield w-5 text-center text-sm"></i> Kelola Pengguna
        </a>
        <?php endif; ?>

        <?php if($admin_role !== 'kepala_desa'): ?>
        <a href="kelola-surat.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-surat.php' ? 'active' : '' ?>">
            <i class="fas fa-file-alt w-5 text-center text-sm"></i> Kelola Surat
        </a>
        <a href="kelola-pengaduan.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-pengaduan.php' ? 'active' : '' ?>">
            <i class="fas fa-comment-dots w-5 text-center text-sm"></i> Kelola Pengaduan
        </a>
        <a href="kelola-berita.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-berita.php' ? 'active' : '' ?>">
            <i class="fas fa-newspaper w-5 text-center text-sm"></i> Kelola Berita
        </a>
        <a href="kelola-umkm.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-umkm.php' ? 'active' : '' ?>">
            <i class="fas fa-store w-5 text-center text-sm"></i> Kelola UMKM
        </a>
        <a href="kelola-potensi.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-potensi.php' ? 'active' : '' ?>">
            <i class="fas fa-leaf w-5 text-center text-sm"></i> Kelola Potensi
        </a>
        <a href="kelola-lembaga.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-lembaga.php' ? 'active' : '' ?>">
            <i class="fas fa-sitemap w-5 text-center text-sm"></i> Kelola Perangkat
        </a>
        <?php endif; ?>

        <?php if($admin_role !== 'staf'): ?>
        <a href="kelola-transparansi.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === 'kelola-transparansi.php' ? 'active' : '' ?>">
            <i class="fas fa-coins w-5 text-center text-sm"></i> Transparansi
        </a>
        <?php endif; ?>
    </nav>

    <div class="p-4 border-t">
        <div class="flex items-center gap-3 mb-3 px-2">
            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-500 text-xs"></i>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($admin_name) ?></div>
                <div class="text-[10px] text-gray-400 uppercase"><?= str_replace('_', ' ', $admin_role) ?></div>
            </div>
        </div>
        <a href="../index.php" target="_blank"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 mb-1">
            <i class="fas fa-external-link-alt w-5 text-center text-sm"></i> Lihat Website
        </a>
        <a href="logout.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-red-600 hover:bg-red-50 font-bold transition">
            <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i> Keluar
        </a>
    </div>
</aside>

<div class="lg:ml-64 pt-14 lg:pt-0">
    <div class="hidden lg:flex bg-white border-b px-6 h-14 items-center justify-between sticky top-0 z-20">
        <h2 class="font-bold text-gray-800"><?= $page_title ?? 'Dashboard' ?></h2>
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <div class="bg-primary-50 text-primary-700 px-3 py-1 rounded-full text-xs font-bold border border-primary-100 flex items-center gap-2">
                <i class="fas fa-shield-alt"></i> Level: <?= strtoupper(str_replace('_', ' ', $admin_role)) ?>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('-translate-x-full');
    o.classList.toggle('hidden');
}
</script>