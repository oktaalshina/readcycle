-- FILE: readcycle_schema.sql
-- Digunakan untuk membuat database dan tabel yang diperlukan oleh proyek READCYCLE

-- 1. DROP DATABASE (opsional, jika ingin menghapus yang lama dan membuat yang baru)
-- DROP DATABASE IF EXISTS readcycle;

-- 2. CREATE DATABASE
-- CREATE DATABASE IF NOT EXISTS readcycle CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Gunakan database yang baru dibuat
USE readcycle;

-- 3. CREATE TABLE users
-- Digunakan untuk menyimpan data user (terlihat dari register.php dan login.php)
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Untuk menyimpan hash password (password_hash)
    phone VARCHAR(15) NOT NULL,
    PRIMARY KEY (id)
);

-- 4. CREATE TABLE provinces
-- Digunakan untuk data lokasi/provinsi (terlihat dari posting.php dan timeline.php)
CREATE TABLE provinces (
    id INT(11) NOT NULL AUTO_INCREMENT,
    provinceName VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

-- 5. CREATE TABLE genre
-- Digunakan untuk data genre buku (terlihat dari posting.php dan timeline.php)
CREATE TABLE genre (
    id INT(11) NOT NULL AUTO_INCREMENT,
    genreName VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

-- 6. CREATE TABLE books
-- Tabel utama untuk postingan buku (terlihat dari posting.php, timeline.php, ubah_status.php, hapus.php)
CREATE TABLE books (
    id INT(11) NOT NULL AUTO_INCREMENT,
    ownerId INT(11) NOT NULL,
    provinceId INT(11) NOT NULL,
    genreId INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT,
    bookCondition VARCHAR(50) NOT NULL, -- e.g., 'Good', 'Average', 'Damaged'
    isAvailable TINYINT(1) NOT NULL DEFAULT 1, -- 1=Available, 0=Not Available
    lookingFor VARCHAR(255) NOT NULL,
    language VARCHAR(50) NOT NULL,
    imageUrl VARCHAR(255) NULL, -- Menyimpan nama file gambar
    createdAt DATETIME NOT NULL,
    PRIMARY KEY (id),
    
    -- Foreign Keys
    FOREIGN KEY (ownerId) REFERENCES users(id),
    FOREIGN KEY (provinceId) REFERENCES provinces(id),
    FOREIGN KEY (genreId) REFERENCES genre(id)
);

-- 7. CREATE TABLE comments
-- Digunakan untuk menyimpan komentar pada buku (terlihat dari timeline.php)
-- Catatan: Menggunakan ON DELETE CASCADE agar komentar ikut terhapus saat buku dihapus (seperti yang diindikasikan di hapus.php)
CREATE TABLE comments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    bookId INT(11) NOT NULL,
    userId INT(11) NOT NULL,
    comment TEXT NOT NULL,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    
    -- Foreign Keys
    FOREIGN KEY (bookId) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(id)
);


-- 8. DATA AWAL (SEEDING)

-- Data Dummy untuk Genre
INSERT INTO genre (genreName) VALUES
('Fiksi'),
('Non-Fiksi'),
('Fantasi'),
('Misteri'),
('Romance'),
('Sains'),
('Sejarah'),
('Anak-Anak'),
('Horor');

-- Data Dummy untuk Provinces (Hanya contoh, bisa Anda ganti)
INSERT INTO provinces (provinceName) VALUES
('DKI Jakarta'),
('Jawa Barat'),
('Jawa Tengah'),
('DI Yogyakarta'),
('Jawa Timur'),
('Bali'),
('Sumatera Utara'),
('Sulawesi Selatan');
