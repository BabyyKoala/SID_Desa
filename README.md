# SID Desa Darmakradenan — Sistem Informasi Desa Darmakradenan

## Panduan Instalasi & Penggunaan (XAMPP/Localhost)

---

## 📁 Struktur Folder
```text
sid-desa/
├── config/
│   ├── db.php          ← Konfigurasi database
│   ├── header.php      ← Layout header publik
│   └── footer.php      ← Layout footer publik
├── pages/
│   ├── ajukan-surat.php     ← Form pengajuan surat
│   ├── cek-status.php       ← Cek status pengajuan
│   ├── pengaduan.php        ← Form pengaduan warga
│   ├── informasi.php        ← Info berita, UMKM, potensi, lembaga (Dilengkapi UI Modal Pop-up)
│   ├── detail-berita.php    ← Detail halaman berita
│   ├── layanan.php          ← Halaman layanan
│   ├── transparansi.php     ← APBDes & program desa
│   └── kontak.php           ← Kontak desa
├── admin/
│   ├── login.php            ← Login admin
│   ├── dashboard.php        ← Dashboard admin
│   ├── layout.php           ← Layout sidebar admin
│   ├── layout-footer.php    ← Penutup layout admin
│   ├── kelola-surat.php     ← CRUD + update status surat
│   ├── kelola-pengaduan.php ← CRUD + update status pengaduan
│   ├── kelola-berita.php    ← CRUD berita
│   ├── kelola-umkm.php      ← CRUD UMKM
│   ├── kelola-potensi.php   ← CRUD potensi unggulan desa
│   ├── kelola-lembaga.php   ← CRUD perangkat desa
│   ├── kelola-transparansi.php ← CRUD APBDes & program
│   └── logout.php           ← Logout
├── uploads/
│   ├── berita/             ← Foto berita
│   ├── pengaduan/          ← Foto pengaduan
│   ├── potensi/            ← Foto potensi desa
│   └── umkm/               ← Foto UMKM
├── database.sql            ← Script database lengkap
└── index.php               ← Halaman utama            ← Halaman utama
```

---

## 🚀 Langkah Instalasi

### 1. Persiapan XAMPP

- Download & install XAMPP dari https://apachefriends.org
- Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel

### 2. Copy Project

```
Salin folder `sid-desa` ke:
C:\xampp\htdocs\sid-desa\
```

### 3. Import Database

1. Buka browser → http://localhost/phpmyadmin
2. Klik **"New"** → buat database bernama `sid_desa`
3. Klik database `sid_desa` → pilih tab **"Import"**
4. Klik **"Choose File"** → pilih file `database.sql`
5. Klik **"Go"** / **"Import"**

### 4. Konfigurasi (Jika Perlu)

Buka file `config/db.php` dan sesuaikan:

```php
define('DB_HOST', 'localhost');   // host database
define('DB_USER', 'root');        // username MySQL
define('DB_PASS', '');            // password MySQL (kosong untuk XAMPP default)
define('DB_NAME', 'sid_desa');    // nama database
define('BASE_URL', 'http://localhost/sid_desa');  // URL website
define('WA_NUMBER', '6281234567890'); // Nomor WA admin desa (format 62xxx)
```

### 5. Akses Website

| Halaman       | URL                                       |
| ------------- | ----------------------------------------- |
| Website Utama | http://localhost/sid_desa/                |
| Login Admin   | http://localhost/sid_desa/admin/login.php |

---

## 🔐 Login Admin 

Hak Akses / Role | Username  | Password       | Keterangan
Super Admin      | admin     | SuperAdmin2026 | Akses penuh ke seluruh fitur dan pengaturan.
Staf Pelayanan   | staf_desa | StafDarma2026  | Akses kelola surat, pengaduan, dan berita.
Kepala Desa      | kades     | KadesDarma2026 | Hanya akses pantau Dashboard dan Laporan.
---

## 📱 Fitur Lengkap

### Halaman Publik (Warga)

- ✅ **Beranda** — Hero section + 3 CTA utama + statistik + berita + UMKM
- ✅ **Ajukan Surat** — Form pengajuan + kode otomatis + notifikasi WA
- ✅ **Cek Status** — Cari via kode pengajuan atau NIK
- ✅ **Pengaduan** — Form laporan + upload foto
- ✅ **Informasi** — Berita, UMKM, Potensi, Perangkat Desa (tab)
- ✅ **Transparansi** — APBDes & Program Desa
- ✅ **Kontak** — Info + tombol WhatsApp langsung
- ✅ **Tombol WA Float** — Di semua halaman

### Panel Admin

- ✅ **Login** dengan session PHP yang aman
- ✅ **Dashboard** — Statistik + data terbaru
- ✅ **Kelola Surat** — Lihat, filter, ubah status, hapus
- ✅ **Kelola Pengaduan** — Lihat, ubah status, hapus + foto
- ✅ **Kelola Berita** — Tambah, edit, hapus + upload foto
- ✅ **Kelola UMKM** — Tambah, edit, hapus + upload foto
- ✅ **Kelola Potensi** — Tambah, edit, hapus data direktori potensi desa 
- ✅ **Kelola Perangkat Desa** — Tambah, edit, urutan, hapus
- ✅ **Kelola Transparansi** — APBDes & Program Desa CRUD

---

## 🎨 Teknologi

| Komponen | Teknologi                        |
| -------- | -------------------------------- |
| Frontend | HTML5, Tailwind CSS (CDN)        |
| Backend  | PHP Native (tanpa framework)     |
| Database | MySQL                            |
| Icons    | Font Awesome 6                   |
| Font     | Plus Jakarta Sans (Google Fonts) |
| Server   | XAMPP (Apache + MySQL)           |

---

## ⚙️ Kustomisasi

### Ganti Nama Desa

Cari & replace `Desa` di semua file.

### Ganti Nomor WhatsApp

Edit `WA_NUMBER` di `config/db.php`:

```php
define('WA_NUMBER', '6281234567890'); // Format: 62 + nomor tanpa 0
```

### Ganti Warna Utama

Semua menggunakan Tailwind `emerald/primary`. Cari `primary` di file untuk kustomisasi.

---

## 🐛 Troubleshooting

**Database tidak terhubung:**

- Pastikan MySQL berjalan di XAMPP
- Cek username/password di `config/db.php`

**Upload foto tidak berfungsi:**

- Pastikan folder `uploads/berita`, `uploads/pengaduan`, `uploads/umkm` ada dan writable
- Cek `chmod 755` jika di Linux/Mac

**Halaman admin redirect terus:**

- Pastikan `session_start()` di setiap file admin

---

## 📞 Dukungan

Hubungi administrator sistem desa untuk bantuan teknis.

---

_SID Desa — Sistem Informasi Desa Digital_
_Dibuat untuk kemajuan dan pelayanan masyarakat desa_
