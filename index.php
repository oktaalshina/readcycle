<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to READCYCLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <style>
      /* Custom style agar lebih mirip gambar */
      body {
        font-family: Rubik;
        background: radial-gradient(56.73% 56.73% at 49.97% 40.23%, #9FB3DF 0%, #FFF1D5 100%);/* Warna background krem/beige */
        color: #333;
      }
      .btn-reg {
            border-radius: 18px;
            background: #9EC6F3;
        }
      .btn-custom-outline {
        border: 1px solid #4a5568;
        color: #4a5568;
      }
      .btn-custom-outline:hover {
        background-color: #e3e8f0;
        color: #4a5568;
      }
      .btn-custom-solid {
        background-color: #6a7f9e;
        color: white;
        border: 1px solid #6a7f9e;
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

      .gambar-buku {
        max-width: 450px; /* <-- ATUR ANGKA INI. Coba ganti jadi 400px, 350px, dll. */
        height: auto;     /* Biarkan auto agar gambar tidak gepeng */
      }
    </style>
  </head>
  
  <body class="d-flex flex-column min-vh-100">
    
    <header class="py-3">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <div class="brand-name">
              <strong class="text">READCYCLE</strong>
          </div>
          <div>
            <a href="login.php" class="btn" style="font-family: Righteous;">login</a>
            <a href="register.php" class="btn btn-reg" style="font-family: Righteous;">sign for free</a>
          </div>
        </div>
      </div>
    </header>

    <main class="py-5 flex-grow-1">
      <div class="container text-center">
        <img src="assets/gambarBuku.PNG" alt="Ilustrasi Buku" class="img-fluid gambar-buku">
      </div>

      <div class="container mt-5"> <div class="row text-center">
          <div class="col-md-4 mb-4">
            <p>ReadCycle adalah komunitas tukar buku yang memudahkan siapa saja untuk saling berbagi dan menemukan buku bekas berkualitas.</p>
          </div>
          <div class="col-md-4 mb-4">
            <p>Temukan berbagai genre dari seluruh Indonesia, dan hubungi langsung pemilik buku lewat WhatsApp untuk proses tukar yang cepat dan praktis.</p>
          </div>
          <div class="col-md-4 mb-4">
            <p>Tujuan website ini adalah menghidupkan kembali buku-buku lama agar tetap bermanfaat dan mempererat hubungan antarpembaca di Indonesia.</p>
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
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>