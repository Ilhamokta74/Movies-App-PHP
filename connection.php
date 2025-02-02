<?php
// Koneksi ke database MySQL
$host = 'localhost'; // atau alamat host database Anda
$port = '3306'; // port default MySQL
$dbname = 'movies_db'; // nama database Anda
$user = 'root'; // ganti dengan username database Anda
$password = ''; // ganti dengan password database Anda

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $dbname, $port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// echo "Koneksi berhasil!";
?>
