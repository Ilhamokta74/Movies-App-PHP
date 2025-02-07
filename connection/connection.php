<?php
// Koneksi ke database MySQL
$host = 'sql102.infinityfree.com'; // atau alamat host database Anda
$port = '3306'; // port default MySQL
$dbname = 'if0_38225372_moviesdb'; // nama database Anda
$user = 'if0_38225372'; // ganti dengan username database Anda
$password = 'tN73iqK8MSI'; // ganti dengan password database Anda

// Membuat koneksi
$conn = mysqli_connect("localhost", "root", "", "movies_db", $port);

// // Untuk Hosting (InfinityFree)
// $conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// echo "Koneksi berhasil!";
?>
