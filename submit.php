<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nama_database"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$nama = $_POST['nama'];
$kelas = $_POST['kelas'];
$email = $_POST['email'];

$sql = "INSERT INTO login (nama, kelas, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nama, $kelas, $email);

if ($stmt->execute()) {
    header("Location: index.html");
    exit;
} else {
    echo "<script>alert('Gagal menyimpan data!'); window.location.href='login.html';</script>";
}

$stmt->close();
$conn->close();
?>
