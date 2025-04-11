

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Paneli</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="panel-container">
        <h1>Personel Paneli</h1>
        <ul>
            <li><a href="users.php">Profil Bilgileri</a></li>
            <li><a href="/index.php?r=self-assessment">Son Kullanıcı Değerlendirme Sayfası</a></li>
            <li><a href="/index.php?r=site/olcek">İdari Performans Ölçüm Alanı</a></li>
            <li><a href="index.php">Çıkış Yap</a></li>
        </ul>
        
    </div>
</body>
</html>

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

h1 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    margin: 15px 0;
}

li a {
    display: block;
    padding: 12px;
    background: #793657;
    color: white !important;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

li a:hover {
    background: #5c2a42;
}
CSS;

$this->registerCss($css);
?>