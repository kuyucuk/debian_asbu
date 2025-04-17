<?php
/** @var yii\web\View $this */

$this->title = '';
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

                <div class="button-container">
                    <button type="button" onclick="redirectToPanel();" style="background-color: blue; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Hızlı Giriş</button>
                    <script>
                        function redirectToPanel() {
                            const roleToggle = document.getElementById('roleToggle');
                            if (roleToggle.checked) {
                                window.location.href = '/index.php?r=site%2Fkullaniciyonetimi';
                            } else {
                                window.location.href = '/index.php?r=self-assessment%2Findex';
                            }
                        }
                    </script>
                    <button type="submit" class="login-button">Giriş Yap</button>
                    <a href="/index.php?r=site/sifreunutma">Şifremi Unuttum</a>
                </div>
            </form>
        </div>

        <!-- Panels -->
        <div class="panels-container">
            <div id="personelPanel" class="panel">
                <a href="<?= \yii\helpers\Url::to(['self-assessment/index']) ?>" class="text-decoration-none">
                    <h2>Personel Paneli</h2>
                    
                </a>
                <p>Yönetici amirleriniz tarafından görülecek bilgi ve belgelerinizi yükleyebilir, idari performans ölçüm puanınızı yükseltebilirsiniz.</p>
                
            </div>
            <div id="yoneticiPanel" class="panel">
                <a href="<?= \yii\helpers\Url::to(['site/kullaniciyonetimi']) ?>" class="text-decoration-none">
                    <h2>Yönetici Paneli</h2>
                </a>
                <p>Yöneticisi olduğunuz birimlerde çalışan personelin bilgilerine ve yüklediği belgelerine ulaşabilir, idari performans ölçümünü değerlendirebilirsiniz.</p>
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
    