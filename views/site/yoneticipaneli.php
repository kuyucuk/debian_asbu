

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Paneli</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="panel-container">
        <h1>Yönetici Paneli</h1>
        <ul>
            <li><a href="/index.php?r=site/kullaniciyonetimi">Kullanıcı Yönetimi</a></li>
            <li><a href="/index.php?r=site/olcek">İdari Performans Ölçüm Alanı</a></li>
            <li><a href="evaluations.php">Performans Değerlendirmeleri</a></li>
            <li><a href="categories.php">Genel Değerlendirme ve Raporlama</a></li>
            <li><a href="index.php">Çıkış Yap</a></li>
        </ul>
        
    </div>
</body>
</html>

<?php
$css = <<<CSS


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