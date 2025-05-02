<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

// Veritabanı bağlantısı
$pdo = new PDO("mysql:host=10.0.47.121;dbname=asbu_pbs;charset=utf8mb4", "root", "Asbu&2022*");

// Excel yolu
$filePath = '/var/www/html/yii2/PPD_12.02.2025/web/dosyalar/KullaniciListesi-12020 (3).xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();

$emailColumn = 'F';
$emails = [];

// E-posta sütunundan geçerli e-posta adreslerini topla
foreach ($sheet->getColumnIterator($emailColumn)->current()->getCellIterator() as $cell) {
    $email = $cell->getValue();
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emails[] = $email;
    }
}

// Tarih dönüşüm fonksiyonu (ISO 8601 formatını MySQL formatına çevirme)
function parseDate($dateStr) {
    if (empty($dateStr) || $dateStr === '-' || $dateStr === 'NULL') return null;

    // ISO 8601 formatını MySQL formatına dönüştürmek
    try {
        $dt = DateTime::createFromFormat('Y-m-d\TH:i:sP', $dateStr);
        if ($dt) {
            return $dt->format('Y-m-d'); // Y-m-d formatında döndür
        }
    } catch (Exception $e) {
        return null; // Geçersiz tarih durumunda null döner
    }
    return null; // Eğer tarih geçerli değilse null döner
}

// SOAP yapılandırması
$config = [
    'usr' => 'asbu-bidb-01',
    'pasw' => 'sbQC6BPY9yVJxv5W',
    'url' => 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl'
];

$processedEmails = []; // Daha önce işlenen e-posta adreslerini takip etmek için dizi

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
        // Aynı e-posta adresi daha önce işlendiyse geç
        if (in_array($email, $processedEmails)) {
            continue;
        }

        $params = [
            'wsusername' => $config['usr'],
            'wspassword' => $config['pasw'],
            'email' => $email
        ];

        try {
            $result = $client->__soapCall('byemail', [$params]);
            $info = $result->return ?? null;

            if ($info) {
                // E-posta adresine göre personel bilgilerini kontrol et ve güncelle
                $stmt = $pdo->prepare("SELECT id FROM personeller WHERE email = ?");
                $stmt->execute([$email]);
                $personel = $stmt->fetch();

                if ($personel) {
                    // Kişi zaten varsa, bilgileri güncelle
                    $stmt = $pdo->prepare("UPDATE personeller 
                        SET ad = ?, soyad = ?, tc = ?, telefon = ?, kurum_email = ?, cinsiyet = ?, kurum_sicil_no = ?, birim = ?, unvan = ?, jobrecord_tipi = ?, jobrecord_alttipi = ?
                        WHERE email = ?");
                    $stmt->execute([
                        $info->personelAd ?? '-',
                        $info->personelSoyad ?? '-',
                        $info->personelTckimlikno ?? '-',
                        $info->personelCeptel1 ?? '-',
                        $info->personelKurumemail ?? '-',
                        $info->personelCinsiyet ?? '-',
                        $info->personelKurumsicilno ?? '-',
                        $info->personelBirim ?? '-',
                        $info->personelUnvan ?? '-',
                        $info->personelJobrecordtipi ?? '-',
                        $info->personelJobrecordalttipi ?? '-',
                        $email
                    ]);
                    $personelId = $personel['id']; // Var olan personel ID'sini alıyoruz
                } else {
                    // Kişi yoksa, yeni kişi ekle
                    $stmt = $pdo->prepare("INSERT INTO personeller 
                        (email, ad, soyad, tc, telefon, kurum_email, cinsiyet, kurum_sicil_no, birim, unvan, jobrecord_tipi, jobrecord_alttipi)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([ 
                        $email,
                        $info->personelAd ?? '-',
                        $info->personelSoyad ?? '-',
                        $info->personelTckimlikno ?? '-',
                        $info->personelCeptel1 ?? '-',
                        $info->personelKurumemail ?? '-',
                        $info->personelCinsiyet ?? '-',
                        $info->personelKurumsicilno ?? '-',
                        $info->personelBirim ?? '-',
                        $info->personelUnvan ?? '-',
                        $info->personelJobrecordtipi ?? '-',
                        $info->personelJobrecordalttipi ?? '-'
                    ]);
                    $personelId = $pdo->lastInsertId(); // Yeni personel ID'sini alıyoruz
                }

                // Fiili görevler ekleniyor ya da güncelleniyor
				if (isset($info->fiiligorevlist) && is_array($info->fiiligorevlist)) {
					foreach ($info->fiiligorevlist as $gorev) {
						// Fiili görevleri kontrol et ve güncelle
						$stmt2 = $pdo->prepare("SELECT id FROM fiili_gorevler WHERE personel_id = ? AND gorev_aciklama = ?");
						$stmt2->execute([$personelId, $gorev->fiiligorevAciklama ?? '-']);
						$existingGorev = $stmt2->fetch();

						if ($existingGorev) {
							// Fiili görev zaten varsa güncelle
							$stmt2 = $pdo->prepare("UPDATE fiili_gorevler 
								SET baslangic_tarihi = ?, bitis_tarihi = ?, gorev_birim = ?, master_birim = ?, isasil = ?, kurum_sicil_no = ?
								WHERE id = ?");
							$stmt2->execute([
								parseDate($gorev->fiiligorevBastar ?? null),
								parseDate($gorev->fiiligorevBittar ?? null),
								$gorev->fiiligorevBirim ?? '-',
								$gorev->fiiligorevMasterbirim ?? '-',
								$gorev->fiiligorevIsasil ?? '-',
								$info->personelKurumsicilno ?? '-', // eklendi
								$existingGorev['id']
							]);
						} else {
							// Fiili görev yoksa ekle
							$stmt2 = $pdo->prepare("INSERT INTO fiili_gorevler 
								(personel_id, baslangic_tarihi, bitis_tarihi, gorev_aciklama, gorev_birim, master_birim, isasil, kurum_sicil_no) 
								VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
							$stmt2->execute([
								$personelId,
								parseDate($gorev->fiiligorevBastar ?? null),
								parseDate($gorev->fiiligorevBittar ?? null),
								$gorev->fiiligorevAciklama ?? '-',
								$gorev->fiiligorevBirim ?? '-',
								$gorev->fiiligorevMasterbirim ?? '-',
								$gorev->fiiligorevIsasil ?? '-',
								$info->personelKurumsicilno ?? '-', // eklendi
							]);
						}
					}
				}
            }

            // E-posta adresini işlenmiş olarak kaydet
            $processedEmails[] = $email;

        } catch (SoapFault $e) {
            echo "SOAP hatası: " . $e->getMessage() . "<br>";
        }
    }

    echo "Veriler başarıyla veritabanına kaydedildi.";

} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
