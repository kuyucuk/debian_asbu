<?php

$this->title = 'Profil';

$sicilNo = $_GET['sicilno'] ?? null;
$name = $_GET['name'] ?? 'Ad';
$surname = $_GET['surname'] ?? 'Soyad Bilinmiyor';
$title = $_GET['title'] ?? 'Ünvan Bilinmiyor';
$department = $_GET['department'] ?? 'Birim Bilinmiyor';

// Örnek kullanıcı bilgileri ve değerlendirme puanları
$user = [
    'profile_photo' => 'https://bidb.asbu.edu.tr/sites/idari_birimler/bidb.asbu.edu.tr/files/styles/medium/public/inline-images/tolga.png?itok=92GE3EVR',
    'name' => $name . ' ' . $surname,
    'email' => strtolower(str_replace(
        ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'],
        ['c', 'g', 'i', 'o', 's', 'u', 'C', 'G', 'I', 'O', 'S', 'U'],
        $name
    ) . '.' . str_replace(
        ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'],
        ['c', 'g', 'i', 'o', 's', 'u', 'C', 'G', 'I', 'O', 'S', 'U'],
        $surname
    )) . '@asbu.edu.tr',
    'phone' => '543-906-2143',
    'birth_date' => '08.02.1994',
    'title' => $title,
    'institution_id' => $sicilNo,
    'hizmet_yili' => '1 yıl',
    'egitim_duzeyi' => 'Yüksek Lisans',
    'yabanci_dil' => 'KPDS 72',
    'egitim_veren' => 3,
    'egitim_katilan' => 4,
    'komisyon_gorevi' => 2,
    'duzeltici_faaliyet' => 1,
    'kurum_ici_panel' => 2,
    'unit' => $department
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Profili</title>
    <link rel="stylesheet" href="/web/css/site.css"> <!-- site.css dosyasını dahil edin -->
    <script>
        function toggleApproval(radio, boxId) {
            const box = document.getElementById(boxId);
            if (radio.checked && box.classList.contains('green')) {
                radio.checked = false; // Onayı kaldır
                box.classList.remove('green');
            } else if (radio.checked) {
                box.classList.add('green');
            }
        }

        function completeEvaluation() {
            const successMessage = document.getElementById('success-message');
            successMessage.style.display = 'none'; // Önce mesajı gizle
            successMessage.textContent = ''; // Mesajı temizle

            setTimeout(() => {
                successMessage.textContent = 'Personel değerlendirmesi başarı ile kaydedilmiştir!';
                successMessage.style.display = 'block'; // Mesajı yeniden göster
            }, 100); // Kısa bir gecikme ile yeniden yaz
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
        <div class="profile-item"><label>Birim:</label> <?= htmlspecialchars($user['unit']); ?></div>
        <div class="profile-item"><label>Kurum Sicil No:</label> <?= htmlspecialchars($user['institution_id']); ?></div>
        <div class="profile-item"><label>E-posta:</label> <?= htmlspecialchars($user['email']); ?></div>
        <div class="profile-item"><label>Telefon:</label> <?= htmlspecialchars($user['phone']); ?></div>
        <div class="profile-item"><label>Doğum Tarihi:</label> <?= htmlspecialchars($user['birth_date']); ?></div>

        <div class="section-title">Değerlendirme Bilgileri</div>
        <?php
        $fields = [
            'egitim_veren' => 'Kurum içi eğitim verme',
            'egitim_katilan' => 'Kurum dışı eğitimlere katılım',
            'komisyon_gorevi' => 'Komisyon/kurul görevleri',
            'duzeltici_faaliyet' => 'Düzeltici iyileştirici faaliyetler',
            'kurum_ici_panel' => 'Kurum içi panel/etkinlik katılımı'
        ];
        foreach ($fields as $key => $label): ?>
            <div id="<?= $key ?>" class="puan-box">
                <a href="/index.php?r=self-assessment"><strong><?= $label ?>:</strong> <?= $user[$key]; ?></a>
                <div class="radio-container">
                    <label>Onay</label>
                    <input type="radio" class="radio-button" onclick="toggleApproval(this, '<?= $key ?>')">
                </div>
            </div>
        <?php endforeach; ?>

        <div class="button-container">
            <a href="javascript:history.back()" class="back-button">Geri</a>
            <a href="/index.php?r=site/olcek" class="measurement-button">Personel Değerlendirme Ölçeği</a>
            <a href="javascript:void(0);" onclick="completeEvaluation()" class="complete-button">Değerlendirmeyi Tamamla</a>
        </div>

        <div id="success-message" class="success-message" style="display: none;"></div>
    </div>
</body>
</html>