<?php
$css = <<<CSS
.panel-container {
    max-width: 800px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

p {
    font-size: 16px;
    color: #555;
    line-height: 1.5;
    text-align: center;
}

a.support-link, a.index-link {
    display: block;
    margin-top: 20px;
    text-align: center;
    color: #793657;
    text-decoration: none;
    font-size: 14px;
}

a.support-link:hover, a.index-link:hover {
    text-decoration: underline;
}
CSS;

$this->registerCss($css);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifremi Unuttum</title>
</head>
<body>
    <div class="panel-container">
        <h2>Şifremi Unuttum</h2>
        <p>ASBU mail şifreniz, sistem şifreniz ile aynıdır. Eğer bu şifrenizi de hatırlamıyorsanız, teknik destek almak için aşağıdaki bağlantıya tıklayabilirsiniz.</p>
        <a href="https://teknikdestek.asbu.edu.tr/" class="support-link">Teknik Destek Al</a>
        <a href="/index.php" class="index-link">İdari Personel Performans Ölçümü Sayfasına Dön</a>
    </div>
</body>
</html>
