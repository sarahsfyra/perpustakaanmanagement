<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perpustakaan";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $kelas = $_POST['kelas'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO anggota (name, kelas, address, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $kelas, $address, $phone);
    $stmt->execute();
    $stmt->close();
    
    header("Location: kelola_anggota.php?id=" . $conn->insert_id);
    exit();
}

$conn->close();
?>
