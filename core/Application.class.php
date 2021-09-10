<?php
namespace statera\core;

use statera\core\db\ApplicationModel;

class Application {
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    public static string $ROOT_DIR;
    public static string $COMMON_URL;
    public static Application $clsApp;
    public string $sUserClass;
    public string $sLayout = 'main';
    public ApplicationModel $clsDb;
    public Router $clsRouter;
    public Request $clsRequest;
    public Response $clsResponse;
    public ?Controller $clsController = null;
    public Session $clsSession;
    public ?UserModel $oUser;
    public View $clsView;
    protected array $aEventListeners;

    public function __construct(array $aConfig) {
        $this->sUserClass = $aConfig['sUserClass'];
        self::$ROOT_DIR = $aConfig['sRootPath'];
        self::$COMMON_URL = $aConfig['sCommonUrl'];
        self::$clsApp = $this;
        $this->clsSession = new Session();
        $this->clsRequest = new Request();
        $this->clsResponse = new Response();
        $this->clsRouter = new Router($this->clsRequest, $this->clsResponse);
        $this->clsDb = new ApplicationModel($aConfig['db']);
        $this->clsView = new View();

        $clsUser = new $this->sUserClass;
        $sPrimaryKey = $clsUser->primaryKey();
        $nPrimaryValue = $this->clsSession->get('user');
        if ($nPrimaryValue) {
           $this->oUser = $clsUser->findOne([$sPrimaryKey => $nPrimaryValue]);
        } else {
            $this->oUser = null;
        }
    }

    public function getController() {
        return $this->clsController;
    }

    public function setClsController(Controller $clsController) {
        $this->clsController = $clsController;
    }

    public function run() {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->clsRouter->resolve();
        } catch (\Exception $e) {
            $this->clsResponse->setStatusCode($e->getCode());
            echo $this->clsView->renderView('_error', ['oException' => $e]);
        }
        $this->triggerEvent(self::EVENT_AFTER_REQUEST);
    }

    public function login(UserModel $oUser) {
        $this->oUser = $oUser;
        $sPrimaryKey = $oUser->primaryKey();
        $nPrimaryValue = $oUser->{$sPrimaryKey};
        $this->clsSession->set('user', $nPrimaryValue);
        return true;
    }

    public function logout() {
        $this->oUser = null;
        $this->clsSession->unset('user');
    }

    public static function isGuest() {
        return !self::$clsApp->oUser;
    }

    public function on($sEventName, $sCallback) {
        $this->aEventListeners[$sEventName][] = $sCallback;
    }

    public function triggerEvent($sEventName){
        
        $aCallbacks = $this->aEventListeners[$sEventName] ?? [];
    
        foreach ($aCallbacks as $sCallback) {
            call_user_func($sCallback);
        }
    }
}