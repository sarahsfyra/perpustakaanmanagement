<?php
$conn = new mysqli("localhost", "root", "", "perpustakaan");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            header("Location: index.php?login=success");
            exit();
        } else {
            // pw salah
            header("Location: login.php?error=wrong_password");
            exit();
        }
    } else {
        header("Location: login.php?error=email_not_found");
        exit();
    }
}
$conn->close();
?>
