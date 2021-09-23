<?php
use statera\core\Application;
use statera\controllers\AuthController;
use statera\controllers\SiteController;
use statera\core\environment\DotEnv;

$sRootPath = dirname(__DIR__); 
require_once $sRootPath . DIRECTORY_SEPARATOR . 'autoload.php';
(new DotEnv($sRootPath))->load();

$aConfig = [
    'sUserClass' => \statera\models\User::class
    , 'sRootPath' => $sRootPath
    , 'sPublicPath' => '/public'
    , 'sAssetsPath' => '/assets'
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

if (!empty($_ENV['DEBUG_MODE'])) {
    $clsApp->on(Application::EVENT_BEFORE_REQUEST, function(){
        echo 'Before request';
    });
    $clsApp->on(Application::EVENT_AFTER_REQUEST, function(){
        echo 'After request';
    });
}

$clsApp->clsRouter->get('/', [SiteController::class, 'home']);
if(Application::isGuest()) {
    $clsApp->clsRouter->get('/', [AuthController::class, 'login']);
}
$clsApp->clsRouter->get('/home', [SiteController::class, 'home']);
$clsApp->clsRouter->get('/login', [AuthController::class, 'login']);
$clsApp->clsRouter->post('/login', [AuthController::class, 'login']);
$clsApp->clsRouter->get('/register', [AuthController::class, 'register']);
$clsApp->clsRouter->post('/register', [AuthController::class, 'register']);
$clsApp->clsRouter->get('/logout', [AuthController::class, 'logout']);
$clsApp->clsRouter->get('/profile', [AuthController::class, 'profile']);
$clsApp->clsRouter->get('/edit_user', [AuthController::class, 'editUser']);
$clsApp->clsRouter->post('/edit_user', [AuthController::class, 'editUser']);
$clsApp->clsRouter->get('/pass_recovery', [AuthController::class, 'passRecover']);
$clsApp->clsRouter->post('/pass_recovery', [AuthController::class, 'passRecover']);
$clsApp->clsRouter->get('/change_password', [AuthController::class, 'changePassword']);
$clsApp->clsRouter->post('/change_password', [AuthController::class, 'changePassword']);
$clsApp->clsRouter->get('/activate_account', [AuthController::class, 'activateAccount']);
$clsApp->clsRouter->get('/teste', [AuthController::class, 'teste']);

$clsApp->run();