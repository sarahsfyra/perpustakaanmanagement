<?php
    session_start();
    if (isset($_SESSION['success'])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '" . $_SESSION['success'] . "'
            });
        </script>";
        unset($_SESSION['success']);
    }
    ?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Modern</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #dbdbc5;
            min-height: 100vh;
            color: #333;
        }

        header {
            width: 100%;
            position: relative;
            background-color: #f4e1e1;
            padding: 18px 0;
            text-align: center;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header h1 {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .user-icon {
            position: absolute;
            top: 50%;
            right: 25px;
            transform: translateY(-50%);
            width: 35px;
            height: 35px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .user-icon img {
            width: 20px;
            height: 20px;
            transition: transform 0.3s;
        }

        .user-icon:hover {
            background-color: #f0f0f0;
            transform: translateY(-50%) scale(1.1);
        }

        .image-container {
            width: 90%;
            max-width: 1000px;
            margin: 25px 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .gambar-perpus {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .gambar-perpus:hover {
            transform: scale(1.02);
        }

        .menu-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
            width: 90%;
            max-width: 800px;
        }

        .menu-item {
            padding: 15px 20px;
            background-color: white;
            text-decoration: none;
            color: #333;
            border: none;
            text-align: center;
            border-radius: 8px;
            transition: all 0.3s;
            flex: 1;
            min-width: 170px;
            max-width: 200px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }

        .menu-item:hover {
            background-color: #f4e1e1;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .info-container {
            width: 90%;
            max-width: 1000px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .info-container h2 {
            color: #555;
            margin-bottom: 15px;
            font-size: 22px;
            position: relative;
            display: inline-block;
        }
        
        .info-container h2:after {
            content: "";
            position: absolute;
            width: 50%;
            height: 3px;
            background-color: #f4e1e1;
            bottom: -8px;
            left: 25%;
        }

        .info-container p {
            color: #666;
            font-size: 16px;
        }

        .divider {
            width: 90%;
            max-width: 1000px;
            border: none;
            height: 2px;
            background-color: #f4e1e1;
            margin: 20px 0;
        }

        footer {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 20px 0 30px 0;
            width: 100%;
        }

        .icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .icon img {
            width: 20px;
            height: 20px;
        }

        .icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background-color: #f4e1e1;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .menu-container {
                gap: 15px;
            }

            .menu-item {
                min-width: 140px;
                padding: 12px 15px;
            }

            .info-container {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 20px;
            }

            .user-icon {
                right: 15px;
                width: 30px;
                height: 30px;
            }

            .menu-container {
                gap: 10px;
            }

            .menu-item {
                min-width: 100px;
                padding: 10px;
                font-size: 14px;
            }
            
            .info-container h2 {
                font-size: 18px;
            }
            
            .info-container p {
                font-size: 14px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    

    <header>
        <h1>Perpustakaan Digital</h1>
        
        <a href="login.html" class="user-icon">
            <img src="assets/user.png" alt="Login">
        </a>
    </header>

    <div class="image-container">
        <img src="assets/perpus.jpeg" alt="Gambar Perpustakaan" class="gambar-perpus">
    </div>

    <div class="menu-container">
        <a href="anggota.php" class="menu-item">
            Anggota Perpustakaan
        </a>
        <a href="peminjaman.html" class="menu-item">
            Peminjaman Buku
        </a>
        <a href="pengembalian.html" class="menu-item">
            Pengembalian Buku
        </a>
    </div>

    <div class="info-container">
        <h2>Informasi Sistem Perpustakaan</h2>
        <p><b>Sistem Perpustakaan Digital</b> mempermudah pengelolaan perpustakaan dengan fitur pendaftaran anggota, peminjaman, dan pengembalian buku secara online. Anggota dapat memesan buku dengan mudah dan menerima pengingat otomatis untuk pengembalian. Aplikasi ini dirancang responsif, sehingga dapat diakses kapan saja dan di mana saja untuk mendukung kemudahan pengguna.</p>
    </div>
    
    <hr class="divider">
    
    <footer>
        <a href="https://instagram.com/sarahsfyra" target="_blank" class="icon">
            <img src="assets/ig.png" alt="Instagram">
        </a>
        <a href="https://wa.me/085888688457" target="_blank" class="icon">
            <img src="assets/wa.png" alt="WhatsApp">
        </a>
    </footer>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login') === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Login berhasil!'
            });
        }

        window.onload = function() {
            // Mengecek jika ada parameter 'status=deleted' di URL
            if (urlParams.get('status') === 'deleted') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus!'
                });
            }
        }
    </script>
</body>
</html>