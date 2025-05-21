<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #dbdbc5;
            color: #333;
            padding: 20px;
        }

        .container {
            width: 80%;
            margin-top: auto;
            max-width: 600px;
            margin: 0 auto;
            background: #f5f5df;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            animation: fadeIn 0.5s ease;
        }

        h3 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        a, button {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            font-size: 14px;
            color: #ffffff;
            background-color: #007bff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        a:hover, button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #d9534f;
            font-weight: bold;
        }

        .success {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    $conn = new mysqli("localhost", "root", "", "perpustakaan");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $kode_buku = $_POST['kode_buku'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    $sql = "SELECT * FROM peminjaman WHERE nama = '$nama' AND kelas = '$kelas' AND kode_buku = '$kode_buku'";
    $result = $conn->query($sql);

    echo "<div class='container'>";

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $tanggal_pinjam = $row['tanggal_pinjam'];

        $batas_pengembalian = date('Y-m-d', strtotime($tanggal_pinjam . ' + 7 days'));

        echo "<h3>Detail Peminjaman</h3>";
        echo "Nama: $nama<br>";
        echo "Kelas: $kelas<br>";
        echo "Kode Buku: $kode_buku<br>";
        echo "Tanggal Peminjaman: $tanggal_pinjam<br>";
        echo "Batas Pengembalian: $batas_pengembalian<br>";
        echo "Tanggal Pengembalian: $tanggal_kembali<br><br>";

        if ($tanggal_kembali > $batas_pengembalian) {
            echo "<p class='error'>Buku berhasil dikembalikan, namun Anda telah melebihi batas waktu pengembalian.</p>";
            echo "Silakan hubungi pengurus untuk membayar denda.<br>";
            echo "<a href='https://wa.me/6283879187067' target='_blank'>Hubungi pengurus di WhatsApp</a>";
        } else {
            $stmt = $conn->prepare("INSERT INTO pengembalian (nama, kelas, kode_buku, tanggal_kembali) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $kelas, $kode_buku, $tanggal_kembali);

            if ($stmt->execute()) {
                echo "<p class='success'>Buku berhasil dikembalikan!</p>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        echo "<p class='error'>Error: Data peminjaman tidak valid. Pastikan nama, kelas, dan kode buku benar.</p>";
    }

    echo "<a href='index.php'>Kembali ke Halaman Utama</a>";

    echo "</div>";

    $conn->close();
    ?>
</body>
</html>
