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
            .login-container {
                text-align: center;
                margin: 0 auto;
                max-width: 400px;
            }
            .toggle-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: 10px;
            }
            .toggle-label {
                margin-left: 10px;
            }
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
                border-radius: 34px;
            }
            .slider:before {
                position: absolute;
                content: "";
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                transition: .4s;
                width: 20px;
                height: 20px;
                background-color: white;
                border-radius: 50%;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
            input:checked + .slider {
                background-color: #2196F3;
            }
            input:checked + .slider:before {
                left: 35px;
            }
            .panels-container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
            .panel {
                text-align: center;
                margin: 0 20px;
                display: none;
            }
            .panel h2 {
                color: #793657;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>İDARİ PERSONEL PERFORMANS ÖLÇÜMÜ</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST">
                <input type="email" name="email" placeholder="E-posta" required><br>
                <input type="password" name="password" placeholder="Şifre" required><br>
                
                <!-- Toggle Switch -->
                <div class="toggle-container">
                    <label class="switch">
                        <input type="checkbox" id="roleToggle">
                        <span class="slider round"></span>
                    </label>
                    <span class="toggle-label" id="roleLabel">Personel</span>
                </div>

                <button type="submit" class="login-button">Giriş Yap</button>
            </form>

            <!-- Kayıt Ol & Şifre Sıfırlama -->
            <div class="extra-links">
                <p><a href="forgot_password.php">Şifremi Unuttum</a></p>
            </div>
        </div>

        <!-- Panels -->
        <div class="panels-container">
            <div id="personelPanel" class="panel">
                <a href="/index.php?r=site/personelpaneli" class="text-decoration-none">
                    <h2>Personel Paneli</h2>
                </a>
                <p>Personel olarak giriş yapıldığında bu sayfa açılacak</p>
            </div>
            <div id="yoneticiPanel" class="panel">
                <a href="/index.php?r=site/yoneticipaneli" class="text-decoration-none">
                    <h2>Yönetici Paneli</h2>
                </a>
                <p>Yönetici olarak giriş yapıldığında bu sayfa açılacak</p>
            </div>
        </div>

        <script>
            const roleToggle = document.getElementById('roleToggle');
            const roleLabel = document.getElementById('roleLabel');
            const personelPanel = document.getElementById('personelPanel');
            const yoneticiPanel = document.getElementById('yoneticiPanel');

            function updatePanels() {
                if (roleToggle.checked) {
                    roleLabel.textContent = 'Yönetici';
                    personelPanel.style.display = 'none';
                    yoneticiPanel.style.display = 'block';
                } else {
                    roleLabel.textContent = 'Personel';
                    personelPanel.style.display = 'block';
                    yoneticiPanel.style.display = 'none';
                }
            }

            roleToggle.addEventListener('change', updatePanels);

            // Initialize panels on page load
            updatePanels();
        </script>
    </body>
    </html>