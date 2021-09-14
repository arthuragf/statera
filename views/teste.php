<?php
use statera\core\Application;
use statera\core\environment\DotEnv;

$sRootPath = dirname(__DIR__); 
require_once $sRootPath . DIRECTORY_SEPARATOR . 'autoload.php';
(new DotEnv($sRootPath))->load();

$aConfig = [
    'sUserClass' => \statera\models\User::class
    , 'sRootPath' => $sRootPath
    , 'sCommonUrl' => getenv('COMMON_URL')
    , 'db' => [
        'sDsn' => getenv('DB_DSN')
        , 'sUser' => getenv('DB_USER')
        , 'sPassword' => getenv('DB_PASSWORD')
    ]
    , 'aMailParams' => [
        'bIsSmtp' => getenv('HOST_ISSMTP')
        , 'nRequireAuth' => getenv('HOST_AUTH')
        , 'sUserName' => getenv('HOST_USER')
        , 'sMailPass' => getenv('HOST_PASS')
        , 'sSmtpSecure' => getenv('HOST_SMPT_SECURE')
        , 'sHostSmtp' => getenv('HOST_ADDRESS')
        , 'nPortSmtp' => getenv('HOST_PORT')
        , 'nIsHtml' => getenv('HOST_ISHTML')
        , 'sFromName' => getenv('HOST_FROMNAME')
    ]
];
$clsApp = new Application($aConfig);

Application::$clsApp->clsMail->sendMail(
    [
        'aRecipient' => [
            'sRecipientEmail' => 'arthur.agf@gmail.com'
            , 'sRecipientName' => 'ARTHUR_FARIA'
        ]
        , 'sSubject' => 'Subject'
        , 'sBody' => 'Body'
        , 'sAltBody' => 'Alternative Body'
    ]
);



