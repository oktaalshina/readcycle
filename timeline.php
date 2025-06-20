<?php
include_once("config.php"); // Pastikan session_start() ada di dalam file ini
requireLogin(); // Pastikan user sudah login

// Konfigurasi pagination
$limit = 5; // Data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Konfigurasi search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_query = '';
if (!empty($search)) {
    // Diperbarui agar bisa mencari di semua kolom relevan
    $search_query = "WHERE b.title LIKE '%$search%' OR b.author LIKE '%$search%' OR u.username LIKE '%$search%' OR g.genreName LIKE '%$search%' OR p.provinceName LIKE '%$search%'";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
    $book_id = (int)$_POST['book_id'];
    $user_id = (int)$_SESSION['user_id'];

    if (!empty($comment_text)) {
        $insert_comment_query = "INSERT INTO comments (bookId, userId, comment) VALUES ('$book_id', '$user_id', '$comment_text')";
        if (mysqli_query($conn, $insert_comment_query)) {
            // Buat URL tujuan
            $redirect_url = $_SERVER['PHP_SELF'] . "?page=" . $page . "&search=" . urlencode($search);
        
            // Lakukan redirect dengan Javascript dan langsung hentikan eksekusi script
            echo "<script>window.location.href = '" . $redirect_url . "';</script>";
            exit();
        } else {
            // Handle error jika perlu
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Query untuk menghitung total data (dengan semua JOIN)
$count_query = "SELECT COUNT(b.id) as total 
                FROM books b 
                LEFT JOIN users u ON b.ownerId = u.id
                LEFT JOIN genre g ON b.genreId = g.id
                LEFT JOIN provinces p ON b.provinceId = p.id
                $search_query";
$count_result = mysqli_query($conn, $count_query);
$total_data = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_data / $limit);

// echo "<div style='background: yellow; padding: 10px; border: 2px solid red; position: fixed; top: 0; left: 0; width: 100%; z-index: 9999;'>";
// echo "<b>DEBUG INFO:</b><br>";
// echo "Total Data Ditemukan (total_data): " . $total_data . "<br>";
// echo "Limit per Halaman (limit): " . $limit . "<br>";
// echo "Total Halaman Dihitung (total_pages): " . $total_pages;
// echo "</div>";

// Query utama untuk mengambil semua data yang dibutuhkan sekaligus
$query = "SELECT b.*, u.username, g.genreName, p.provinceName 
          FROM books b 
          JOIN users u ON b.ownerId = u.id 
          LEFT JOIN genre g ON b.genreId = g.id
          LEFT JOIN provinces p ON b.provinceId = p.id
          $search_query 
          ORDER BY b.id DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Mengambil semua data genre untuk dropdown
$all_genres_query = "SELECT * FROM genre ORDER BY genreName ASC";
$all_genres_result = mysqli_query($conn, $all_genres_query);

// Mengambil semua data provinsi untuk dropdown
$all_provinces_query = "SELECT * FROM provinces ORDER BY provinceName ASC";
$all_provinces_result = mysqli_query($conn, $all_provinces_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>READCYCLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Rubik', sans-serif; 
            background-color: bg-white; }
        .main-header {
            background: linear-gradient(90deg, #FFF1D5 44.12%, #9FB3DF 100%);
            padding: 1rem 0;
        }
        .brand-header { 
            font-family: 'Righteous', cursive;
            color: #9FB3DF;
        }
        .card { 
            border: 1px solid #e9ecef; 
            
        }
        .comment-box { 
            background-color: #e9ecef; 
            font-size: 0.9em; 
        }
        .footer {
            background: linear-gradient(90deg, #FFF1D5 0%, #9FB3DF 100%);
            font-family: Rubik;
        }

        .footer a {
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }
        .brand-name {
            font-family: 'Righteous', cursive;
            color: #9FB3DF;
        }
        .btn-deco {
            display: inline-block;      /* Agar bisa diberi padding & margin */
            padding: 0.35em 0.65em;     /* Atur jarak dalam (atas-bawah, kanan-kiri) */
            font-size: 0.875em;         /* Ukuran font sedikit lebih kecil */
            font-weight: 500;           /* Ketebalan font */
            line-height: 1.5;           /* Jarak antar baris */
            text-align: center;         /* Teks di tengah */
            text-decoration: none;      /* Hapus garis bawah default dari link */
            vertical-align: middle;     /* Membantu perataan vertikal */
            cursor: default;            /* Cursor normal untuk yang bukan link */
            color: white;  /* Warna dasar abu-abu (atau warna birumu) */
            border-radius: 100px;
            background: #9EC6F3;        /* Radius melengkung (pill-shaped) */
            border: 1px solid transparent;
        }
        
        /* Efek hover HANYA untuk link (<a>) yang punya kelas .btn-deco */
        a.btn-deco:hover {
            background-color: #5a6268; /* Warna jadi sedikit lebih gelap saat disentuh mouse */
            cursor: pointer;           /* Cursor berubah jadi tangan saat di atas link */
        }
        .nav {
            color: #333333;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <header class="shadow-sm main-header">
        <div class="container text-center">
            <h1 class="brand-header mb-0">READCYCLE</h1>
        </div>
    </header>

    <main class="flex-grow-1 py-4">
        <div class="container">
            <div class="row">

                <aside class="col-md-3">
                    <div class="p-3 bg-white rounded shadow-sm">
                        <h5 class="fw-bold">Hello, @<?php echo htmlspecialchars($_SESSION['username']); ?>!</h5>
                        <hr>
                        <ul class="nav flex-column">
                            <!--<li class="btn-deco">-->
                            <!--    <a class="nav-link" href="timeline.php">Home</a>-->
                            <!--</li>-->
                            <li class="nav-item">
                                <a class="nav-link" href="#">Profile</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-danger" href="logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </aside>

                <div class="col-md-9">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" class="flex-grow-1 me-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
                        </form>
                        <a href="posting.php" class="btn btn-deco">+ Posting</a>
                    </div>

                    <div class="d-flex gap-2 mb-4">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Genre</button>
                            <ul class="dropdown-menu">
                                <?php mysqli_data_seek($all_genres_result, 0); // Reset pointer
                                while($genre_row = mysqli_fetch_assoc($all_genres_result)): ?>
                                    <li><a class="dropdown-item" href="?genre=<?php echo $genre_row['id']; ?>"><?php echo htmlspecialchars($genre_row['genreName']); ?></a></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Lokasi</button>
                            <ul class="dropdown-menu">
                                <?php mysqli_data_seek($all_provinces_result, 0); // Reset pointer
                                while($province_row = mysqli_fetch_assoc($all_provinces_result)): ?>
                                    <li><a class="dropdown-item" href="?province=<?php echo $province_row['id']; ?>"><?php echo htmlspecialchars($province_row['provinceName']); ?></a></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>

                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">

                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="card-subtitle text-muted fw-bold mb-0">@<?php echo htmlspecialchars($row['username']); ?></h6>
                                    
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['ownerId']): ?>
                                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="badge bg-transparent text-dark border" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">Hapus</a>
                                            <?php endif; ?>
                                        </div>
                                    
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['ownerId']): ?>
    
                                                <a href="ubah_status.php?id=<?php echo $row['id']; ?>&status=<?php echo ($row['isAvailable'] == 1) ? '0' : '1'; ?>" class="badge <?php echo ($row['isAvailable'] == 1) ? 'bg-success' : 'bg-secondary'; ?> text-decoration-none">
                                                    <?php echo ($row['isAvailable'] == 1) ? 'Available' : 'Not Available'; ?>
                                                </a>
                                            
                                            <?php else: ?>
                                            
                                                <span class="badge <?php echo ($row['isAvailable'] == 1) ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?php echo ($row['isAvailable'] == 1) ? 'Available' : 'Not Available'; ?>
                                                </span>
                                            
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($row['provinceName'])): ?>
                                                <span class="btn-deco"><?php echo htmlspecialchars($row['provinceName']); ?></span>
                                            <?php endif; ?>
                                            
                                            <a href="#" class="btn-deco">Contact</a>
                                        </div>
                                    
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <?php
                                            // Kita siapkan path gambar default sebagai cadangan
                                            $image_path = "assets/gambarBuku.PNG"; 
                                        
                                            // Cek apakah ada nama file gambar di database DAN apakah file-nya benar-benar ada di folder 'books'
                                            if (!empty($row['imageUrl']) && file_exists("books/" . $row['imageUrl'])) {
                                                // Jika ada, kita ubah path-nya untuk menunjuk ke gambar yang benar
                                                $image_path = "books/" . $row['imageUrl'];
                                            }
                                            ?>
                                            
                                            <img src="<?php echo $image_path; ?>" class="img-fluid rounded" style="height: 200px; width: 150px; object-fit: cover;" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-1"><strong>Judul :</strong> <?php echo htmlspecialchars($row['title']); ?></p>
                                            <p class="mb-1"><strong>Penulis :</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                                            <p class="mb-1"><strong>Kondisi :</strong> <?php echo htmlspecialchars($row['bookCondition']); ?></p>
                                            <p class="mb-1"><strong>Bahasa :</strong> Indonesia</p>
                                            <p class="mb-1"><strong>Ingin ditukar dengan :</strong> <?php echo htmlspecialchars($row['lookingFor']); ?></p>
                                            <p class="mb-1"><strong>DESC :</strong><br><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                                            <p class="mt-2">
                                                <?php if (!empty($row['genreName'])): ?>
                                                    <span class="badge bg-transparent text-dark border">#<?php echo htmlspecialchars($row['genreName']); ?></span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mt-3">
                                        <h6 class="small fw-bold">Comments:</h6>
                                        <?php
                                        $book_id = $row['id'];
                                        $comments_query = "SELECT c.comment, u.username FROM comments c JOIN users u ON c.userId = u.id WHERE c.bookId = '$book_id' ORDER BY c.createdAt ASC";
                                        $comments_result = mysqli_query($conn, $comments_query);
                                        if ($comments_result && mysqli_num_rows($comments_result) > 0):
                                            while($comment = mysqli_fetch_assoc($comments_result)):
                                        ?>
                                                <div class="p-2 mb-2 comment-box rounded">
                                                    <strong>@<?php echo htmlspecialchars($comment['username']); ?></strong> 
                                                    <?php echo htmlspecialchars($comment['comment']); ?>
                                                </div>
                                        <?php 
                                            endwhile;
                                        else:
                                        ?>
                                            <p class="small text-muted fst-italic">Belum ada komentar.</p>
                                        <?php 
                                        endif; 
                                        ?>
                                        <form method="POST" action="" class="mt-3 d-flex gap-2">
                                            <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                                            <input type="text" name="comment_text" class="form-control form-control-sm" placeholder="Tulis komentar..." required>
                                            <button type="submit" name="submit_comment" class="btn btn-deco">Kirim</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center p-5 bg-white rounded shadow-sm">
                            <h4>Tidak ada buku ditemukan.</h4>
                        </div>
                    <?php endif; ?>

                    <?php if ($total_pages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </main>

    <footer class="footer px-3 py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
            <div class="col-md-4 text-md-start text-center mb-2 mb-md-0">
                <div class="brand-name">
                <strong class="text">READCYCLE</strong>
                </div>
            </div>

            <div class="col-md-4 text-center mb-2 mb-md-0">
                <a href="#" class="text-dark mx-2">About Us</a>
                <span class="text-muted">|</span>
                <a href="#" class="text-dark mx-2">FAQ</a>
                <span class="text-muted">|</span>
                <a href="#" class="text-dark mx-2">Contact</a>
            </div>

            <div class="col-md-4 text-md-end text-center">
                <small class="text-muted">© 2025 READCYCLE. All rights reserved.</small>
            </div>
            </div> <!-- penutup .row di tempat yang benar -->
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>