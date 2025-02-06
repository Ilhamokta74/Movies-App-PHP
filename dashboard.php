<?php
session_start();
require './connection.php';

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
    <!-- Header -->
    <header class="bg-black py-3 px-4 d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="index.php" class="text-white text-decoration-none">
                <h2>MOVIES APP</h2>
            </a>
        </div>

        <div class="d-flex gap-3">
            <a href="logout.php" class="btn btn-light text-dark px-3">Logout</a>
        </div>
    </header>

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard</h2>

        <!-- Search & Add Movie -->
        <div class="d-flex justify-content-between mb-3">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari judul..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-light">Search</button>
            </form>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMovieModal">
                Add Movie
            </button>
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
                    <?php $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['year']; ?></td>
                            <td><img src="./public/image/<?php echo $row['poster_url']; ?>" alt="Poster" width="50"></td>
                            <td><?php echo $row['slug']; ?></td>
                            <td><a href="<?php echo $row['video_url']; ?>" target="_blank" class="text-primary">Watch</a></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editMovieModal" data-id="<?php echo $row['id']; ?>" data-title="<?php echo $row['title']; ?>" data-year="<?php echo $row['year']; ?>" data-poster="<?php echo $row['poster_url']; ?>" data-video_url="<?php echo $row['video_url']; ?>" data-summary="<?php echo $row['summary']; ?>">
                                    Edit
                                </button>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Add Movie -->
    <div class="modal fade" id="addMovieModal" tabindex="-1" aria-labelledby="addMovieModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMovieModalLabel">Tambah Film</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMovieForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Input Judul Here...." required>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="year" name="year" placeholder="Input Year Here...." required>
                        </div>
                        <div class="mb-3">
                            <label for="poster" class="form-label">Poster</label>
                            <input type="file" class="form-control" id="poster" name="poster">
                        </div>
                        <div class="mb-3">
                            <label for="video_url" class="form-label">URL Video</label>
                            <input type="url" class="form-control" id="video_url" name="video_url" placeholder="Input URL Here...." required>
                        </div>
                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" id="summary" name="summary" rows="5" placeholder="Input Summary Here...." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Movie -->
    <div class="modal fade" id="editMovieModal" tabindex="-1" aria-labelledby="editMovieModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMovieModalLabel">Edit Film</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMovieForm" action="edit_movie.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="movieId" name="id">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="editTitle" name="title" placeholder="Input Judul Here...." required>
                        </div>
                        <div class="mb-3">
                            <label for="editYear" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="editYear" name="year" placeholder="Input Year Here...." required>
                        </div>
                        <div class="mb-3">
                            <label for="editPoster" class="form-label">Poster</label>
                            <input type="file" class="form-control" id="editPoster" name="poster">
                        </div>
                        <div class="mb-3">
                            <label for="editVideoUrl" class="form-label">URL Video</label>
                            <input type="url" class="form-control" id="editVideoUrl" name="video_url" placeholder="Input URL Here...." required>
                        </div>
                        <div class="mb-3">
                            <label for="editSummary" class="form-label">Summary</label>
                            <textarea class="form-control" id="editSummary" name="summary" rows="5" placeholder="Input Summary Here...." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle Add Movie Form
            $("#addMovieForm").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "add_movie.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            alert("Film berhasil ditambahkan!");
                            location.reload();
                        } else {
                            alert("Gagal menambahkan film!");
                        }
                    }
                });
            });

            // Populate Edit Movie Modal
            $('#editMovieModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var title = button.data('title');
                var year = button.data('year');
                var poster = button.data('poster');
                var videoUrl = button.data('video_url');
                var summary = button.data('summary');

                var modal = $(this);
                modal.find('#movieId').val(id);
                modal.find('#editTitle').val(title);
                modal.find('#editYear').val(year);
                modal.find('#editVideoUrl').val(videoUrl);
                modal.find('#editSummary').val(summary);
            });

            // Handle Edit Movie Form
            $("#editMovieForm").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "edit_movie.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            alert("Film berhasil diupdate!");
                            location.reload();
                        } else {
                            alert("Gagal mengupdate film!");
                        }
                    },
                });

                const movieId =document.getElementById(`movieId`);
                console.log(movieId);
            });
        });
    </script>
</body>

</html>