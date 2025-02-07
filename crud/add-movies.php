<?php
include '../connection/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $slug = str_replace(" ", "-", strtolower($title)); // Slug lebih konsisten (lowercase)
    $video_url = $_POST['video_url'];
    
    // Mengambil dan men-sanitasi input dari textarea (summary)
    $summary = isset($_POST['summary']) ? htmlspecialchars($_POST['summary'], ENT_QUOTES, 'UTF-8') : '';

    // **1. Cek apakah slug sudah ada di database**
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM movies WHERE slug = ?");
    $checkStmt->bind_param("s", $slug);
    $checkStmt->execute();
    $checkStmt->bind_result($slugCount);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($slugCount > 0) {
        echo json_encode(["success" => false, "message" => "Film sudah ada di database!"]);
        exit;
    }

    // **2. Upload poster jika ada**
    $poster_url = "";
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $poster_name = time() . "_" . basename($_FILES['poster']['name']);
        $target_dir = "../public/image/";
        $target_file = $target_dir . $poster_name;

        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            $poster_url = $poster_name;
        } else {
            echo json_encode(["success" => false, "message" => "Gagal mengunggah poster."]);
            exit;
        }
    }

    // **3. Insert data ke database**
    $stmt = $conn->prepare("INSERT INTO movies (title, year, poster_url, slug, video_url, summary) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $title, $year, $poster_url, $slug, $video_url, $summary);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menyimpan ke database: " . $stmt->error]);
    }

    $stmt->close();
}
?>
