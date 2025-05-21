<?php 
session_start(); // Mulai session di awal file  

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "perpustakaan";  

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);  

if ($conn->connect_error) {     
    die("Koneksi gagal: " . $conn->connect_error); 
}  

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {     
    $id = $_POST['id'];     
    $name = htmlspecialchars($_POST['name']);     
    $kelas = htmlspecialchars($_POST['kelas']);     
    $address = htmlspecialchars($_POST['address']);     
    $phone = htmlspecialchars($_POST['phone']);      
    
    $stmt = $conn->prepare("UPDATE anggota SET name = ?, kelas = ?, address = ?, phone = ? WHERE id = ?");     
    $stmt->bind_param("ssssi", $name, $kelas, $address, $phone, $id);      
    
    if ($stmt->execute()) {         
        $_SESSION['success'] = "Data anggota berhasil diperbarui!";         
        header("Location: index.php");         
        exit();     
    } else {         
        $_SESSION['error'] = "Gagal memperbarui data: " . $stmt->error;     
    }     
    $stmt->close(); 
}  

// Ambil data anggota berdasarkan ID
if (isset($_GET['id'])) {     
    $id = $_GET['id'];     
    $stmt = $conn->prepare("SELECT * FROM anggota WHERE id = ?");     
    $stmt->bind_param("i", $id);     
    $stmt->execute();     
    $result = $stmt->get_result();      
    
    if ($result->num_rows > 0) {         
        $row = $result->fetch_assoc();     
    } else {         
        echo "Anggota tidak ditemukan!";         
        exit();     
    }     
    $stmt->close(); 
} else {     
    echo "ID anggota tidak ditemukan!";     
    exit(); 
}  

$conn->close(); 
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Edit Anggota</title>     
    <!-- Tambahkan SweetAlert2 -->     
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #dbdbc5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #557c42;
            text-align: center;
            margin-bottom: 20px;
        }

        .divider {
            width: 90%;
            border: none;
            border-top: 4px solid #bbb;
            margin: 10px 0;
        }

        main {
            width: 90%;
            max-width: 400px;
            background-color: #f5f5df;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            color: #4b4b31;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        input:focus, select:focus {
            border-color: #888;
            outline: none;
        }

        button[type="submit"] {
            background-color: #d11a1a;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #b30f0f;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 20px;
            }

            main {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }

            label {
                font-size: 12px;
            }

            input,
            select {
                font-size: 12px;
            }

            button[type="submit"] {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head> 
<body>     
    <h1>Edit Anggota</h1>     
    <hr class="divider"><br>

    <?php     
    // Tampilkan pesan error jika ada     
    if (isset($_SESSION['error'])) {         
        echo "<script>             
            Swal.fire({                 
                icon: 'error',                 
                title: 'Error!',                 
                text: '" . $_SESSION['error'] . "'             
            });         
        </script>";         
        unset($_SESSION['error']);     
    }     
    ?>      

    <main>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id; ?>" method="POST">         
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">         
            
            <div class="form-group">
                <label for="name">Nama:</label>         
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="kelas">Kelas:</label>         
                <select id="kelas" name="kelas" required>             
                    <option value="X PPLG A" <?php if ($row['kelas'] == "X PPLG A") echo "selected"; ?>>X PPLG A</option>             
                    <option value="X PPLG B" <?php if ($row['kelas'] == "X PPLG B") echo "selected"; ?>>X PPLG B</option>             
                    <option value="XI PPLG A" <?php if ($row['kelas'] == "XI PPLG A") echo "selected"; ?>>XI PPLG A</option>             
                    <option value="XI PPLG B" <?php if ($row['kelas'] == "XI PPLG B") echo "selected"; ?>>XI PPLG B</option>             
                    <option value="XII PPLG A" <?php if ($row['kelas'] == "XII PPLG A") echo "selected"; ?>>XII PPLG A</option>             
                    <option value="XII PPLG B" <?php if ($row['kelas'] == "XII PPLG B") echo "selected"; ?>>XII PPLG B</option>         
                </select>
            </div>

            <div class="form-group">
                <label for="address">Alamat:</label>         
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">No. Telp:</label>         
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
            </div>

            <button type="submit" name="save">Simpan Perubahan</button>     
        </form>
    </main>
</body> 
</html>