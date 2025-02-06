<?php
// Menyertakan file koneksi
include './connection/connection.php';

// Memastikan koneksi ke database berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mendapatkan kata kunci pencarian jika ada
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Membuat query berdasarkan apakah ada pencarian
$query = "SELECT * FROM movies";
if ($searchTerm) {
    $searchTerm = '%' . mysqli_real_escape_string($conn, $searchTerm) . '%';
    $query .= " WHERE title LIKE ?"; // Menggunakan placeholder untuk prepared statement
}

$query .= " ORDER BY year DESC";

// Menyiapkan query dan mengikat parameter
$stmt = mysqli_prepare($conn, $query);

if ($searchTerm) {
    mysqli_stmt_bind_param($stmt, 's', $searchTerm); // Mengikat parameter pencarian
}

// Menjalankan query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

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
    <?php
    // Menampilkan Header
    include './component/header-user.php'
    ?>

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
                            <a href="detail.php?id=<?php echo htmlspecialchars($movie['slug']); ?>" class="text-decoration-none text-white">
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

    <?php
    // Menampilkan Footer
    include './component/footer.php'
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>