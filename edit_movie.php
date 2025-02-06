<?php
require './connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Mengambil data dari form
    $id = $_POST['id']; // ID film yang ingin diperbarui
    $title = $_POST['title'];
    $year = $_POST['year'];
    $slug = str_replace(" ", "-", strtolower($title)); // Slug lebih konsisten (lowercase)
    $video_url = $_POST['video_url'];
    
    // Mengambil dan men-sanitasi input dari textarea (summary)
    $summary = isset($_POST['summary']) ? htmlspecialchars($_POST['summary'], ENT_QUOTES, 'UTF-8') : '';

    // **1. Cek apakah slug sudah ada di database untuk film lain**
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM movies WHERE slug = ? AND id != ?");
    $checkStmt->bind_param("si", $slug, $id);
    $checkStmt->execute();
    $checkStmt->bind_result($slugCount);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($slugCount > 0) {
        echo json_encode(["success" => false, "message" => "Slug sudah ada di database untuk film lain!"]);
        exit;
    }

    // **2. Upload poster jika ada**
    $poster_url = "";
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $poster_name = time() . "_" . basename($_FILES['poster']['name']);
        $target_dir = "./public/image/";
        $target_file = $target_dir . $poster_name;

        if (move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            $poster_url = $poster_name;
        } else {
            echo json_encode(["success" => false, "message" => "Gagal mengunggah poster."]);
            exit;
        }
    } else {
        // Jika poster tidak diupload, gunakan poster lama (ambil dari database)
        $stmt = $conn->prepare("SELECT poster_url FROM movies WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($existingPoster);
        $stmt->fetch();
        $stmt->close();

        // Jika tidak ada poster baru, gunakan poster lama
        $poster_url = $existingPoster;
    }

    // **3. Update data ke database**
    $stmt = $conn->prepare("UPDATE movies SET title = ?, year = ?, poster_url = ?, slug = ?, video_url = ?, summary = ? WHERE id = ?");
    $stmt->bind_param("sissssi", $title, $year, $poster_url, $slug, $video_url, $summary, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal memperbarui data ke database: " . $stmt->error]);
    }

    $stmt->close();
}
?>
