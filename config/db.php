<?php

return [
   'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=erpdiamante',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
    
   /* 'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=192.168.1.2;dbname=erpdiamante',
    'username' => 'desarrollo',
    'password' => 'm@quil@1119',
    'charset' => 'utf8',
    attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],    */

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
