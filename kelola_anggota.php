<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = ''; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Anggota</title>
    <style>
     * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color:  #dbdbc5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: #f5f5df;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
        }

        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        button {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            min-width: 120px;
        }

        .btn-delete {
            background-color: #ff4757;
            color: white;
        }

        .btn-delete:hover {
            background-color: #ff6b81;
            transform: translateY(-2px);
        }

        .btn-edit {
            background-color: #1e90ff;
            color: white;
        }

        .btn-edit:hover {
            background-color: #40a9ff;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: #2ecc71;
            color: white;
        }

        .btn-back:hover {
            background-color: #3ae374;
            transform: translateY(-2px);
        }

        /* Responsiveness */
        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }

        button:active {
            transform: scale(0.95);
        }

        .container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        button:focus {
            outline: 3px solid rgba(30, 144, 255, 0.5);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kelola Data Anggota</h1>
        <p>Data anggota berhasil disimpan. Apa yang ingin Anda lakukan selanjutnya?</p>

        <div class="button-group">
            <form action="delete_anggota.php" method="POST" style="flex: 1;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"> <!-- Pastikan $id diisi -->
                <button type="submit" class="btn-delete" return confirm="Yakin anda ingin menghapus data anggota yang telah di daftar?">Hapus Anggota</button>
            </form>

            <form action="edit_anggota.php" method="GET" style="flex: 1;" onsubmit="return confirmDelete()">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"> <!-- Pastikan $id diisi -->
                <button type="submit" class="btn-edit">Edit Anggota</button>
            </form>
        </div>        
        <br>
        <button class="btn-back" onclick="window.location.href='index.php'">Kembali ke Halaman Utama</button>
    </div>

    
</body>
</html>
