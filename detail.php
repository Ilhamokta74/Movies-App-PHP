<?php
// Koneksi ke database
include './connection.php';

// Mendapatkan ID film dari URL
$movie_id = isset($_GET['id']) ? $_GET['id'] : null;

// Jika ID tidak ada, redirect ke halaman utama
if (!$movie_id) {
    header('Location: index.php');
    exit;
}

// Mengambil data film berdasarkan ID menggunakan prepared statement
$query = "SELECT * FROM movies WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $movie_id); // "i" menandakan integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$movie = mysqli_fetch_assoc($result);

// Menutup koneksi database
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Mengekstrak video ID dari URL YouTube
// $video_url = $movie['trailer_url']; // Pastikan kolom 'trailer_url' berisi URL embed YouTube
$video_url = 'https://www.youtube.com/embed/rWsnLS0Q7G0';
$parsed_url = parse_url($video_url);
$video_id = basename($parsed_url['path']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo htmlspecialchars($movie['title']); ?> - MOVIES APP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./style/detail.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="index.php">
                <h2>MOVIES APP</h2>
            </a>
        </div>

        <div class="search-bar">
            <form method="GET" action="">
                <input name="search" placeholder="Quick search" type="text" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </header>
    <main>
        <div class="container">
            <!-- Menyematkan video YouTube berdasarkan video ID -->
            <div class="video-container">
                <iframe width="1280" height="720" src="https://www.youtube.com/embed/<?php echo htmlspecialchars($video_id); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>

        <div class="movie-details">
            <h2>Plot Summary</h2>
            <p>Kaluna, a middle-class worker living with her parents and married siblings, dreams of owning her own house. However, supporting her extended family on a minimal income leaves her feeling out of place at home.</p>
        </div>
    </main>

    <footer class="footer">
        <p>© <?php echo date("Y"); ?> MOVIES APP. All rights reserved.</p>
    </footer>
</body>

</html>