<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <title>Register Page</title>
    <style>
        * {
        }
        
        body {
            font-family: Rubik;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        input[type="text"],
        input[type="password"] {
            border-radius: 4px;
            border: 1px solid #E4E4E4;
            background: #F1F1F1;
        }
        
        .btn {
            border-radius: 18px;
            background: #9EC6F3;
        }

        .kiri {
            background: radial-gradient(50.17% 50.17% at 50% 50%, #9FB3DF 0%, #FFF1D5 100%);
        }

        .footer {
            background-color: #FFF1D5;
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
            color: #9FB3DF;
            font-family: Righteous;
        }

    </style>
</head>
<body>
    <div class="container-fluid px-0">
        <div class="row g-0 min-vh-100"> <div class="col-md-6 p-0 kiri d-flex align-items-center justify-content-center"> 
            <img src="assets/gambarBuku.PNG" alt="gambar" class="img-fluid">
        </div>

        <div class="col-md-6 bg-white p-5 d-flex align-items-center justify-content-center"> 
            <div class="register-container w-100" style="max-width: 500px;">
                <div class="register-header">
                    <h1><strong>Register</strong></h1>
                    <p>Buat Akun Baru</p>
                </div>
                
                <?php
                    include_once("config.php");
                    
                    if (isLoggedIn()) {
                        header("Location: index.php");
                        exit();
                    }
                    
                    if (isset($_POST['register'])) {
                        $username = mysqli_real_escape_string($conn, $_POST['username']);
                        $email = mysqli_real_escape_string($conn, $_POST['email']);
                        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
                        $password = $_POST['password'];
                        $confirm_password = $_POST['confirm_password'];
                        
                        $errors = array();
                        
                        // Validasi
                        if (empty($username)) {
                            $errors[] = "Username tidak boleh kosong";
                        }
                        
                        if (empty($email)) {
                            $errors[] = "Email tidak boleh kosong";
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Format email tidak valid";
                        }
                        
                        if (empty($phone)) {
                            $errors[] = "Nomor Handphone tidak boleh kosong";
                        }
                        
                        if (empty($password)) {
                            $errors[] = "Password tidak boleh kosong";
                        } elseif (strlen($password) < 6) {
                            $errors[] = "Password minimal 6 karakter";
                        }
                        
                        if ($password !== $confirm_password) {
                            $errors[] = "Konfirmasi password tidak cocok";
                        }
                        
                        // Cek username dan email sudah ada atau belum
                        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
                        $check_result = mysqli_query($conn, $check_query);
                        
                        if (mysqli_num_rows($check_result) > 0) {
                            $errors[] = "Username atau email sudah terdaftar";
                        }
                        
                        if (empty($errors)) {
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $insert_query = "INSERT INTO users (username, email, password, phone) 
                                        VALUES ('$username', '$email', '$hashed_password', $phone)";
                            
                            if (mysqli_query($conn, $insert_query)) {
                                $_SESSION['user_id'] = mysqli_insert_id($conn); // Jika ingin langsung login otomatis
                                $_SESSION['username'] = $username;             // Simpan session jika perlu

                                header("Location: timeline.php");
                                exit(); // WAJIB agar redirect berhasil dan tidak render halaman ini lagi
                            } else {
                                $errors[] = "Error: " . mysqli_error($conn);
                            }
                        }
                    }
                    ?>
                    
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor handphone</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" name="register" class="btn">Register</button> </form>

                <br><p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                <p><a href="index.php">Kembali ke halaman utama</a></p>
            </div>
        </div>
    </div>      

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>