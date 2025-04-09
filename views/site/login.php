<?php
#session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Geçersiz giriş bilgileri!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <div class="login-container">
        <h2>ASBÜ kullanıcı bilgileriniz ile giriş yapınız</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="E-posta" required><br>
            <input type="password" name="password" placeholder="Şifre" required><br>
            <button type="submit" class="login-button">Giriş Yap</button>
        </form>

        <!-- Kayıt Ol & Şifre Sıfırlama -->
        <div class="extra-links">
            <p><a href="register.php">Kayıt Ol</a> | <a href="forgot_password.php">Şifremi Unuttum</a></p>
        </div>
    </div>
</body>
</html>
