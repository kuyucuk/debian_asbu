
<?php
$this->title = 'Profil';

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
    'komisyon_gorevi' => 2,
    'duzeltici_faaliyet' => 1,
    'kurum_ici_panel' => 2
];

// Veritabanından bilgiler çekiliyor
if ($sicilNo) {
    $stmt = $pdo->prepare("SELECT ad, soyad, unvan, birim, telefon, kurum_email, tc, cinsiyet, jobrecord_tipi, jobrecord_alttipi FROM personeller WHERE kurum_sicil_no = ?");
    $stmt->execute([$sicilNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
// Fiili görevler
$gorevler = [];

if ($sicilNo) {
    $stmt = $pdo->prepare("SELECT baslangic_tarihi, bitis_tarihi, gorev_aciklama, gorev_birim, master_birim, isasil 
                           FROM fiili_gorevler 
                           WHERE id = ?
                           ORDER BY baslangic_tarihi DESC");
    $stmt->execute([$sicilNo]);
    $gorevler = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

	

    if ($row) {
        $user['name'] = $row['ad'] . ' ' . $row['soyad'];
        $user['title'] = $row['unvan'];
        $user['unit'] = $row['birim'];
        $user['phone'] = $row['telefon'] ?: $user['phone'];
        $user['email'] = $row['kurum_email'] ?: strtolower(
            str_replace(['ç','ğ','ı','ö','ş','ü','Ç','Ğ','İ','Ö','Ş','Ü'], ['c','g','i','o','s','u','C','G','I','O','S','U'], $row['ad'])
            . '.' .
            str_replace(['ç','ğ','ı','ö','ş','ü','Ç','Ğ','İ','Ö','Ş','Ü'], ['c','g','i','o','s','u','C','G','I','O','S','U'], $row['soyad'])
        ) . '@asbu.edu.tr';

        // Yeni alanlar
        $user['tc'] = $row['tc'];
        $user['cinsiyet'] = $row['cinsiyet'];
        $user['jobrecord_tipi'] = $row['jobrecord_tipi'];
        $user['jobrecord_alttipi'] = $row['jobrecord_alttipi'];
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Profili</title>
    <link rel="stylesheet" href="/web/css/site.css">
    <style>
        body {
            background: #f5f5f5;
            font-family: "Segoe UI", sans-serif;
        }
        .profile-container {
            max-width: 800px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #444;
            margin-top: 30px;
            margin-bottom: 10px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
        .profile-item {
            margin-bottom: 8px;
        }
        .profile-item label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 180px;
        }
        .puan-box {
            margin-top: 10px;
            padding: 12px;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .puan-box.green {
            background: #d4edda;
            border-color: #c3e6cb;
        }
        .radio-container {
            float: right;
        }
        .radio-button {
            margin-left: 10px;
        }
        .button-container {
            margin-top: 30px;
            text-align: center;
        }
        .back-button, .measurement-button, .complete-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            transition: 0.3s ease;
        }
        .back-button:hover, .measurement-button:hover, .complete-button:hover {
            background-color: #0056b3;
        }
        .success-message {
            margin-top: 20px;
            color: green;
            font-weight: bold;
            text-align: center;
        }
    </style>
    <script>
        function toggleApproval(radio, boxId) {
            const box = document.getElementById(boxId);
            if (radio.checked && box.classList.contains('green')) {
                radio.checked = false;
                box.classList.remove('green');
            } else if (radio.checked) {
                box.classList.add('green');
            }
        }

        function completeEvaluation() {
            const successMessage = document.getElementById('success-message');
            successMessage.style.display = 'none';
            successMessage.textContent = '';
            setTimeout(() => {
                successMessage.textContent = 'Personel değerlendirmesi başarı ile kaydedilmiştir!';
                successMessage.style.display = 'block';
            }, 100);
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
	<div class="profile-item"><label>T.C. Kimlik No:</label> <?= htmlspecialchars($user['tc']) ?></div>
	<div class="profile-item"><label>Cinsiyet:</label> <?= htmlspecialchars($user['cinsiyet']) ?></div>
	<div class="profile-item"><label>Kadro:</label> <?= htmlspecialchars($user['jobrecord_tipi']) ?></div>
	<div class="profile-item"><label>Kadro Tipi:</label> <?= htmlspecialchars($user['jobrecord_alttipi']) ?></div>
	<div class="profile-item"><label>E-posta:</label> <?= htmlspecialchars($user['email']) ?></div>
	<div class="profile-item"><label>Telefon:</label> <?= htmlspecialchars($user['phone']) ?></div>

<?php
// ... (Üst kısım aynı kalıyor)

// Veritabanından bilgiler çekiliyor
if ($sicilNo) {
    // Personel bilgilerini çek
    $stmt = $pdo->prepare("SELECT id, ad, soyad, unvan, birim, telefon, kurum_email, tc, cinsiyet, jobrecord_tipi, jobrecord_alttipi FROM personeller WHERE kurum_sicil_no = ?");
    $stmt->execute([$sicilNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
    // FİİLİ GÖREVLERİ ÇEKME KISMI EKLENDİ
    $gorevler = [];
if ($row && isset($row['id'])) {
    $stmtGorev = $pdo->prepare("SELECT * 
                               FROM fiili_gorevler 
                               WHERE personel_id = ? 
                               ORDER BY baslangic_tarihi DESC"); // En yeni en üste
    $stmtGorev->execute([$row['id']]);
    $gorevler = $stmtGorev->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>
/* Aktif görev vurgulama CSS EKLENDİ */
.active-gorev {
    background-color: #e8f5e9 !important; /* Açık yeşil */
    border-left: 4px solid #2e7d32;       /* Yeşil çizgi */
}
</style>

<!-- TABLO GÜNCELLENDİ (Aktif görev kontrolü) -->
<?php if (!empty($gorevler)): ?>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Başlangıç</th>
            <th>Bitiş</th>
            <th>Durum</th>
            <th>Açıklama</th>
            <th>Birim</th>
            <th>Master Birim</th>
            <th>İşasil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gorevler as $gorev): 
            $isActive = empty($gorev['bitis_tarihi']) || $gorev['bitis_tarihi'] == '-';
        ?>
            <tr class="<?= $isActive ? 'active-gorev' : '' ?>">
                <td>
                    <?= date('d.m.Y', strtotime($gorev['baslangic_tarihi'])) ?? '-' ?>
                </td>
                <td>
                    <?php if($isActive): ?>
                        <span style="color:green">Aktif</span>
                    <?php else: ?>
                        <?= date('d.m.Y', strtotime($gorev['bitis_tarihi'])) ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($isActive): ?>
                        🔵 Devam Ediyor
                    <?php else: ?>
                        ⚪ Tamamlandı
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($gorev['gorev_aciklama'] ?? '-') ?></td>
                <td><?= htmlspecialchars($gorev['gorev_birim'] ?? '-') ?></td>
                <td><?= htmlspecialchars($gorev['master_birim'] ?? '-') ?></td>
                <td><?= htmlspecialchars($gorev['isasil'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="alert alert-info">Fiili görev bulunamadı.</div>
<?php endif; ?>

<!-- ... (Sayfanın geri kalanı aynı) -->
    <div class="section-title">Değerlendirme Bilgileri</div>
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
        <div class="accordion-header" onclick="toggleAccordion(event, '<?= $key ?>')">
            <strong><?= $label ?>:</strong> <?= $user[$key]; ?>
        </div>
        <div id="details-<?= $key ?>" class="accordion-content">
        <?php
        $belgeSayisi = (int) $user[$key];
        if ($belgeSayisi > 0):
            for ($i = 1; $i <= $belgeSayisi; $i++): ?>
                <a href="dosyalar/asbu_idari_performans_degerlendirme.pdf" target="_blank" title="Bu belge <?= $label ?> kriterine ait <?= $i ?> numaralı belgedir.">
                    Belge <?= $i ?>
                </a>
            <?php endfor;
        else: ?>
            <span>Belge bulunmamaktadır.</span>
        <?php endif; ?>
        </div>
        <div class="radio-container">
            <label>
                <input type="checkbox" class="approval-checkbox" onclick="toggleApproval(this)">
                Onay
            </label>
        </div>
    </div>
<?php endforeach; ?>


<script>
function toggleAccordion(event, key) {
    event.stopPropagation();
    const content = document.getElementById(`details-${key}`);
    content.classList.toggle('active');

    // Diğer açık olanları kapat
    document.querySelectorAll('.accordion-content').forEach(otherContent => {
        if (otherContent !== content) {
            otherContent.classList.remove('active');
        }
    });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.puan-box')) {
        document.querySelectorAll('.accordion-content').forEach(content => {
            content.classList.remove('active');
        });
    }
});

function toggleApproval(checkbox) {
    const label = checkbox.closest('label');
    if (checkbox.checked) {
        label.style.color = 'green';
        label.style.fontWeight = 'bold';
    } else {
        label.style.color = '';
        label.style.fontWeight = '';
    }
}
</script>




    <div class="button-container">
        <a href="javascript:history.back()" class="back-button">Geri</a>
        <a href="/index.php?r=site/olcek" class="measurement-button">Personel Değerlendirme Ölçeği</a>
        <a href="javascript:void(0);" onclick="completeEvaluation()" class="complete-button">Değerlendirmeyi Tamamla</a>
    </div>

    <div id="success-message" class="success-message" style="display: none;"></div>
</div>
</body>
</html>
