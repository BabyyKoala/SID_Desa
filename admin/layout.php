<?php
// admin/layout.php - Admin Layout
if(!isAdmin()) redirect('../admin/login.php');
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$current = basename($_SERVER['PHP_SELF']);
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
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif']}}}}</script>
    <style>
        body{font-family:'Plus Jakarta Sans',sans-serif;}
        .sidebar-link{transition:all .15s;}
        .sidebar-link:hover,.sidebar-link.active{background:#ecfdf5;color:#059669;}
        .sidebar-link.active{font-weight:700;}
        .badge-diproses{background:#fef3c7;color:#92400e;}
        .badge-selesai{background:#d1fae5;color:#065f46;}
        .badge-ditolak{background:#fee2e2;color:#991b1b;}
        .badge-masuk{background:#dbeafe;color:#1e40af;}
        .badge-berjalan{background:#fef3c7;color:#92400e;}
        .badge-perencanaan{background:#e0e7ff;color:#3730a3;}
        #sidebar{transition:transform .25s ease;}
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

<!-- Mobile Header -->
<div class="lg:hidden bg-white border-b px-4 h-14 flex items-center justify-between fixed top-0 left-0 right-0 z-50 shadow-sm">
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-green-700 rounded-lg flex items-center justify-center">
            <i class="fas fa-landmark text-white text-xs"></i>
        </div>
        <span class="font-bold text-green-800 text-sm">Admin SID Darmakradenan</span>
    </div>
    <button onclick="toggleSidebar()" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
        <i class="fas fa-bars"></i>
    </button>
</div>

<!-- Overlay -->
<div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-30 z-30 lg:hidden" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 z-40 transform -translate-x-full lg:translate-x-0 flex flex-col">
    <!-- Logo -->
    <div class="p-5 border-b">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-700 rounded-xl flex items-center justify-center">
                <i class="fas fa-landmark text-white"></i>
            </div>
            <div>
                <div class="font-extrabold text-green-800 text-sm">SID Desa Darmakradenan</div>
                <div class="text-xs text-gray-400">Panel Admin</div>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <?php
        $menus = [
            ['file'=>'dashboard.php','icon'=>'fa-tachometer-alt','label'=>'Dashboard'],
            ['file'=>'kelola-surat.php','icon'=>'fa-file-alt','label'=>'Kelola Surat'],
            ['file'=>'kelola-pengaduan.php','icon'=>'fa-comment-dots','label'=>'Kelola Pengaduan'],
            ['file'=>'kelola-berita.php','icon'=>'fa-newspaper','label'=>'Kelola Berita'],
            ['file'=>'kelola-umkm.php','icon'=>'fa-store','label'=>'Kelola UMKM'],
            ['file'=>'kelola-lembaga.php','icon'=>'fa-sitemap','label'=>'Kelola Perangkat'],
            ['file'=>'kelola-transparansi.php','icon'=>'fa-coins','label'=>'Transparansi'],
        ];
        foreach($menus as $m): ?>
        <a href="<?= $m['file'] ?>" 
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 <?= $current === $m['file'] ? 'active' : '' ?>">
            <i class="fas <?= $m['icon'] ?> w-5 text-center text-sm"></i>
            <span><?= $m['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- Bottom -->
    <div class="p-4 border-t">
        <div class="flex items-center gap-3 mb-3 px-2">
            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-500 text-xs"></i>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($admin_name) ?></div>
                <div class="text-xs text-gray-400">Administrator</div>
            </div>
        </div>
        <a href="../index.php" target="_blank"
           class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-gray-600 mb-1">
            <i class="fas fa-external-link-alt w-5 text-center text-sm"></i> Lihat Website
        </a>
        <a href="logout.php" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-red-600 hover:bg-red-50">
            <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i> Keluar
        </a>
    </div>
</aside>

<!-- Main -->
<div class="lg:ml-64 pt-14 lg:pt-0">
    <!-- Top Bar (desktop) -->
    <div class="hidden lg:flex bg-white border-b px-6 h-14 items-center justify-between sticky top-0 z-20">
        <h2 class="font-bold text-gray-800"><?= $page_title ?? 'Dashboard' ?></h2>
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <i class="fas fa-user-circle text-gray-400"></i>
            <?= htmlspecialchars($admin_name) ?>
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
