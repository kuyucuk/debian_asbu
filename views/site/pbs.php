<?php

$config = [
    'usr' => 'asbu-bidb-01',
    'pasw' => 'sbQC6BPY9yVJxv5W',
    'url' => 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl'
];

// Sorgulanacak e-posta adreslerini buraya ekleyin
$emailList = [
	'basak.hasgul@asbu.edu.tr',
	'fatma.koprucu@asbu.edu.tr',
    'ziya.cirakoglu@asbu.edu.tr',
    'tolga.kuyucuk@asbu.edu.tr',
    'seher.akbaba@asbu.edu.tr'
];

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
        echo "<h3>Sorgulanan e-posta: $email</h3>";

        $params = [
            'wsusername' => $config['usr'],
            'wspassword' => $config['pasw'],
            'email' => $email
        ];

        try {
            $result = $client->__soapCall('byemail', [$params]);

            echo "<pre>";
            print_r($result);
            echo "</pre>";

        } catch (SoapFault $e) {
            echo "<strong>Hata oluştu ($email):</strong> " . $e->getMessage() . "<br>";
        }
    }

} catch (SoapFault $e) {
    echo "SOAP istemcisi oluşturulamadı: " . $e->getMessage();
}
?>









<?php

$config = [
    'usr' => 'asbu-bidb-01',
    'pasw' => 'sbQC6BPY9yVJxv5W',
    'url' => 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl'
];

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

    // 🔍 Kurumsal e-posta adresi ile sorgulama
    $params = [
        'wsusername' => $config['usr'],
        'wspassword' => $config['pasw'],
        'email' => 'tolga.kuyucuk@asbu.edu.tr'  // Buraya gerçek e-posta yazılmalı
		
    ];


    $result = $client->__soapCall('byemail', [$params]);

    echo "<pre>";
    print_r($result);  // Yanıtı yazdır
    echo "</pre>";

} catch (SoapFault $e) {
    echo "Hata: " . $e->getMessage();
}
?>





<?php

$config = [
    'usr' => 'asbu-bidb-01',
    'pasw' => 'sbQC6BPY9yVJxv5W',
    'url' => 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl'
];

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

    // Sadece isim ile arama
    $params = [
        'namesurname' => 'TOLGA'  // Sadece isim ile arama
    ];

    $result = $client->__soapCall('bynamesurname', [$params]);

    echo "<pre>";
    print_r($result);  // Yanıtı yazdırıyoruz
    echo "</pre>";

    // Detaylı debug çıktılarını almak için
    echo "<pre>";
    echo "Request:\n" . $client->__getLastRequest() . "\n";
    echo "Response:\n" . $client->__getLastResponse() . "\n";
    echo "</pre>";

} catch (SoapFault $e) {
    echo "Hata: " . $e->getMessage();

}
?>





<?php

$wsdl = 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl';



try {
    $client = new SoapClient($wsdl, [
        'login'    => 'asbu-bidb-01',
        'password' => 'sbQC6BPY9yVJxv5W',
        'cache_wsdl' => WSDL_CACHE_NONE,
        'trace' => 1,
        'exceptions' => true,
        'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ])
    ]);

    echo "<h3>SOAP Fonksiyonları</h3>";
    echo "<pre>"; print_r($client->__getFunctions()); echo "</pre>";

    echo "<h3>SOAP Tipleri</h3>";
    echo "<pre>"; print_r($client->__getTypes()); echo "</pre>";

} catch (SoapFault $e) {
    echo "SOAP Hatası: " . $e->getMessage();
}
?>
