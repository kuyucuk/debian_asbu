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

        <style>
        /* Genel Sayfa Stili */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Giri� Formu */
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: white;
        }

        .login-button {
            background: #007bff;
        }

        .login-button:hover {
            background: #0056b3;
        }

        /* Alt Ba�lant�lar */
        .extra-links {
            margin-top: 20px;
            font-size: 14px;
        }

        .extra-links a {
            text-decoration: none;
            color: #007bff;
            transition: 0.3s;
        }

        .extra-links a:hover {
            color: #0056b3;
        }

        

    </style>

</head>
<body>
    <div class="login-container">
        <h2>Lütfen ASBÜ kullanıcı bilgileriniz ile giriş yapınız</h2>
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
