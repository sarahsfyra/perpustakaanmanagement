<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "perpustakaan");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $kode_buku = $_POST['kode_buku'];

    if (!preg_match("/^B[0-9]+$/", $kode_buku)) {
        echo "<script>alert('Kode buku tidak valid. Kode buku harus diawali dengan huruf B diikuti angka.'); window.history.back();</script>";
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO peminjaman (nama, kelas, tanggal_pinjam, kode_buku) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $kelas, $tanggal_pinjam, $kode_buku);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil meminjam buku!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal meminjam buku. Silakan coba lagi.'); window.history.back();</script>";
    }
    $stmt->close();
}

$conn->close();
?>
