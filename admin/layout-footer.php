</div></div><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi Global untuk Konfirmasi Hapus Data di Panel Admin
function konfirmasiHapus(url, namaData) {
    Swal.fire({
        title: 'Hapus Data?',
        text: `Anda yakin ingin menghapus "${namaData}"? Data yang sudah dihapus tidak bisa dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Merah Tailwind untuk tombol hapus
        cancelButtonColor: '#6b7280', // Abu-abu Tailwind untuk tombol batal
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true, // Memindahkan tombol batal ke sebelah kanan agar lebih intuitif
        customClass: {
            confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-sm',
            cancelButton: 'rounded-xl px-4 py-2 font-bold shadow-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, redirect ke link penghapusan
            window.location.href = url;
        }
    });
}
</script>

</body>
</html>