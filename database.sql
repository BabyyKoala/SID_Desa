-- ============================================
-- SID Desa ABC - Database Schema
-- Import via phpMyAdmin atau MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS sid_desa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sid_desa;

-- Tabel Users (Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Surat
CREATE TABLE IF NOT EXISTS surat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(20) NOT NULL,
    nama VARCHAR(200) NOT NULL,
    jenis_surat VARCHAR(100) NOT NULL,
    keperluan TEXT NOT NULL,
    kode_pengajuan VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('Diproses','Selesai','Ditolak') DEFAULT 'Diproses',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Pengaduan
CREATE TABLE IF NOT EXISTS pengaduan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    foto VARCHAR(255),
    status ENUM('Masuk','Diproses','Selesai') DEFAULT 'Masuk',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Berita
CREATE TABLE IF NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(300) NOT NULL,
    isi LONGTEXT NOT NULL,
    gambar VARCHAR(255),
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel UMKM
CREATE TABLE IF NOT EXISTS umkm (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(200) NOT NULL,
    deskripsi TEXT NOT NULL,
    foto VARCHAR(255),
    kontak VARCHAR(100),
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Potensi Desa
CREATE TABLE IF NOT EXISTS potensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori ENUM('Wisata','Pertanian','Kerajinan') NOT NULL,
    judul VARCHAR(300) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255),
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Lembaga Desa
CREATE TABLE IF NOT EXISTS lembaga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(200) NOT NULL,
    jabatan VARCHAR(200) NOT NULL,
    foto VARCHAR(255),
    urutan INT DEFAULT 0,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel APBDes
CREATE TABLE IF NOT EXISTS apbdes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun YEAR NOT NULL,
    kategori ENUM('Pendapatan','Pengeluaran') NOT NULL,
    uraian VARCHAR(300) NOT NULL,
    jumlah BIGINT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Program Desa
CREATE TABLE IF NOT EXISTS program_desa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_program VARCHAR(300) NOT NULL,
    deskripsi TEXT,
    status ENUM('Perencanaan','Berjalan','Selesai') DEFAULT 'Perencanaan',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- DATA CONTOH (SAMPLE DATA)
-- ============================================

-- Admin default: username=admin, password=admin123
INSERT INTO users (username, password, nama_lengkap) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Desa');

-- Berita contoh
INSERT INTO berita (judul, isi, gambar) VALUES
('Musyawarah Desa Bahas Anggaran 2025', 'Desa ABC telah mengadakan musyawarah desa untuk membahas rencana anggaran tahun 2025. Kegiatan ini dihadiri oleh seluruh perangkat desa, BPD, dan perwakilan warga dari setiap RT/RW. Dalam musyawarah tersebut, disepakati beberapa program prioritas untuk meningkatkan kesejahteraan masyarakat.', NULL),
('Perbaikan Jalan Desa Dimulai', 'Program perbaikan jalan desa sepanjang 2 km telah resmi dimulai. Pekerjaan ini diharapkan selesai dalam waktu 30 hari kerja. Warga diimbau untuk berhati-hati saat melintas di area proyek.', NULL),
('Posyandu Balita Bulan Juni', 'Posyandu balita bulan Juni akan dilaksanakan pada tanggal 15 Juni 2025 di Balai Desa ABC. Seluruh orang tua dengan anak balita diharapkan hadir untuk penimbangan dan pemeriksaan kesehatan gratis.', NULL);

-- UMKM contoh
INSERT INTO umkm (nama, deskripsi, foto, kontak) VALUES
('Keripik Singkong Bu Sari', 'Keripik singkong renyah dengan berbagai varian rasa: original, pedas, dan keju. Dibuat dari singkong pilihan lokal dengan proses higienis.', NULL, '08123456789'),
('Batik Tulis Pak Hendra', 'Batik tulis motif khas daerah dengan kualitas premium. Tersedia dalam berbagai ukuran dan warna. Melayani pesanan custom.', NULL, '08234567890'),
('Warung Makan Mbak Rina', 'Menyajikan masakan rumahan khas Jawa dengan harga terjangkau. Buka setiap hari dari pukul 07.00-21.00 WIB.', NULL, '08345678901');

-- Lembaga contoh
INSERT INTO lembaga (nama, jabatan, urutan) VALUES
('H. Budi Santoso', 'Kepala Desa', 1),
('Siti Rahayu, S.Pd', 'Sekretaris Desa', 2),
('Ahmad Fauzi', 'Kaur Keuangan', 3),
('Dewi Kusuma', 'Kaur Perencanaan', 4),
('Suparman', 'Kasi Pelayanan', 5),
('Marlina', 'Kasi Kesejahteraan', 6);

-- Program Desa contoh
INSERT INTO program_desa (nama_program, deskripsi, status) VALUES
('Pavingisasi Jalan RT 03', 'Pemasangan paving block di jalan lingkungan RT 03 sepanjang 500 meter', 'Berjalan'),
('Posyandu Remaja', 'Program kesehatan remaja bulanan dengan pemeriksaan gratis', 'Berjalan'),
('Digitalisasi Administrasi Desa', 'Pengembangan sistem informasi desa berbasis web untuk pelayanan masyarakat', 'Selesai'),
('Pelatihan UMKM Digital', 'Pelatihan pemasaran online untuk pelaku UMKM desa', 'Perencanaan');

-- APBDes contoh
INSERT INTO apbdes (tahun, kategori, uraian, jumlah) VALUES
(2025, 'Pendapatan', 'Dana Desa', 850000000),
(2025, 'Pendapatan', 'Alokasi Dana Desa (ADD)', 320000000),
(2025, 'Pendapatan', 'Pendapatan Asli Desa', 45000000),
(2025, 'Pengeluaran', 'Bidang Penyelenggaraan Pemerintahan', 280000000),
(2025, 'Pengeluaran', 'Bidang Pembangunan Desa', 550000000),
(2025, 'Pengeluaran', 'Bidang Pembinaan Kemasyarakatan', 120000000),
(2025, 'Pengeluaran', 'Bidang Pemberdayaan Masyarakat', 265000000);

-- Potensi contoh
INSERT INTO potensi (kategori, judul, deskripsi) VALUES
('Wisata', 'Waduk Sari Indah', 'Waduk buatan yang kini menjadi destinasi wisata alam dengan pemandangan indah. Tersedia fasilitas perahu, area piknik, dan warung makan.'),
('Pertanian', 'Sawah Organik Terintegrasi', 'Lahan persawahan seluas 45 hektar dengan sistem pertanian organik yang menghasilkan beras premium berkualitas tinggi.'),
('Kerajinan', 'Sentra Gerabah Tradisional', 'Kerajinan gerabah yang diwariskan turun-temurun. Produk berupa pot, kendi, dan dekorasi rumah dengan motif khas lokal.');
