<?php
// config/header.php - Layout Header untuk halaman publik
$page_title = isset($page_title) ? $page_title . ' — SID Desa Darmakradenan' : 'SID Desa Darmakradenan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <meta name="description" content="Sistem Informasi Desa Darmakradenan - Pelayanan administrasi desa secara digital">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#ecfdf5',100:'#d1fae5',200:'#a7f3d0',300:'#6ee7b7',400:'#34d399',500:'#10b981',600:'#059669',700:'#047857',800:'#065f46',900:'#064e3b' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link { transition: color 0.2s; }
        .nav-link:hover { color: #059669; }
        .nav-link.active { color: #059669; font-weight: 600; border-bottom: 2px solid #059669; }
        .btn-primary { background: linear-gradient(135deg, #059669, #047857); transition: all 0.2s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(5,150,105,0.4); }
        .card-hover { transition: all 0.2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .badge-diproses { background:#fef3c7; color:#92400e; }
        .badge-selesai { background:#d1fae5; color:#065f46; }
        .badge-ditolak { background:#fee2e2; color:#991b1b; }
        .badge-masuk { background:#dbeafe; color:#1e40af; }
        .badge-berjalan { background:#fef3c7; color:#92400e; }
        .badge-perencanaan { background:#e0e7ff; color:#3730a3; }
        .hero-bg { background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%); }
        .mobile-menu { display:none; }
        .mobile-menu.open { display:block; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-landmark text-white text-sm"></i>
                </div>
                <div>
                    <div class="font-bold text-primary-700 text-sm leading-tight">SID Desa Darmakradenan</div>
                    <div class="text-xs text-gray-400 leading-tight hidden sm:block">Sistem Informasi Desa</div>
                </div>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-1">
                <a href="<?= BASE_URL ?>/index.php" class="nav-link px-3 py-2 text-sm text-gray-600">Beranda</a>
                <a href="<?= BASE_URL ?>/pages/informasi.php" class="nav-link px-3 py-2 text-sm text-gray-600">Informasi</a>
                <a href="<?= BASE_URL ?>/pages/layanan.php" class="nav-link px-3 py-2 text-sm text-gray-600">Layanan</a>
                <a href="<?= BASE_URL ?>/pages/transparansi.php" class="nav-link px-3 py-2 text-sm text-gray-600">Transparansi</a>
                <a href="<?= BASE_URL ?>/pages/kontak.php" class="nav-link px-3 py-2 text-sm text-gray-600">Kontak</a>
            </div>

            <!-- CTA + Mobile Toggle -->
            <div class="flex items-center gap-2">
                <a href="<?= BASE_URL ?>/pages/ajukan-surat.php" 
                   class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-lg hidden sm:block">
                    <i class="fas fa-file-alt mr-1"></i> Ajukan Surat
                </a>
                <button onclick="toggleMenu()" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu pb-4 border-t border-gray-100">
            <div class="flex flex-col pt-3 gap-1">
                <a href="<?= BASE_URL ?>/index.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm">Beranda</a>
                <a href="<?= BASE_URL ?>/pages/informasi.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm">Informasi</a>
                <a href="<?= BASE_URL ?>/pages/layanan.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm">Layanan</a>
                <a href="<?= BASE_URL ?>/pages/transparansi.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm">Transparansi</a>
                <a href="<?= BASE_URL ?>/pages/kontak.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm">Kontak</a>
                <a href="<?= BASE_URL ?>/pages/ajukan-surat.php" class="mx-4 mt-2 btn-primary text-white text-sm font-semibold px-4 py-2.5 rounded-lg text-center">
                    <i class="fas fa-file-alt mr-1"></i> Ajukan Surat
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');
    menu.classList.toggle('open');
    icon.className = menu.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
}
</script>
