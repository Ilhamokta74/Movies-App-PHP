<?php
// Koneksi ke database PostgreSQL
$host = 'localhost'; // atau alamat host database Anda
$port = '5432'; // port default PostgreSQL
$dbname = 'movies_db'; // nama database Anda
$user = 'postgres'; // ganti dengan username database Anda
$password = 'ilham'; // ganti dengan password database Anda

// Membuat koneksi
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}
?>
