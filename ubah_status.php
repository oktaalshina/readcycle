<?php
include_once("config.php"); // Meng-include koneksi dan memulai session
requireLogin(); // Memastikan hanya user yang sudah login yang bisa mengakses file ini

// 1. Validasi Input dari URL
// Pastikan parameter 'id' dan 'status' dikirim lewat URL
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    // Jika tidak lengkap, langsung kembalikan ke timeline
    header("Location: timeline.php");
    exit();
}

// 2. Ambil dan bersihkan data dari URL dan Session
$book_id = (int)$_GET['id'];
$new_status = (int)$_GET['status']; // Status baru (0 atau 1)
$user_id = (int)$_SESSION['user_id']; // ID user yang sedang login

// 3. Verifikasi Kepemilikan (Langkah Keamanan PENTING)
// Ambil dulu ownerId dari buku yang mau diubah untuk memastikan
$verification_query = "SELECT ownerId FROM books WHERE id = '$book_id'";
$verification_result = mysqli_query($conn, $verification_query);

if (mysqli_num_rows($verification_result) == 1) {
    $book = mysqli_fetch_assoc($verification_result);
    
    // Cek apakah user yang sedang login adalah pemilik sah buku ini
    if ($book['ownerId'] == $user_id) {
        
        // 4. Jika benar pemilik, lakukan UPDATE ke database
        $update_query = "UPDATE books SET isAvailable = '$new_status' WHERE id = '$book_id'";
        
        if (mysqli_query($conn, $update_query)) {
            // Jika berhasil, siapkan pesan sukses (opsional)
            $_SESSION['message'] = "Status buku berhasil diperbarui.";
        } else {
            // Jika gagal, siapkan pesan error (opsional)
            $_SESSION['error'] = "Gagal memperbarui status buku.";
        }

    } else {
        // Jika bukan pemilik, set pesan error hak akses
        $_SESSION['error'] = "Anda tidak memiliki izin untuk mengubah postingan ini.";
    }
} else {
    $_SESSION['error'] = "Buku tidak ditemukan.";
}

// 5. Redirect 'Pintar' kembali ke halaman timeline yang benar
// Ambil info halaman & search dari URL untuk kembali ke tempat yang sama
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$redirect_url = "timeline.php?page=" . $page . "&search=" . urlencode($search);

// Gunakan JavaScript redirect yang aman dan anti-error
echo "<script>window.location.href = '" . $redirect_url . "';</script>";
exit();

?>