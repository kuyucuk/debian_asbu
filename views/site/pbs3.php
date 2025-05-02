<?php

// Autoload dahil edilmez! (require 'vendor/autoload.php'; satırı yok)

// PhpSpreadsheet sınıflarını manuel yükle (autoload olmadan çalışma için)
include_once '/var/www/html/yii2/PPD_12.02.2025/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Excel dosyasının yolu
$filePath = '/var/www/html/yii2/PPD_12.02.2025/web/dosyalar/KullaniciListesi-12020 (3).xlsx';

// Excel dosyasını oku
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

// E-posta adreslerini F sütunundan al
$emails = [];
foreach ($sheet->getRowIterator() as $row) {
    $cell = $sheet->getCell('F' . $row->getRowIndex());
    $email = trim($cell->getValue());
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emails[] = $email;
    }
}

// PBS SOAP bilgileri
$config = [
    'usr' => 'asbu-bidb-01',
    'pasw' => 'sbQC6BPY9yVJxv5W',
    'url' => 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl'
];

$dataList = [];

try {
    $client = new SoapClient($config['url'], [
        'trace' => 1,
        'exceptions' => true,
        'login' => $config['usr'],
        'password' => $config['pasw'],
        'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ])
    ]);

    foreach ($emails as $email) {
        $params = [
            'wsusername' => $config['usr'],
            'wspassword' => $config['pasw'],
            'email' => $email
        ];

        try {
            $result = $client->__soapCall('byemail', [$params]);
            $info = $result->return ?? null;

            if ($info) {
                $personelData = [
                    'email' => $email,
                    'ad' => $info->personelAd ?? '-',
                    'soyad' => $info->personelSoyad ?? '-',
                    'tc' => $info->personelTckimlikno ?? '-',
                    'telefon' => $info->personelCeptel1 ?? '-',
                    'kurumEmail' => $info->personelKurumemail ?? '-',
                    'cinsiyet' => $info->personelCinsiyet ?? '-',
                    'kurumSicilNo' => $info->personelKurumsicilno ?? '-',
                    'birim' => $info->personelBirim ?? '-',
                    'unvan' => $info->personelUnvan ?? '-',
                    'jobrecordTipi' => $info->personelJobrecordtipi ?? '-',
                    'jobrecordAltTipi' => $info->personelJobrecordalttipi ?? '-',
                    'fiiliGorevler' => []
                ];

                if (isset($info->fiiligorevlist) && is_array($info->fiiligorevlist)) {
                    foreach ($info->fiiligorevlist as $gorev) {
                        $personelData['fiiliGorevler'][] = [
                            'baslangicTarihi' => $gorev->fiiligorevBastar ?? '-',
                            'bitisTarihi' => $gorev->fiiligorevBittar ?? '-',
                            'gorevAciklama' => $gorev->fiiligorevAciklama ?? '-',
                            'gorevBirim' => $gorev->fiiligorevBirim ?? '-',
                            'masterBirim' => $gorev->fiiligorevMasterbirim ?? '-',
                            'isasil' => $gorev->fiiligorevIsasil ?? '-',
                        ];
                    }
                }

                $dataList[] = $personelData;
            } else {
                $dataList[] = ['email' => $email, 'error' => 'Veri bulunamadı'];
            }
        } catch (SoapFault $e) {
            $dataList[] = ['email' => $email, 'error' => $e->getMessage()];
        }
    }

} catch (SoapFault $e) {
    echo "<div class='alert alert-danger'>SOAP istemcisi oluşturulamadı: " . $e->getMessage() . "</div>";
    exit;
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
