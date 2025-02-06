<?php
session_start();
include '../connection/connection.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek jika ada id yang dikirimkan
if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];

    // Hapus poster terkait film, jika ada
    $stmt = $conn->prepare("SELECT poster_url FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $poster_url = $row['poster_url'];
        // Cek jika file poster ada, lalu hapus
        if (file_exists("./public/image/" . $poster_url)) {
            unlink("./public/image/" . $poster_url);
        }
    }

    // Hapus film dari database
    $deleteStmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $deleteStmt->bind_param("i", $movie_id);
    
    if ($deleteStmt->execute()) {
        echo "<script>alert('Film berhasil dihapus!'); window.location.href='/website-film/dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus film!'); window.location.href='/website-film/dashboard.php';</script>";
    }
} else {
    echo "<script>alert('ID film tidak ditemukan!'); window.location.href='/website-film/dashboard.php';</script>";
}
?>
