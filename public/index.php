<?php
use statera\core\Application;
//use statera\controllers\SiteController;
//use statera\controllers\AuthController;
use statera\core\environment\DotEnv;


spl_autoload_register(function($sClass){
    $sBaseDir = dirname(__DIR__);
    
    $sClass = str_replace('statera', $sBaseDir, $sClass);

    $sFile = str_replace('\\', DIRECTORY_SEPARATOR, $sClass) . '.class.php';

    if (file_exists($sFile)) {
        require $sFile;
    }
});

(new DotEnv(dirname(__DIR__)))->load();

$aConfig = [
    'sUserClass' => \statera\models\User::class,
    'db' => [
        'sDsn' => $_ENV['DB_DSN']
        , 'sUser' => $_ENV['DB_USER']
        , 'sPassword' => $_ENV['DB_PASSWORD']
    ]
];
$clsApp = new Application(dirname(__DIR__), $aConfig);

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
    $clsApp->clsRouter->get('/', [SiteController::class, 'login']);
}
$clsApp->clsRouter->get('/home', [SiteController::class, 'home']);
$clsApp->clsRouter->get('/login', [AuthController::class, 'login']);
$clsApp->clsRouter->post('/login', [AuthController::class, 'login']);
//$clsApp->clsRouter->get('/contact', [SiteController::class, 'contact']);
//$clsApp->clsRouter->post('/contact', [SiteController::class, 'contact']);
//$clsApp->clsRouter->get('/register', [AuthController::class, 'register']);
//$clsApp->clsRouter->post('/register', [AuthController::class, 'register']);
//$clsApp->clsRouter->get('/logout', [AuthController::class, 'logout']);
//$clsApp->clsRouter->get('/profile', [AuthController::class, 'profile']);

$clsApp->run();