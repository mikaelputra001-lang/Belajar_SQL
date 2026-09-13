CREATE DATABASE IF NOT EXISTS db_rumah_sakit
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_rumah_sakit;

CREATE TABLE IF NOT EXISTS pasien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan'),
    nomor_telepon VARCHAR(15)
);
