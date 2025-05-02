<?php
$this->title = 'Performans Ölçüm Formu';

// Veritabanı bağlantısı
$pdo = new PDO("mysql:host=10.0.47.121;dbname=asbu_pbs;charset=utf8mb4", "root", "Asbu&2022*");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Sicil no GET ile alınır
$sicilNo = $_GET['sicilno'] ?? null;

// Varsayılan kullanıcı bilgileri
$user = [
    'name' => 'Ad Soyad',
    'email' => 'bilinmiyor@asbu.edu.tr',
    'phone' => 'Telefon Bilinmiyor',
    'birth_date' => 'Doğum Tarihi Bilinmiyor',
    'title' => 'Ünvan Bilinmiyor',
    'tc' => 'TC Bilinmiyor',
    'cinsiyet' => '-',
    'jobrecord_tipi' => '-',
    'jobrecord_alttipi' => '-',
    'institution_id' => $sicilNo,
    'unit' => 'Birim Bilinmiyor',
    'egitim_duzeyi' => 'Yüksek Lisans',
    'hizmet_yili' => '1 yıl',
    'yabanci_dil' => 'KPDS 72',
    'egitim_veren' => 3,
    'egitim_katilan' => 4,
    'komisyon_gorevi' => 5,
    'duzeltici_faaliyet' => 1,
    'kurum_ici_panel' => 0
];

// Kullanıcı bilgileri veritabanından çekiliyor
if ($sicilNo) {
    $stmt = $pdo->prepare("SELECT ad, soyad, unvan, birim, telefon, kurum_email, tc, cinsiyet, jobrecord_tipi, jobrecord_alttipi FROM personeller WHERE kurum_sicil_no = ?");
    $stmt->execute([$sicilNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user['name'] = $row['ad'] . ' ' . $row['soyad'];
        $user['title'] = $row['unvan'];
        $user['unit'] = $row['birim'];
        $user['phone'] = $row['telefon'] ?: $user['phone'];
        $user['email'] = $row['kurum_email'] ?: $user['email'];
        $user['tc'] = $row['tc'];
        $user['cinsiyet'] = $row['cinsiyet'];
        $user['jobrecord_tipi'] = $row['jobrecord_tipi'];
        $user['jobrecord_alttipi'] = $row['jobrecord_alttipi'];
    }
}

// Değerlendirme tamamlandığında
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_evaluation'])) {
    try {
        $pdo->beginTransaction();
        
        // 1. Personelin tüm belge onaylarını kaydet
        $stmtBelge = $pdo->prepare("INSERT INTO belge_onaylari (sicil_no, belge_turu, durum, aciklama) VALUES (?, ?, ?, ?)");
        
        foreach ($_POST['belge_onaylari'] as $belge) {
            $stmtBelge->execute([
                $sicilNo,
                $belge['tur'],
                $belge['durum'],
                $belge['aciklama']
            ]);
        }

        // 2. Performans ölçüm sonuçlarını kaydet
        $stmtPerformans = $pdo->prepare("INSERT INTO performans_sonuclari (sicil_no, soru_no, puan) VALUES (?, ?, ?)");
        
        foreach ($_POST['cevaplar'] as $soruNo => $puan) {
            $stmtPerformans->execute([$sicilNo, $soruNo, $puan]);
        }

        // 3. Genel değerlendirme durumunu güncelle
        $stmtDegerlendirme = $pdo->prepare("UPDATE personeller SET degerlendirme_tamamlandi = 1 WHERE kurum_sicil_no = ?");
        $stmtDegerlendirme->execute([$sicilNo]);

        $pdo->commit();
        
        echo "<script>
            alert('Değerlendirme başarıyla tamamlandı!');
            window.location.href = '/profil?sicilno=$sicilNo'; // Yönlendirme
        </script>";

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Hata: " . $e->getMessage());
    }
}

// Değerlendirme tamamlandığında sonuçları kaydetme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_evaluation'])) {
    $stmt = $pdo->prepare("INSERT INTO degerlendirme_sonuc (sicil_no, sonuc) VALUES (?, ?)");
    $stmt->execute([$sicilNo, 'Değerlendirme tamamlandı']);

    echo "<script>alert('Değerlendirme başarıyla tamamlandı.');</script>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Profili</title>
    <link rel="stylesheet" href="/web/css/site.css">
    <style>
        body { background: #f5f5f5; font-family: "Segoe UI", sans-serif; }
        .profile-container { max-width: 1500px; width: 90%; background: white; margin: 40px auto; padding: 30px; box-shadow: 0 0 12px rgba(0,0,0,0.1); border-radius: 10px; }
        .profile-header { text-align: center; margin-bottom: 30px; }
        .section-title { font-size: 18px; font-weight: bold; color: #444; margin-top: 30px; margin-bottom: 10px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .profile-item { margin-bottom: 8px; }
        .profile-item label { font-weight: bold; color: #555; display: inline-block; width: 180px; }
        .puan-box { margin-top: 10px; padding: 15px; background: #f7f7f7; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; }
        .puan-box.green { background: #d4edda; border-color: #c3e6cb; }
        .belge-item { margin: 8px 0; padding: 8px; background: #eef1f5; border-radius: 6px; }
        .belge-item { position: relative;}
        .tooltip { visibility: hidden; background-color: #333; color: #fff; text-align: center; border-radius: 6px; padding: 6px 10px; position: absolute; z-index: 1; bottom: 110%; /* kutunun üstüne çıkması için */ left: 50%; transform: translateX(-50%); opacity: 0; transition: opacity 0.3s; font-size: 13px; white-space: nowrap; }
        .belge-item:hover .tooltip { visibility: visible; opacity: 1; }
        .belge-link { margin-bottom: 4px; font-size: 15px; color: #0056b3; text-decoration: none; display: inline-block; }
        .belge-link:hover { text-decoration: underline; }
        .belge-actions { margin-top: 5px; }
        .belge-actions button { margin-right: 5px; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
        .belge-item.approved {background-color: #d4edda !important; border: 1px solid #c3e6cb;}
        .belge-item.rejected {background-color: #f8d7da !important; border: 1px solid #f5c6cb;}
        .approve-btn { background-color: #28a745; color: white; }
        .reject-btn { background-color: #dc3545; color: white; }
        .explanation-box { margin-top: 8px; display: none; }
        .explanation-box input[type="text"] { width: 100%; padding: 6px; margin-bottom: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .explanation-box button { background-color: #007bff; color: white; }
        .button-container { margin-top: 30px; text-align: center; }
        .back-button, .measurement-button, .complete-button { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; background-color: #007bff; color: white; transition: 0.3s ease; }
        .back-button:hover, .measurement-button:hover, .complete-button:hover { background-color: #0056b3; }
        .success-message { margin-top: 20px; color: green; font-weight: bold; text-align: center; display: none; }
    </style>
    <script>
        function approve(button) {
            const parent = button.closest('.belge-item');
            parent.classList.add('approved');
            parent.classList.remove('rejected');
            parent.querySelector('.reject-btn').classList.remove('active');
            parent.querySelector('.explanation-box').style.display = 'none';
            button.classList.add('active');
        }

        function reject(button) {
            const parent = button.closest('.belge-item');
            parent.classList.add('rejected');
            parent.classList.remove('approved');
            parent.querySelector('.approve-btn').classList.remove('active');
            button.classList.add('active');
            parent.querySelector('.explanation-box').style.display = 'block';
        }

        function completeEvaluation() {
    // Onay penceresi
    if (!confirm('Değerlendirmeyi tamamlamak istediğinize emin misiniz?\nBu işlem geri alınamaz!')) {
        return; // İptal durumu
    }
	
		function completeEvaluation() {
    // Tüm belgelerin onaylandığını kontrol et
    const reddedilenBelgeler = document.querySelectorAll('.belge-item.rejected');
    if(reddedilenBelgeler.length > 0 && !confirm('Reddedilen belgeler var! Yine de tamamlamak istiyor musunuz?')) {
        return;
    }

    // Onay penceresi
    if (!confirm('Değerlendirmeyi tamamlamak istediğinize emin misiniz?\nBu işlem geri alınamaz!')) {
        return;
    }

    // Form verilerini topla
    const formData = new FormData();
    formData.append('complete_evaluation', '1');
    
    // AJAX ile gönderim (Opsiyonel)
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    }).then(response => {
        if(response.ok) {
            alert('Başarılı!');
            window.location.reload();
        }
    });
}

    // Form oluştur ve gönder
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'complete_evaluation';
    input.value = '1';
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

        function saveExplanation(button) {
            const input = button.previousElementSibling;
            if (input.value.trim() === "") {
                alert("Lütfen açıklama giriniz.");
                return;
            }
            alert("Açıklama kaydedildi: " + input.value);
        }
    </script>
</head>
<body>
<div class="profile-container">
    <div class="profile-header">
        <div><strong><?= htmlspecialchars($user['name']) ?></strong></div>
    </div>

    <div class="section-title">Kişisel Bilgiler</div>
    <div class="profile-item"><label>Ünvan:</label> <?= htmlspecialchars($user['title']) ?></div>
    <div class="profile-item"><label>Birim:</label> <?= htmlspecialchars($user['unit']) ?></div>
    <div class="profile-item"><label>Kurum Sicil No:</label> <?= htmlspecialchars($user['institution_id']) ?></div>
    <div class="profile-item"><label>Kadro:</label> <?= htmlspecialchars($user['jobrecord_tipi']) ?></div>
    <div class="profile-item"><label>Kadro Tipi:</label> <?= htmlspecialchars($user['jobrecord_alttipi']) ?></div>
    <div class="profile-item"><label>E-posta:</label> <?= htmlspecialchars($user['email']) ?></div>

    <?php
    $fields = [
        'egitim_veren' => 'Kurum içi eğitim verme',
        'egitim_katilan' => 'Kurum dışı eğitimlere katılım',
        'komisyon_gorevi' => 'Komisyon/kurul görevleri',
        'duzeltici_faaliyet' => 'Düzeltici iyileştirici faaliyetler',
        'kurum_ici_panel' => 'Kurum içi panel/etkinlik katılımı'
    ];
    ?>

    <?php foreach ($fields as $key => $label): ?>
        <div id="<?= $key ?>" class="puan-box">
            <div class="accordion-header">
                <?= $label ?>: <?= (int)$user[$key]; ?>
            </div>
            <?php if ((int)$user[$key] > 0): ?>
                <?php for ($i = 1; $i <= (int)$user[$key]; $i++): ?>
                    <div class="belge-item">
                        <a href="dosyalar/asbu_idari_performans_degerlendirme.pdf" class="belge-link" target="_blank">
                            Belge <?= $i ?>
                        </a>
                        <div class="tooltip">
                            <?= htmlspecialchars($label) ?> - Belge <?= $i ?>
                        </div>
                        <div class="belge-actions">
                            <button class="approve-btn" onclick="approve(this)">Onayla</button>
                            <button class="reject-btn" onclick="reject(this)">Reddet</button>
                        </div>
                        <div class="explanation-box">
                            <input type="text" placeholder="Açıklama">
                            <button onclick="saveExplanation(this)">Kaydet</button>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <span class="no-document">Belge bulunmamaktadır.</span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="button-container">
        <a href="/back" class="back-button">Geri</a>
        <a href="javascript:void(0)" class="measurement-button" onclick="completeEvaluation()">Değerlendirmeyi Tamamla</a>
    </div>

    <div id="success-message" class="success-message"></div>
</div>
</body>
</html>

