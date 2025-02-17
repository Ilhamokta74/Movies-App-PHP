<?php
// Koneksi ke database
include './connection/connection.php';

// Mendapatkan ID film dari URL
$movie_id = isset($_GET['id']) ? $_GET['id'] : null;

// Jika ID tidak ada, redirect ke halaman utama
if (!$movie_id) {
    header('Location: index.php');
    exit;
}

// Mengambil data film berdasarkan ID menggunakan prepared statement
$query = "SELECT * FROM movies WHERE slug = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $movie_id); // "s" menandakan string
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$movie = mysqli_fetch_assoc($result);

// Menutup koneksi database
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Mengekstrak video ID dari URL YouTube jika ada
$video_url = isset($movie['video_url']) ? $movie['video_url'] : '';
$video_id = '';

if ($video_url) {
    $parsed_url = parse_url($video_url);
    if (isset($parsed_url['path'])) {
        $video_id = basename($parsed_url['path']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo htmlspecialchars($movie['title']); ?> - MOVIES APP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">
    <!-- Header -->
    <header class="bg-black py-3 px-4 d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="index.php" class="text-white text-decoration-none">
                <h2>MOVIES APP</h2>
            </a>
        </div>

        <div class="d-flex gap-3">
            <a href="register.php" class="btn btn-outline-light px-3">Register</a>
            <a href="login.php" class="btn btn-light text-dark px-3">Login</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container py-4">
        <!-- Menyematkan video YouTube berdasarkan video ID -->
        <?php if ($video_id): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <iframe width="100%" height="500" src="https://www.youtube.com/embed/<?php echo htmlspecialchars($video_id); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted">Video tidak tersedia.</p>
        <?php endif; ?>

        <div class="movie-details">
            <h2>Plot Summary</h2>
            <p><?php echo nl2br(htmlspecialchars($movie['summary'])); ?></p>
        </div>
    </main>

    <?php
    // Menampilkan Footer
    include './component/footer.php'
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>