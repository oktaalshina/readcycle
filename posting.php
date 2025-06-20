<?php
include_once("config.php");
requireLogin();

// Mengambil data untuk dropdown
$genres_result = mysqli_query($conn, "SELECT * FROM genre ORDER BY genreName ASC");
$provinces_result = mysqli_query($conn, "SELECT * FROM provinces ORDER BY provinceName ASC");

if(isset($_POST['submit'])) {
    $ownerId = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $provinceId = mysqli_real_escape_string($conn, $_POST['provinceId']);
    $genreId = mysqli_real_escape_string($conn, $_POST['genreId']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $bookCondition = mysqli_real_escape_string($conn, $_POST['bookCondition']);
    $isAvailable = 1;
    $lookingFor = mysqli_real_escape_string($conn, $_POST['lookingFor']);
    $language = mysqli_real_escape_string( $conn, $_POST['language']);

    date_default_timezone_set("Asia/Jakarta");
    $createdAt = date("Y-m-d H:i:s");

    $errors = array();
    $foto_filename = null;
    
    // Validasi form (sudah bagus, tidak diubah)
    if(empty($title)) $errors[] = "Judul tidak boleh kosong!";
    if(empty($author)) $errors[] = "Penulis tidak boleh kosong!";
    if(empty($provinceId)) $errors[] = "Lokasi tidak boleh kosong!";
    if(empty($genreId)) $errors[] = "Genre tidak boleh kosong!";
    if(empty($language)) $errors[] = "Bahasa tidak boleh kosong!";
    
    // Proses upload foto (sudah bagus, tidak diubah)
    if(!empty($_FILES['imageUrl']['name'])) {
        // Asumsi kamu punya fungsi uploadFile di config.php
        $upload_result = uploadFile($_FILES['imageUrl']);
        if($upload_result['success']) {
            $foto_filename = $upload_result['filename'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }
    
    // Jika tidak ada error, simpan data (sudah bagus, tidak diubah)
    if(empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : "NULL";
        $query = "INSERT INTO books(ownerId, provinceId, title, author, description, genreId, bookCondition, isAvailable, lookingFor, createdAt, language, imageUrl) 
                  VALUES('$ownerId', '$provinceId', '$title', '$author', '$description', '$genreId', '$bookCondition', '$isAvailable', '$lookingFor', '$createdAt', '$language', $foto_sql)";
        
        if(mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Buku berhasil ditambahkan ke timeline!";
            header("Location: timeline.php");
            exit();
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
            if($foto_filename) {
                // Asumsi kamu punya fungsi deleteFile di config.php
                deleteFile($foto_filename);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posting Buku - READCYCLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Rubik', sans-serif; 
            background-color: white; 
            
        }
        .brand-name { 
            font-family: 'Righteous', cursive; 
            color: #9FB3DF; 
            
        }
        .footer { 
            background-color: #FFF1D5; 
            
        }
        .footer a { 
            text-decoration: none; 
            font-weight: 500; 
            color: #333; 
        }
        .file-upload-area { 
            border: 2px dashed #ced4da; 
            border-radius: .5rem; 
            padding: 3rem; 
            text-align: center; 
            cursor: pointer; 
            background-color: #fff; 
            transition: background-color .2s ease-in-out; 
            
        }
        .btn {
            border-radius: 18px;
            background: #9EC6F3;
        }
        .file-upload-area:hover { background-color: #e9ecef; }
        .file-upload-area input[type="file"] { display: none; }
        .preview-image { max-height: 200px; max-width: 100%; border-radius: .5rem; margin-top: 1rem; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <main class="flex-grow-1 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-5 d-flex flex-column">
                    <label for="imageUrl" class="form-label">Pilih Foto *</label>
                
                    <div class="file-upload-area flex-grow-1" onclick="document.getElementById('imageUrl').click();">
                        <input type="file" name="imageUrl" id="imageUrl" form="postingForm" accept="image/*">
                        <div id="preview" class="text-center">📷 Klik untuk pilih foto atau drag & drop di sini</div>
                    </div>
                
                    
                </div>

                <div class="col-md-7">
                    <br><h2>Posting buku untuk ditukar</h2>
                    <hr>
                    
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="posting.php" method="post" enctype="multipart/form-data" id="postingForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul *</label>
                            <input type="text" class="form-control" name="title" id="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Penulis *</label>
                            <input type="text" class="form-control" name="author" id="author" required value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="language" class="form-label">Bahasa *</label>
                            <input type="text" class="form-control" name="language" id="language" required value="<?php echo isset($_POST['language']) ? htmlspecialchars($_POST['language']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="lookingFor" class="form-label">Ingin ditukar dengan *</label>
                            <input type="text" class="form-control" name="lookingFor" id="lookingFor" required value="<?php echo isset($_POST['lookingFor']) ? htmlspecialchars($_POST['lookingFor']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md">
                                <label for="genreId" class="form-label">Genre *</label>
                                <select class="form-select" name="genreId" id="genreId" required>
                                    <option value="">Pilih Genre...</option>
                                    <?php while($genre = mysqli_fetch_assoc($genres_result)): ?>
                                        <option value="<?php echo $genre['id']; ?>"><?php echo htmlspecialchars($genre['genreName']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="provinceId" class="form-label">Lokasi *</label>
                                <select class="form-select" name="provinceId" id="provinceId" required>
                                    <option value="">Pilih Lokasi...</option>
                                    <?php while($province = mysqli_fetch_assoc($provinces_result)): ?>
                                        <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['provinceName']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="bookCondition" class="form-label">Kondisi *</label>
                                <select class="form-select" name="bookCondition" id="bookCondition" required>
                                    <option value="Good">Good</option>
                                    <option value="Average">Average</option>
                                    <option value="Damaged">Damaged</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="submit" class="btn">Post</button>
                            <span class="ms-3">Kembali ke halaman utama? <a href="timeline.php">klik disini</a></span>
                        </div>
                    </form>
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
    
    <script>
        // Javascript untuk preview image
        document.getElementById('imageUrl').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="preview-image">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>