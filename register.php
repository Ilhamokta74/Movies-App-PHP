<?php
// Koneksi ke database
include './connection.php';

$alertMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username atau email sudah ada
        $checkStmt = $conn->prepare("SELECT id FROM user WHERE username = ? OR email = ?");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $alertMessage = "<div class='alert alert-danger'>Username atau Email sudah terdaftar!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $alertMessage = "<div class='alert alert-success'>Registrasi berhasil! <a href='login.php' class='text-white'>Login</a></div>";
            } else {
                $alertMessage = "<div class='alert alert-danger'>Terjadi kesalahan: " . $stmt->error . "</div>";
            }
            $stmt->close();
        }
        $checkStmt->close();
    } else {
        $alertMessage = "<div class='alert alert-danger'>Password tidak cocok!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movies App</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('./public/background/bg-register.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="text-light">
    <main>
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card bg-secondary p-4 shadow-lg" style="width: 400px; background-color: rgba(0, 0, 0, 0.7);">
                <h3 class="text-center text-white">Register</h3>
                <?= $alertMessage ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label text-white">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label text-white">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                            <button class="btn btn-outline-light" type="button" onclick="togglePassword('password', 'togglePasswordIcon1')">
                                <i id="togglePasswordIcon1" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="confirmPassword" class="form-label text-white">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Ulangi Password" required>
                            <button class="btn btn-outline-light" type="button" onclick="togglePassword('confirmPassword', 'togglePasswordIcon2')">
                                <i id="togglePasswordIcon2" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="confirmPasswordError" class="text-danger" style="display:none;">Password tidak cocok!</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <p class="text-white">Sudah punya akun? <a href="login.php" class="text-decoration-none text-primary">Login</a></p>
                    </div>

                    <button type="submit" class="btn btn-light w-100">Daftar</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        // Validasi password real-time
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorElement = document.getElementById('confirmPasswordError');

            if (password !== confirmPassword) {
                errorElement.style.display = 'block';
                document.getElementById('confirmPassword').classList.add('is-invalid');
            } else {
                errorElement.style.display = 'none';
                document.getElementById('confirmPassword').classList.remove('is-invalid');
            }
        });
    </script>
</body>

</html>
