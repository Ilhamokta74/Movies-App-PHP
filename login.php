<?php
session_start();
require './connection.php'; // File konfigurasi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: dashboard.php"); // Redirect ke halaman dashboard
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movies App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card bg-secondary p-4 shadow-lg" style="width: 400px;">
            <h3 class="text-center">Login</h3>
            <?php if (isset($error)) {
                echo "<div class='alert alert-danger'>$error</div>";
            } ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="ilhamoktavian74@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" value="123456" required>
                </div>
                <button type="submit" class="btn btn-light w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <p>Belum punya akun? <a href="register.php" class="text-white">Daftar</a></p>
            </div>
        </div>
    </div>
</body>

</html>