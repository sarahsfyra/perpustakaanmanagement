<?php
$conn = new mysqli("localhost", "root", "", "perpustakaan");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (nama, kelas, email, password) VALUES ('$nama', '$kelas', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.html?register=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>