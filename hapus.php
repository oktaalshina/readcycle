<?php
include_once("config.php");
requireLogin();

// Cek apakah ID ada di URL
if (!isset($_GET['id'])) {
    header("Location: timeline.php");
    exit();
}

$book_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

// Ambil data buku untuk verifikasi kepemilikan
$query = "SELECT ownerId, imageUrl FROM books WHERE id = '$book_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $book = mysqli_fetch_assoc($result);
    
    // PENTING: Cek apakah user yang login adalah pemilik buku
    if ($book['ownerId'] == $user_id) {
        // Hapus file gambar terkait jika ada
        if (!empty($book['imageUrl'])) {
            // Asumsi kamu punya fungsi deleteFile di config.php
            deleteFile($book['imageUrl']);
        }

        // Hapus data buku dari database
        // Komentar akan otomatis terhapus oleh trigger yang sudah kita buat
        $delete_query = "DELETE FROM books WHERE id = '$book_id'";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['message'] = "Postingan berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus postingan: " . mysqli_error($conn);
        }
    } else {
        // Jika bukan pemilik, kirim pesan error
        $_SESSION['error'] = "Anda tidak punya hak untuk menghapus postingan ini.";
    }
} else {
    $_SESSION['error'] = "Postingan tidak ditemukan.";
}

header("Location: timeline.php");
exit();
?>