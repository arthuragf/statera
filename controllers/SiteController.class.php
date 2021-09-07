<?php
namespace statera\controllers;

use statera\core\Controller;
use statera\core\Application;
use statera\core\middlewears\AuthMiddlewear;
use statera\core\Request;
use statera\core\Response;
use statera\models\LoginForm;

class SiteController extends Controller{
    public const GUEST_ALIAS = 'Guest';

    public function __construct(){
        $this->registerMiddlewear(new AuthMiddlewear(['home']));
    }

    public function home(Request $clsRequest, Response $clsResponse) {
        if (empty(Application::$clsApp->oUser)) {
            $clsResponse->redirect('/login');
        }

        $aParams = [
            'sName' => Application::$clsApp->oUser->getDisplayName()
        ];
        return $this->render('home', $aParams);
    }

    public function login(Request $clsRequest, Response $clsResponse) {
        $clsLoginForm = new LoginForm();
        if ($clsRequest->isPost()) {
            $clsLoginForm->loadData($clsRequest->getBody());
            if ($clsLoginForm->validate() && $clsLoginForm->login()) {
                $clsResponse->redirect('/');
                return;
            }
        }

        $this->setLayout('auth');
        return $this->render('login', ['clsModel' => $clsLoginForm]);
    }
}