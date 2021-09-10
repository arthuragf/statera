<?php
use statera\core\db\MigrationsModel;
use statera\core\environment\DotEnv;

require_once './../autoload.php';
$sRootPath = dirname(__DIR__); 
(new DotEnv($sRootPath))->load();

$aConfig = [
    'sRootDir' => dirname(__DIR__),
        'db' => [
        'sDsn' => $_ENV['DB_DSN']
        , 'sUser' => $_ENV['DB_USER']
        , 'sPassword' => $_ENV['DB_PASSWORD']
    ]
];
$clsMigrations = new MigrationsModel($aConfig);
$clsMigrations->applyMigrations();