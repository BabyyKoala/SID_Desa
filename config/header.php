<?php
// config/header.php - Layout Header untuk halaman publik

// Fallback jika BASE_URL belum didefinisikan di db.php atau config lainnya
if (!defined('BASE_URL')) {
    // Sesuaikan '/sid_desa' dengan nama folder project Anda di htdocs. 
    // Jika ada di root domain, kosongkan saja stringnya define('BASE_URL', '');
    define('BASE_URL', ''); 
}

$page_title = isset($page_title) ? $page_title . ' — SID Desa Darmakradenan' : 'SID Desa Darmakradenan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="Sistem Informasi Desa Darmakradenan - Pelayanan administrasi desa secara digital">
    
    <!-- Tailwind CSS & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Konfigurasi Tailwind untuk warna Primary (Hijau) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: { 
                            50:'#f0fdf4',
                            100:'#dcfce7',
                            200:'#bbf7d0',
                            300:'#86efac',
                            400:'#4ade80',
                            500:'#22c55e',
                            600:'#16a34a',
                            700:'#15803d',
                            800:'#166534',
                            900:'#14532d' 
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link { transition: color 0.2s; }
        .nav-link:hover { color: #16a34a; } /* Menggunakan green-600 */
        .nav-link.active { color: #16a34a; font-weight: 600; border-bottom: 2px solid #16a34a; }
        .btn-primary { background: linear-gradient(135deg, #16a34a, #15803d); transition: all 0.2s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(22,163,74,0.4); }
        .card-hover { transition: all 0.2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        
        /* Utility classes untuk badge status */
        .badge-diproses { background:#fef3c7; color:#92400e; border: 1px solid #fde68a; }
        .badge-selesai { background:#dcfce7; color:#166534; border: 1px solid #bbf7d0; } /* Menggunakan warna dari palette primary baru */
        .badge-ditolak { background:#fee2e2; color:#991b1b; border: 1px solid #fecaca; }
        .badge-masuk { background:#fee2e2; color:#b91c1c; border: 1px solid #fecaca; } 
        .badge-berjalan { background:#dbeafe; color:#1e40af; border: 1px solid #bfdbfe; }
        .badge-perencanaan { background:#fef3c7; color:#92400e; border: 1px solid #fde68a; }
        
        .hero-bg { background: linear-gradient(135deg, #14532d 0%, #15803d 50%, #16a34a 100%); }
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
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center shadow-sm">
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
                   class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-lg hidden sm:block shadow-sm">
                    <i class="fas fa-file-alt mr-1"></i> Ajukan Surat
                </a>
                <button onclick="toggleMenu()" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition focus:outline-none">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu pb-4 border-t border-gray-100">
            <div class="flex flex-col pt-3 gap-1">
                <a href="<?= BASE_URL ?>/index.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm transition">Beranda</a>
                <a href="<?= BASE_URL ?>/pages/informasi.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm transition">Informasi</a>
                <a href="<?= BASE_URL ?>/pages/layanan.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm transition">Layanan</a>
                <a href="<?= BASE_URL ?>/pages/transparansi.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm transition">Transparansi</a>
                <a href="<?= BASE_URL ?>/pages/kontak.php" class="px-4 py-2 text-gray-600 hover:bg-primary-50 hover:text-primary-700 rounded-lg text-sm transition">Kontak</a>
                <a href="<?= BASE_URL ?>/pages/ajukan-surat.php" class="mx-4 mt-2 btn-primary text-white text-sm font-semibold px-4 py-2.5 rounded-lg text-center shadow-sm">
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
    
    // Smooth transition untuk icon
    if (menu.classList.contains('open')) {
        icon.className = 'fas fa-times text-primary-600';
    } else {
        icon.className = 'fas fa-bars';
    }
}
</script>