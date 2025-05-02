<?php
// Veritabanı bağlantısı
$pdo = new PDO("mysql:host=10.0.47.121;dbname=asbu_pbs;charset=utf8mb4", "root", "Asbu&2022*");

// E-posta adreslerini veritabanından al
$stmt = $pdo->query("SELECT DISTINCT email FROM personeller");
$emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Verileri alacak dizi
$dataList = [];

foreach ($emails as $email) {
    // Personel bilgilerini al
    $stmt = $pdo->prepare("SELECT * FROM personeller WHERE email = ?");
    $stmt->execute([$email]);
    $personel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($personel) {
        // Personel verilerini hazırlıyoruz
        $personelData = [
            'email' => $personel['email'],
            'ad' => $personel['ad'] ?? '-',
            'soyad' => $personel['soyad'] ?? '-',
            'tc' => $personel['tc'] ?? '-',
            'telefon' => $personel['telefon'] ?? '-',
            'kurumEmail' => $personel['kurum_email'] ?? '-',
            'cinsiyet' => $personel['cinsiyet'] ?? '-',
            'kurumSicilNo' => $personel['kurum_sicil_no'] ?? '-',
            'birim' => $personel['birim'] ?? '-',
            'unvan' => $personel['unvan'] ?? '-',
            'jobrecordTipi' => $personel['jobrecord_tipi'] ?? '-',
            'jobrecordAltTipi' => $personel['jobrecord_alttipi'] ?? '-',
            'fiiliGorevler' => []
        ];

        // Fiili görevler
        $stmt2 = $pdo->prepare("SELECT * FROM fiili_gorevler WHERE personel_id = ?");
        $stmt2->execute([$personel['id']]);
        $gorevler = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach ($gorevler as $gorev) {
            $personelData['fiiliGorevler'][] = [
                'baslangicTarihi' => $gorev['baslangic_tarihi'] ?? '-',
                'bitisTarihi' => $gorev['bitis_tarihi'] ?? '-',
                'gorevAciklama' => $gorev['gorev_aciklama'] ?? '-',
                'gorevBirim' => $gorev['gorev_birim'] ?? '-',
                'masterBirim' => $gorev['master_birim'] ?? '-',
                'isasil' => $gorev['isasil'] ?? '-',
            ];
        }

        $dataList[] = $personelData;
    } else {
        $dataList[] = ['email' => $email, 'error' => 'Veri bulunamadı'];
    }
}
?>

<!-- HTML Çıktısı -->
<div class="container mt-5">
    <h2 class="mb-4">Bilgi İşlem Daire Başkanlığı</h2>
    <h4 class="mb-3">Personel Bilgileri</h4>

    <?php foreach ($dataList as $person): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                <?php echo htmlspecialchars($person['email']); ?>
            </div>
            <div class="card-body">
                <?php if (isset($person['error'])): ?>
                    <div class="alert alert-danger">
                        Hata: <?php echo htmlspecialchars($person['error']); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr><th>Ad Soyad</th><td><?php echo $person['ad'] . ' ' . $person['soyad']; ?></td></tr>
                            <tr><th>TC Kimlik No</th><td><?php echo $person['tc']; ?></td></tr>
                            <tr><th>Kurum Email</th><td><?php echo $person['kurumEmail']; ?></td></tr>
                            <tr><th>Cinsiyet</th><td><?php echo $person['cinsiyet']; ?></td></tr>
                            <tr><th>Birim</th><td><?php echo $person['birim']; ?></td></tr>
                            <tr><th>Unvan</th><td><?php echo $person['unvan']; ?></td></tr>
                            <tr><th>Görev Tipi</th><td><?php echo $person['jobrecordTipi']; ?></td></tr>
                            <tr><th>Alt Görev Tipi</th><td><?php echo $person['jobrecordAltTipi']; ?></td></tr>
                            <tr><th>Kurum Sicil No</th><td><?php echo $person['kurumSicilNo']; ?></td></tr>
                        </tbody>
                    </table>

                    <h5 class="mt-3">Fiili Görevler</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>Başlangıç</th><th>Bitiş</th><th>Açıklama</th><th>Birim</th><th>Master Birim</th><th>İşasil</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($person['fiiliGorevler'] as $gorev): ?>
                                <tr>
                                    <td><?php echo $gorev['baslangicTarihi']; ?></td>
                                    <td><?php echo $gorev['bitisTarihi']; ?></td>
                                    <td><?php echo $gorev['gorevAciklama']; ?></td>
                                    <td><?php echo $gorev['gorevBirim']; ?></td>
                                    <td><?php echo $gorev['masterBirim']; ?></td>
                                    <td><?php echo $gorev['isasil']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
body {
    margin-top: 70px;
}
.card {
    border-radius: 10px;
}
</style>

