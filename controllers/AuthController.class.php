<?php
namespace statera\controllers;
use statera\core\Application;
use statera\core\Controller;
use statera\core\Request;
use statera\core\Response;
use statera\models\User;
use statera\models\PassRecover;
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
            $clsUser->sPostAction = $clsUser::POST_ACTION_INSERT;
            $clsUser->loadData($clsRequest->getBody());
            if ($clsUser->validate() && $clsUser->insert()) {
                Application::$clsApp->clsSession->setFlash('success', 'Account succesfully created');
                Application::$clsApp->clsResponse->redirect('/');
            }
            return $this->render('register', [
                'clsUser' => $clsUser
            ]);
            
        }
        return $this->render('register', [
            'clsUser' => $clsUser
        ]);
    }

    public function teste() {
        return $this->render('teste');
    }

    public function editUser(Request $clsRequest) {
        if ($clsRequest->isPost()) {
            Application::$clsApp->oUser->sPostAction = Application::$clsApp->oUser::POST_ACTION_EDIT;
            Application::$clsApp->oUser->loadData($clsRequest->getBody());
            if (Application::$clsApp->oUser->validate()) {
                if (Application::$clsApp->oUser->changePassword === 'on') {
                    Application::$clsApp->oUser->setPasswordHash();
                }
                if (Application::$clsApp->oUser->edit()) {
                    Application::$clsApp->clsSession->setFlash('success', 'Account succesfully edited');
                    Application::$clsApp->clsResponse->redirect('/');
                }
            }
            return $this->render('edit_user', [
                'clsUser' => Application::$clsApp->oUser
            ]);
            
        }
        return $this->render('edit_user', [
            'clsUser' => Application::$clsApp->oUser
        ]);
    }

    public function passRecover(Request $clsRequest) {
        
        $clsRecoverPass = new PassRecover;
        $this->setLayout('auth');
        if ($clsRequest->isPost()) {
            
            $clsRecoverPass->loadData($clsRequest->getBody());
            
            if ($clsRecoverPass->validate() && $clsRecoverPass->sendRecoverMail()) {
                Application::$clsApp->clsSession->setFlash(
                    'success'
                    , 'An email have been sent for the specified address, please, 
                    check your inbox in the next feel minutes.'
                );
                Application::$clsApp->clsResponse->redirect('/');
            }
            return $this->render('password_recover', [
                'clsRecoverPass' => $clsRecoverPass
            ]);
            
        }
        return $this->render('password_recover', [
            'clsRecoverPass' => $clsRecoverPass
        ]);
    }

    public function changePassword(Request $clsRequest, Response $clsResponse) {
        $clsRecoverPass = new PassRecover;
        $clsRecoverPass->loadData($clsRequest->getBody());
        $this->setLayout('auth');
        if ($clsRequest->isPost()) {
            $clsRecoverPass->sPostAction = $clsRecoverPass::POST_ACTION_EDIT;
            $clsRecoverPass->validateToken($clsRequest->getBody()['token']);
            if ($clsRecoverPass->validate() && $clsRecoverPass->changePassword()) {
                Application::$clsApp->clsSession->setFlash('success', 'Password successfully changed');
                Application::$clsApp->clsResponse->redirect('/');
            }
        }
        return $this->render('change_password', [
            'clsRecoverPass' => $clsRecoverPass
            , 'token' => $clsRequest->getBody()['token']
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