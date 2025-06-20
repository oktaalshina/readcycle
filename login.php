<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <title>Login Page</title>
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
        <div class="row g-0">
            <div class="col-md-6 p-0">
                <div class="kiri">
                    <img src="assets/gambarBuku.PNG" alt="gambar" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6 bg light p-5">
                <div class="login-container">
                    <div class="login-header">
                        <h1><strong>Login</strong></h1>
                        <p>Masuk ke READCYCLE untuk mulai menukar buku!</p>
                    </div>
                    
                    <?php
                    include_once("config.php");
                    
                    if (isLoggedIn()) {
                        header("Location: timeline.php");
                        exit();
                    }
                    
                    if (isset($_POST['login'])) {
                        $username = mysqli_real_escape_string($conn, $_POST['username']);
                        $password = $_POST['password'];
                        
                        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
                        $result = mysqli_query($conn, $query);
                        
                        if (mysqli_num_rows($result) == 1) {
                            $user = mysqli_fetch_assoc($result);
                            if (password_verify($password, $user['password'])) {
                                $_SESSION['user_id'] = $user['id'];
                                $_SESSION['username'] = $user['username'];
                                header("Location: timeline.php");
                                exit();
                            } else {
                                $error = "Username atau password salah!";
                            }
                        } else {
                            $error = "Username atau password salah!";
                        }
                    }
                    ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username atau Email</label><br>
                            <input type="text" name="username" class="form-control" id="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label><br>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        
                        <button type="submit" name="login" class="btn">Login</button>
                    </form>
                    
                    <br><p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                    <a href="index.php">Kembali ke halaman utama</a>
                </div>
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
    
</body>
</html>