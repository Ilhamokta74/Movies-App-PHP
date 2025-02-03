<?php
// Menyertakan file koneksi
include './connection.php';

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

        <form method="GET" action="" class="d-flex bg-secondary rounded p-1">
            <input name="search" class="form-control bg-transparent text-white border-0" placeholder="Quick search" type="text" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
            <button type="submit" class="btn btn-dark">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="d-flex gap-3">
            <a href="register.php" class="btn btn-outline-light px-3">Register</a>
            <a href="login.php" class="btn btn-light text-dark px-3">Login</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container text-center py-4">
        <h2><i class="fas fa-star"></i> Popular Downloads</h2>
        <div class="row justify-content-center">
            <?php if (empty($movies)): ?>
                <p class="text-muted">No movies found matching your search.</p>
            <?php else: ?>
                <?php foreach ($movies as $movie): ?>
                    <div class="col-md-3 col-sm-6 my-3">
                        <div class="card bg-secondary text-white shadow-lg h-100">
                            <a href="detail.php?id=<?php echo $movie['slug']; ?>" class="text-decoration-none text-white">
                                <img alt="<?php echo htmlspecialchars($movie['title']); ?>" class="card-img-top img-fluid" src="./public/image/<?php echo htmlspecialchars($movie['poster_url']); ?>" />
                                <div class="card-body d-flex flex-column">
                                    <p class="card-title fw-bold mb-1 text-center"><?php echo htmlspecialchars($movie['title']); ?></p>
                                    <p class="card-text text-center text-white"><?php echo htmlspecialchars($movie['year']); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black text-center py-3">
        <p>© <?php echo date("Y"); ?> MOVIES APP. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
