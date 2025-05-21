<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perpustakaan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM anggota WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: anggota.html");
        exit();
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID anggota tidak ditemukan!";
}

$conn->close();
?>