<?php
$sicilNo = $_GET['sicilno'] ?? null;

// Örnek kullanıcı bilgileri ve değerlendirme puanları
$user = [
    'profile_photo' => 'https://bidb.asbu.edu.tr/sites/idari_birimler/bidb.asbu.edu.tr/files/styles/medium/public/inline-images/tolga.png?itok=92GE3EVR',
    'name' => 'Tolga Kuyucuk',
    'email' => 'tolga.kuyucuk@asbu.edu.tr',
    'phone' => '543-906-2143',
    'birth_date' => '08.02.1994',
    'title' => 'Öğretim Görevlisi',
    'institution_id' => 'A-519',
    'hizmet_yili' => '1 yıl',
    'egitim_duzeyi' => 'Yüksek Lisans',
    'yabanci_dil' => 'KPDS 72',
    'egitim_veren' => 3,
    'egitim_katilan' => 4,
    'komisyon_gorevi' => 2,
    'duzeltici_faaliyet' => 1,
    'kurum_ici_panel' => 2,
    'unit' => 'Bilgi İşlem Daire Başkanlığı'
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Profili</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .profile-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: white;
            border-radius: 10px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .profile-item {
            margin-bottom: 10px;
        }
        .profile-item label {
            font-weight: bold;
        }
        .puan-box {
            margin-top: 10px;
            padding: 10px;
            background-color: #eef;
            border-left: 5px solid #3366cc;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .button-container a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .back-button {
            background-color: #3366cc;
        }
        .certificates-button {
            background-color: #28a745;
        }
        .measurement-button {
            background-color: #dc3545;
        }
        .complete-button {
            background-color: #ffc107;
        }
        .success-message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
    <script>
        function completeEvaluation() {
            const messageContainer = document.getElementById('success-message');
            messageContainer.style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?= htmlspecialchars($user['profile_photo']); ?>" alt="Profil Fotoğrafı" class="profile-photo">
            <div><strong><?= htmlspecialchars($user['name']); ?></strong></div>
        </div>

        <div class="section-title">Kişisel Bilgiler</div>
        <div class="profile-item"><label>Ünvan:</label> <?= htmlspecialchars($user['title']); ?></div>
        <div class="profile-item"><label>Kurum Sicil No:</label> <?= htmlspecialchars($user['institution_id']); ?></div>
        <div class="profile-item"><label>E-posta:</label> <?= htmlspecialchars($user['email']); ?></div>
        <div class="profile-item"><label>Telefon:</label> <?= htmlspecialchars($user['phone']); ?></div>
        <div class="profile-item"><label>Doğum Tarihi:</label> <?= htmlspecialchars($user['birth_date']); ?></div>
        <div class="profile-item"><label>Birim:</label> <?= htmlspecialchars($user['unit']); ?></div>

        <div class="section-title">Değerlendirme Bilgileri</div>
        <div class="puan-box"><strong>ASBÜ’deki Hizmet Yılı:</strong> <?= $user['hizmet_yili']; ?></div>
        <div class="puan-box"><strong>Eğitim Düzeyi:</strong> <?= $user['egitim_duzeyi']; ?></div>
        <div class="puan-box"><strong>Yabancı Dil Puanı:</strong> <?= $user['yabanci_dil']; ?></div>
        <div class="puan-box"><strong>Kurum içi eğitim verme:</strong> <?= $user['egitim_veren']; ?> görev</div>
        <div class="puan-box"><strong>Kurum dışı eğitimlere katılım:</strong> <?= $user['egitim_katilan']; ?> sertifika</div>
        <div class="puan-box"><strong>Komisyon/kurul görevleri:</strong> <?= $user['komisyon_gorevi']; ?> görev</div>
        <div class="puan-box"><strong>Düzeltici iyileştirici faaliyetler:</strong> <?= $user['duzeltici_faaliyet']; ?> adet</div>
        <div class="puan-box"><strong>Kurum içi panel/etkinlik katılımı:</strong> <?= $user['kurum_ici_panel']; ?> etkinlik</div>

        <div class="button-container">
            <a href="javascript:history.back()" class="back-button">Geri</a>
            <a href="/index.php?r=self-assessment" class="certificates-button">Yüklenen Belgeler</a>
            <a href="/index.php?r=site/olcek" class="measurement-button">Personel Değerlendirme Ölçeği</a>
            <a href="javascript:void(0);" onclick="completeEvaluation()" class="complete-button">Değerlendirmeyi Tamamla</a>
        </div>

        <div id="success-message" class="success-message" style="display: none;">
            Personel değerlendirmesi başarı ile kaydedilmiştir!
        </div>
    </div>
</body>
</html>
