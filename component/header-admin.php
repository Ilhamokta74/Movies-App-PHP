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