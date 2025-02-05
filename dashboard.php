<?php
session_start();
require './connection.php'; // Pastikan koneksi database sudah benar

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pencarian berdasarkan title
$search = isset($_GET['search']) ? $_GET['search'] : "";
$sql = "SELECT * FROM movies";
if (!empty($search)) {
    $sql .= " WHERE title LIKE ?";
}

$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("s", $searchParam);
}
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Movies App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">
    <div class="container mt-5">
        <h2 class="mb-4">Dashboard</h2>

        <!-- Search & Add Movie -->
        <div class="d-flex justify-content-between mb-3">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari judul..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-light">Search</button>
            </form>
            <a href="add.php" class="btn btn-success">Add Movie</a>
        </div>

        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Poster</th>
                    <th>Slug</th>
                    <th>URL Video</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php
                    $no = 1; // Inisialisasi nomor urut sebelum loop
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td> <!-- Menggunakan nomor urut biasa -->
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['year']; ?></td>
                            <td><img src="./public/image/<?php echo $row['poster_url']; ?>" alt="Poster" width="50"></td>
                            <td><?php echo $row['slug']; ?></td>
                            <td><a href="<?php echo $row['video_url']; ?>" target="_blank" class="text-primary">Watch</a></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="logout.php" class="btn btn-light">Logout</a>
    </div>
</body>

</html>