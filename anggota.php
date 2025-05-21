<?php
// Kode PHP tetap sama
$host = "localhost";
$username = "root";
$password = "";
$database = "perpustakaan";

// Buat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi ambil data anggota
function getAnggota($conn, $search = "")
{
    $sql = "SELECT * FROM anggota";
    if (!empty($search)) {
        $sql .= " WHERE name LIKE ?";
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($search)) {
        $searchParam = "%" . $search . "%";
        $stmt->bind_param("s", $searchParam);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $anggota = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $anggota[] = $row;
        }
    }
    
    return $anggota;
}

$search = isset($_GET['search']) ? $_GET['search'] : "";
$anggota = getAnggota($conn, $search);
$logAktivitas = getLogAktivitas($conn);
// Fungsi untuk mengambil log aktivitas
function getLogAktivitas($conn) {
    $result = $conn->query("SELECT * FROM log_aktivitas ORDER BY waktu DESC");
    if (!$result) {
        die("Kesalahan kueri: " . $conn->error);
    }
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Fungsi agregasi
function getTotalAnggota($conn) {
    $result = $conn->query("SELECT COUNT(*) AS total FROM anggota");
    return $result->fetch_assoc()['total'];
}

function getJumlahPerKelas($conn) {
    $result = $conn->query("SELECT kelas, COUNT(*) AS jumlah FROM anggota GROUP BY kelas");
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

function getRataRataBulanan($conn) {
    $result = $conn->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS bulan, COUNT(*) AS jumlah FROM anggota GROUP BY bulan");
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

function getRataRataMingguan($conn) {
    $result = $conn->query("SELECT YEAR(created_at) AS tahun, WEEK(created_at) AS minggu, COUNT(*) AS jumlah FROM anggota GROUP BY tahun, minggu");
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Hapus data
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM anggota WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: anggota.php");
    exit();
}

// Tambah data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $nomor_hp = $_POST['nomor_hp'];

    $stmt = $conn->prepare("CALL tambahAnggota(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $kelas, $alamat, $nomor_hp);
    if ($stmt->execute()) {
        header("Location: anggota.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Tangani pencarian
$search = isset($_GET['search']) ? $_GET['search'] : "";
$anggota = getAnggota($conn, $search);
$logAktivitas = getLogAktivitas($conn);

// Tangani filter agregasi
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'total';
$hasilFilter = [];
switch ($filter) {
    case 'kelas':
        $hasilFilter = getJumlahPerKelas($conn);
        break;
    case 'bulan':
        $hasilFilter = getRataRataBulanan($conn);
        break;
    case 'minggu':
        $hasilFilter = getRataRataMingguan($conn);
        break;
    default:
        $hasilFilter = ['total' => getTotalAnggota($conn)];
        break;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Anggota Perpustakaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #dbdbc5 0%, #e8e8d0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 30px 20px;
        }

        /* Header Section */
        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            color: #3a3a27;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.8px;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            text-transform: uppercase;
        }

        header h1:after {
            content: '';
            position: absolute;
            width: 70%;
            height: 4px;
            background: linear-gradient(to right, #d11a1a, #ff4d4d);
            bottom: 0;
            left: 15%;
            border-radius: 2px;
        }

        /* Container Styles */
        main {
            width: 90%;
            max-width: 900px;
            background: #f5f5df;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.6s ease-out;
            backdrop-filter: blur(5px);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-size: 15px;
            font-weight: 600;
            color: #3a3a27;
            margin-bottom: 10px;
            display: block;
            letter-spacing: 0.3px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 1px solid #c5c5a6;
            border-radius: 8px;
            font-size: 15px;
            background-color: #ffffff;
            color: #3a3a27;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        input:focus,
        select:focus {
            border-color: #d11a1a;
            outline: none;
            box-shadow: 0 0 0 4px rgba(209, 26, 26, 0.15), 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Icons for Form Fields */
        .form-group::before {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 16px;
            top: 38px;
            color: #6d6d57;
            font-size: 18px;
            z-index: 1;
        }

        .form-group:nth-child(1)::before {
            content: "\f007"; /* User icon */
        }

        .form-group:nth-child(2)::before {
            content: "\f19d"; /* Graduation cap icon */
        }

        .form-group:nth-child(3)::before {
            content: "\f3c5"; /* Map marker icon */
        }

        .form-group:nth-child(4)::before {
            content: "\f095"; /* Phone icon */
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236d6d57' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 45px;
        }

        /* Button Styles */
        button[type="submit"],
        .btn-submit {
            background: linear-gradient(to right, #d11a1a, #ff4d4d);
            color: #fff;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(209, 26, 26, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        button[type="submit"]::before,
        .btn-submit::before {
            content: "\f0c7"; /* Save icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-right: 8px;
        }

        button[type="submit"]:hover,
        .btn-submit:hover {
            background: linear-gradient(to right, #b30f0f, #e63939);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(209, 26, 26, 0.4);
        }

        button[type="submit"]:active,
        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(209, 26, 26, 0.2);
        }

        /* Search Section */
        .search {
            margin-bottom: 30px;
        }

        .search form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .search input[type="text"] {
            flex: 1;
        }

        .search button {
            background: linear-gradient(to right, #d11a1a, #ff4d4d);
            color: #fff;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(209, 26, 26, 0.3);
        }

        .search button::before {
            content: "\f002"; /* Search icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-right: 8px;
        }

        .search button:hover {
            background: linear-gradient(to right, #b30f0f, #e63939);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(209, 26, 26, 0.4);
        }

        /* Filter Section */
        .filter {
            margin-bottom: 30px;
        }

        .filter select {
            max-width: 350px;
        }

        .filter p {
            font-size: 15px;
            color: #3a3a27;
            margin-top: 12px;
        }

        .filter p strong {
            font-weight: 700;
            color: #d11a1a;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            font-size: 14px;
            color: #3a3a27;
        }

        th {
            background: linear-gradient(to right, #d11a1a, #ff4d4d);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: #f9f9e7;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: #ffe6e6;
            transform: scale(1.01);
        }

        /* Action Buttons */
        .btn-edit,
        .btn-delete {
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            margin-right: 10px;
        }

        .btn-edit {
            background: linear-gradient(to right, #ffb107, #ffca2c);
            color: #3a3a27;
            border: none;
        }

        .btn-edit::before {
            content: "\f304"; /* Edit icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-right: 6px;
        }

        .btn-edit:hover {
            background: linear-gradient(to right, #e0a800, #f7b731);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(224, 168, 0, 0.4);
        }

        .btn-delete {
            background: linear-gradient(to right, #dc3545, #ff6666);
            color: #fff;
            border: none;
        }

        .btn-delete::before {
            content: "\f2ed"; /* Trash icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-right: 6px;
        }

        .btn-delete:hover {
            background: linear-gradient(to right, #c82333, #e63939);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(200, 35, 51, 0.4);
        }

        /* Typography */
        h2, h3 {
            color: #3a3a27;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        h2 {
            font-size: 26px;
        }

        h3 {
            font-size: 22px;
        }

        h2:after, h3:after {
            content: '';
            position: absolute;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #d11a1a, #ff4d4d);
            bottom: 0;
            left: 0;
            border-radius: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                width: 95%;
                padding: 25px;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px;
            }

            .btn-edit, .btn-delete {
                padding: 7px 12px;
                font-size: 13px;
            }

            .search form {
                flex-direction: column;
                align-items: stretch;
            }

            .search button {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 22px;
            }

            h3 {
                font-size: 18px;
            }

            .form-group input,
            .form-group select,
            .search button,
            .btn-submit {
                font-size: 14px;
                padding: 12px 14px 12px 40px;
            }

            .form-group::before {
                top: 36px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Manajemen Anggota Perpustakaan</h1>
    </header>
    <main>
        <h2>Form Tambah Anggota</h2>
        <form action="anggota.php" method="post">
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" required>
            </div>
            <div class="form-group">
                <label>Kelas:</label>
                <select name="kelas" required>
                    <option value="" disabled selected>Pilih kelas</option>
                    <option value="X PPLG A">X PPLG A</option>
                    <option value="X PPLG B">X PPLG B</option>
                    <option value="XI PPLG A">XI PPLG A</option>
                    <option value="XI PPLG B">XI PPLG B</option>
                    <option value="XII PPLG A">XII PPLG A</option>
                    <option value="XII PPLG B">XII PPLG B</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat:</label>
                <input type="text" name="alamat" required>
            </div>
            <div class="form-group">
                <label>Nomor HP:</label>
                <input type="text" name="nomor_hp" required pattern="\d+">
            </div>
            <button type="submit" name="save" class="btn-submit">Simpan</button>
        </form>

        <div class="filter">
            <h3>Filter Agregasi</h3>
            <form action="anggota.php" method="get">
                <label for="filter">Lihat data berdasarkan:</label>
                <select name="filter" onchange="this.form.submit()">
                    <option value="total" <?= $filter === 'total' ? 'selected' : '' ?>>Total Anggota</option>
                    <option value="kelas" <?= $filter === 'kelas' ? 'selected' : '' ?>>Jumlah per Kelas</option>
                    <option value="bulan" <?= $filter === 'bulan' ? 'selected' : '' ?>>Rata-rata Bulanan</option>
                    <option value="minggu" <?= $filter === 'minggu' ? 'selected' : '' ?>>Rata-rata Mingguan</option>
                </select>
            </form>

            <?php if ($filter === 'total'): ?>
                <p><strong>Total Anggota:</strong> <?= $hasilFilter['total'] ?></p>
            <?php elseif (!empty($hasilFilter)): ?>
                <table>
                    <thead>
                        <tr>
                            <?php if (!empty($hasilFilter)): ?>
                                <?php foreach (array_keys($hasilFilter[0]) as $key): ?>
                                    <th><?= ucfirst($key) ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasilFilter as $row): ?>
                            <tr>
                                <?php foreach ($row as $col): ?>
                                    <td><?= htmlspecialchars($col) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada data yang ditemukan.</p>
            <?php endif; ?>
        </div>

        <div class="search">
            <h3>Cari Anggota</h3>
            <form action="anggota.php" method="get">
                <input type="text" name="search" placeholder="Cari nama..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <h3>Daftar Anggota</h3>
        <?php if (!empty($anggota)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>Nomor HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anggota as $row): ?>
                        <tr>
                            <td><?= isset($row['name']) ? htmlspecialchars($row['name']) : '' ?></td>
                            <td><?= isset($row['kelas']) ? htmlspecialchars($row['kelas']) : '' ?></td>
                            <td><?= isset($row['address']) ? htmlspecialchars($row['address']) : '' ?></td>
                            <td><?= isset($row['phone']) ? htmlspecialchars($row['phone']) : '' ?></td>
                            <td>
                                <a href="edit_anggota.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                <a href="anggota.php?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada anggota yang ditemukan.</p>
        <?php endif; ?>

        <h3>Log Aktivitas</h3>
        <?php if (!empty($logAktivitas)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logAktivitas as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['aktivitas']) ?></td>
                            <td><?= htmlspecialchars($log['waktu']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada aktivitas yang ditemukan.</p>
        <?php endif; ?>
    </main>
    <script>
        // Tampilkan alert jika parameter success=1 ada di URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            alert('Berhasil ditambahkan!');
        }
    </script>
</body>
</html>