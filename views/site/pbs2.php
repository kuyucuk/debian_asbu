<?php

$config = [
    'usr' => 'asbu-bidb-01',
    'pasw' => 'sbQC6BPY9yVJxv5W',
    'url' => 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl'
];

// Sorgulanacak e-posta adreslerini buraya ekleyin
$emailList = [
    'ziya.cirakoglu@asbu.edu.tr',
    'tolga.kuyucuk@asbu.edu.tr',
    'seher.akbaba@asbu.edu.tr'
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

    foreach ($emailList as $email) {
        $params = [
            'wsusername' => $config['usr'],
            'wspassword' => $config['pasw'],
            'email' => $email
        ];

        try {
            $result = $client->__soapCall('byemail', [$params]);

            // Gelen yanıtı kontrol et
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

                // Fiili görev listesine göz atma
                if (isset($info->fiiligorevlist) && is_array($info->fiiligorevlist)) {
                    foreach ($info->fiiligorevlist as $gorev) {
                        $personelData['fiiliGorevler'][] = [
                            'baslangicTarihi' => $gorev->fiiligorevBastar ?? '-',
                            'bitisTarihi' => $gorev->fiiligorevBittar ?? '-',
                            'gorevAciklama' => $gorev->fiiligorevAciklama ?? '-',
                            'gorevBirim' => $gorev->fiiligorevBirim ?? '-',
                            'gorevBirimId' => $gorev->fiiligorevBirimid ?? '-',
                            'masterBirim' => $gorev->fiiligorevMasterbirim ?? '-',
                            'masterBirimId' => $gorev->fiiligorevMasterbirimid ?? '-',
                            'isasil' => $gorev->fiiligorevIsasil ?? '-',
                        ];
                    }
                }

                $dataList[] = $personelData;
            } else {
                $dataList[] = [
                    'email' => $email,
                    'error' => 'Veri bulunamadı'
                ];
            }
        } catch (SoapFault $e) {
            $dataList[] = [
                'email' => $email,
                'error' => $e->getMessage()
            ];
        }
    }

} catch (SoapFault $e) {
    echo "<div class='alert alert-danger'>SOAP istemcisi oluşturulamadı: " . $e->getMessage() . "</div>";
    exit;
}
?>

<!-- HTML Başlangıcı -->
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
                            <tr>
                                <th style="width: 30%;">Ad Soyad</th>
                                <td><?php echo $person['ad'] . ' ' . $person['soyad']; ?></td>
                            </tr>
                            <tr>
                                <th>TC Kimlik No</th>
                                <td><?php echo $person['tc']; ?></td>
                            </tr>
                            <tr>
                                <th>Kurum Email</th>
                                <td><?php echo $person['kurumEmail']; ?></td>
                            </tr>
                            <tr>
                                <th>Cinsiyet</th>
                                <td><?php echo $person['cinsiyet']; ?></td>
                            </tr>
                            <tr>
                                <th>Birim</th>
                                <td><?php echo $person['birim']; ?></td>
                            </tr>
                            <tr>
                                <th>Unvan</th>
                                <td><?php echo $person['unvan']; ?></td>
                            </tr>
                            <tr>
                                <th>Görev Tipi</th>
                                <td><?php echo $person['jobrecordTipi']; ?></td>
                            </tr>
                            <tr>
                                <th>Alt Görev Tipi</th>
                                <td><?php echo $person['jobrecordAltTipi']; ?></td>
                            </tr>
                            <tr>
                                <th>Kurum Sicil No</th>
                                <td><?php echo $person['kurumSicilNo']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mt-3">Fiili Görevler</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Başlangıç Tarihi</th>
                                <th>Bitiş Tarihi</th>
                                <th>Açıklama</th>
                                <th>Birim</th>
                                <th>Master Birim</th>
                                <th>İşasil</th>
                            </tr>
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

    .navbar {
        z-index: 1040;
    }
</style>
