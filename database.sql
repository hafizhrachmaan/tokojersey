DROP DATABASE IF EXISTS tokojersey;
CREATE DATABASE tokojersey;
USE tokojersey;

-- Tabel kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
);

-- Tabel produk
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    id_kategori INT,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);

-- Tabel users (bukan user)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    email VARCHAR(100),
    no_hp VARCHAR(15),
    alamat TEXT
);

-- Tabel pesanan (harus sebelum detail_pesanan)
CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_users INT,
    tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Proses', 'Selesai') DEFAULT 'Pending',
    total DECIMAL(10,2),
    FOREIGN KEY (id_users) REFERENCES users(id)
);

-- Tabel keranjang
CREATE TABLE keranjang (
    id_keranjang INT AUTO_INCREMENT PRIMARY KEY,
    id_users INT,
    id_produk INT,
    jumlah INT DEFAULT 1,
    FOREIGN KEY (id_users) REFERENCES users(id),
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
);

-- Tabel detail_pesanan
CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT,
    id_produk INT,
    jumlah INT,
    harga DECIMAL(10,2),
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
);

-- Tabel testimoni
CREATE TABLE testimoni (
    id_testimoni INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    isi TEXT,
    rating INT CHECK (rating BETWEEN 1 AND 5)
);

-- Data kategori
INSERT INTO kategori (nama_kategori) VALUES
('Jersey'),
('Accessories');

-- Data produk
INSERT INTO produk (nama_produk, deskripsi, harga, gambar, id_kategori) VALUES
('Premium Jersey - Barca Retro', 'Official Jersey Retro Barcelona', 94.99, 'image/barca1.png', 1),
('Premium Jersey - Bayern Retro', 'Official Jersey Retro Bayern Munchen', 94.99, 'image/bayern1.png', 1),
('Premium Accessories - Madrid', 'Official Team Accessories Madrid', 150.00, 'image/madrid1.png', 2),
('Premium Accessories - Milan', 'Official Team Accessories Milan', 37.79, 'image/milan.png', 2);
