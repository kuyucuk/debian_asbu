<?php
/** @var yii\web\View $this */

$this->title = 'PPD';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <!-- Logo eklendi -->
            <a href="https://www.asbu.edu.tr" class="d-inline-block">
                <img src="/images/new-asbu-logo-tr.jpg" alt="Üniversite Logosu" style="height: 100px; margin-right: 30px;">
            </a>
        </div>
    </div>

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
            <h2>İDARİ PERSONEL PERFORMANS ÖLÇÜMÜ</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST">
                <input type="email" name="email" placeholder="E-posta" required><br>
                <input type="password" name="password" placeholder="Şifre" required><br>
                <button type="submit" class="login-button">Giriş Yap</button>
            </form>

            <!-- Kayıt Ol & Şifre Sıfırlama -->
            <div class="extra-links">
                <p> <a href="forgot_password.php">Şifremi Unuttum</a></p>
            </div>
        </div>
    </body>
    </html>

    <!-- 50px boşluk eklemek için aşağıdaki stil eklendi -->
    <div style="height: 100px;"></div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Son Kullanıcı Aşaması</h2>

                <p>Çalışanlar sistemden bilgilerini çekip sertifikalarını yükleyebilir.</p>

                <p><a class="btn btn-outline-secondary" href="/index.php?r=site/personelpaneli">Giriş &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Yönetici Paneli</h2>

                <p>Yönetici olarak giriş yapıldığında bu sayfa açılacak</p>

                <p><a class="btn btn-outline-secondary" href="/index.php?r=site/yoneticipaneli">Giriş &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Genel Değerlendirme Aşaması</h2>

                <p>Puanlar toplanıp raporlanır.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/extensions/">Eklentiler &raquo;</a></p>
            </div>
        </div>
    </div>
</div>
