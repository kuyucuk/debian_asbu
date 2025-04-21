<?php

$wsdl = 'https://pbs.asbu.edu.tr/webservices/personelinfo?wsdl';

$options = [
    'login' => 'asbu-bidb-01',
    'password' => 'sbQC6BPY9yVJxv5W',
    'trace' => 1,
    'exceptions' => true,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'stream_context' => stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ]
    ])
];

try {
    $client = new SoapClient($wsdl, $options);

    $functions = $client->__getFunctions();
    echo "<pre>Fonksiyonlar:\n";
    print_r($functions);
    echo "</pre>";

    // Örnek sorgu
    $response = $client->bynamesurname([
        'adi' => 'MEHMET',
        'soyadi' => 'ARAZ'
    ]);

    echo "<pre>Cevap:\n";
    print_r($response);
    echo "</pre>";

} catch (SoapFault $e) {
    echo "SOAP Hatası: " . $e->getMessage();
}
