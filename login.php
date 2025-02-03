<!DOCTYPE html>
<html lang="en">

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

    <main>
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card bg-secondary p-4 shadow-lg" style="width: 400px; background-color: rgba(0, 0, 0, 0.7);">
                <h3 class="text-center text-white">Login</h3>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $confirmPassword = $_POST['confirmPassword'];

                    if ($password === $confirmPassword) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        $conn = new mysqli("localhost", "root", "", "movies_app");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $username, $email, $hashedPassword);

                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Registration successful!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
                        }

                        $stmt->close();
                        $conn->close();
                    } else {
                        echo "<div class='alert alert-danger'>Passwords do not match!</div>";
                    }
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Input Email Here" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label text-white">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Input Password Here" required>
                            <button class="btn btn-outline-light" type="button" onclick="togglePassword('password', 'togglePasswordIcon1')">
                                <i id="togglePasswordIcon1" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <p class="text-white">No Have Account ? <a href="register.php" class="text-decoration-none text-primary">Register</a></p>
                    </div>

                    <button type="submit" class="btn btn-light w-100">Login</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black text-center py-3">
        <p>© <?php echo date("Y"); ?> MOVIES APP. All rights reserved.</p>
    </footer>

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

        // Real-time password match validation
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