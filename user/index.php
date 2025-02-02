<?php
// Menyertakan file koneksi
include '../connection.php';

// Mendapatkan kata kunci pencarian jika ada
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Membuat query berdasarkan apakah ada pencarian
$query = "SELECT * FROM movies";

if ($searchTerm) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $query .= " WHERE title LIKE '%" . $searchTerm . "%'"; // LIKE untuk pencarian case-insensitive di MySQL
}

$query .= " ORDER BY year DESC";

// Menjalankan query
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $movies[] = $row;
}

// Menutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YTS - HD Movies</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../style/index.css">
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

    <main class="main-content">
        <h2><i class="fas fa-star"></i> Popular Downloads</h2>
        <div class="popular-downloads">
            <?php if (empty($movies)): ?>
                <p>No movies found matching your search.</p>
            <?php else: ?>
                <?php foreach ($movies as $movie): ?>
                    <div class="movie">
                        <a href="detail.php?id=<?php echo $movie['id']; ?>">
                            <img alt="<?php echo htmlspecialchars($movie['title']); ?>" height="300" src="../public/image/<?php echo htmlspecialchars($movie['poster_url']); ?>" width="200" />
                            <p><?php echo htmlspecialchars($movie['title']); ?></p>
                            <p><?php echo htmlspecialchars($movie['year']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <p>© <?php echo date("Y"); ?> MOVIES APP. All rights reserved.</p>
    </footer>
</body>

</html>