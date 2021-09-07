<?php
namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\User;
use app\models\LoginForm;
use app\core\middlewears\AuthMiddlewear;

class AuthController extends Controller{

    public function __construct() {

        $this->registerMiddlewear(new AuthMiddlewear(['profile']));
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

    public function register(Request $clsRequest) {
        $clsUser = new User();
        if ($clsRequest->isPost()) {
            $clsUser->loadData($clsRequest->getBody());
            
            if ($clsUser->validate() && $clsUser->save()) {
                Application::$clsApp->clsSession->setFlash('success', 'Thanks for registering');
                Application::$clsApp->clsResponse->redirect('/');
            }

            return $this->render('register', [
                'clsUser' => $clsUser
            ]);
            
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'clsUser' => $clsUser
        ]);
    }
    
    public function logout(Request $clsRequest, Response $clsResponse) {
        Application::$clsApp->logout();
        $clsResponse->redirect('/');
    }

    public function profile() {
        return $this->render('profile');
    }

}