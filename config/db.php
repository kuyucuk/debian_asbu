<?php

return [
    'class' => 'yii\\db\\Connection',
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=127.0.0.1;dbname=universite_degerlendirme',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
