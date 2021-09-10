<?php
use statera\core\Application;
use statera\controllers\AuthController;
use statera\controllers\SiteController;
use statera\core\environment\DotEnv;

$sRootPath = dirname(__DIR__); 
require_once $sRootPath . DIRECTORY_SEPARATOR . 'autoload.php';
(new DotEnv($sRootPath))->load();

$aConfig = [
    'sUserClass' => \statera\models\User::class,
    'sRootPath' => $sRootPath,
    'sCommonUrl' => $_ENV['COMMON_URL'],
    'db' => [
        'sDsn' => $_ENV['DB_DSN']
        , 'sUser' => $_ENV['DB_USER']
        , 'sPassword' => $_ENV['DB_PASSWORD']
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
    //echo 123;
    //die();
    $clsApp->clsRouter->get('/', [AuthController::class, 'login']);
}
$clsApp->clsRouter->get('/home', [SiteController::class, 'home']);
$clsApp->clsRouter->get('/login', [AuthController::class, 'login']);
$clsApp->clsRouter->post('/login', [AuthController::class, 'login']);
//$clsApp->clsRouter->get('/contact', [SiteController::class, 'contact']);
//$clsApp->clsRouter->post('/contact', [SiteController::class, 'contact']);
$clsApp->clsRouter->get('/register', [AuthController::class, 'register']);
$clsApp->clsRouter->post('/register', [AuthController::class, 'register']);
$clsApp->clsRouter->get('/logout', [AuthController::class, 'logout']);
$clsApp->clsRouter->get('/profile', [AuthController::class, 'profile']);
$clsApp->clsRouter->get('/edit_user', [AuthController::class, 'editUser']);
$clsApp->clsRouter->post('/edit_user', [AuthController::class, 'editUser']);

$clsApp->run();