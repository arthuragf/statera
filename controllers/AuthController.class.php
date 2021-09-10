<?php
namespace statera\controllers;
use statera\core\Application;
use statera\core\Controller;
use statera\core\Request;
use statera\core\Response;
use statera\models\User;
use statera\models\LoginForm;
use statera\core\middlewears\AuthMiddlewear;

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
        $this->setLayout('auth');
        if ($clsRequest->isPost()) {
            $clsUser->loadData($clsRequest->getBody());
            
            if ($clsUser->validate() && $clsUser->insert()) {
                Application::$clsApp->clsSession->setFlash('success', 'Account succesfully created');
                Application::$clsApp->clsResponse->redirect('/');
                exit;
            }
            return $this->render('register', [
                'clsUser' => $clsUser
            ]);
            
        }
        return $this->render('register', [
            'clsUser' => $clsUser
        ]);
    }

    public function editUser(Request $clsRequest) {
        $clsUser = new Application::$clsApp->sUserClass;
        $sPrimaryKey = $clsUser->primaryKey();
        $nPrimaryValue = Application::$clsApp->clsSession->get('user');
        Application::$clsApp->oUser = $clsUser->findOne([$sPrimaryKey => $nPrimaryValue]);

        if ($clsRequest->isPost()) {
            $clsUser->loadData($clsRequest->getBody());
            
            if ($clsUser->validate() && $clsUser->edit()) {
                Application::$clsApp->clsSession->setFlash('success', 'Account succesfully created');
                Application::$clsApp->clsResponse->redirect('/');
                exit;
            }
            return $this->render('edit_user', [
                'clsUser' => $clsUser
            ]);
            
        }
        return $this->render('edit_user', [
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