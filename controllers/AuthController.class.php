<?php
namespace statera\controllers;
use statera\core\Application;
use statera\core\Controller;
use statera\core\exceptions\InvalidTokenException;
use statera\core\Request;
use statera\core\Response;
use statera\models\User;
use statera\models\PassRecover;
use statera\models\LoginForm;
use statera\core\middlewears\AuthMiddlewear;

class AuthController extends Controller{

    public function __construct() {
        $this->registerMiddlewear(new AuthMiddlewear(['editUser']));
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
                if ($this->sendRegisterMail($clsUser)) {
                    Application::$clsApp->clsSession->setFlash('success', 'An email have been sent for adress confirmation');
                    Application::$clsApp->clsResponse->redirect('/');
                }
            }
            return $this->render('register', [
                'clsUser' => $clsUser
            ]);
            
        }
        return $this->render('register', [
            'clsUser' => $clsUser
        ]);
    }

    public function activateAccount(Request $clsRequest, Response $clsResponse) {
        $clsRequest->aBase64Fields[] = 'id';
        $clsUser = new Application::$clsApp->sUserClass;
        $clsUser->loadData($clsRequest->getBody());
        $oUser = $clsUser->findOne(['id' => $clsUser->id]);
        $oUser->status = $oUser::STATUS_ACTIVE;
        if ($oUser->edit()){
            Application::$clsApp->clsSession->setFlash('success', 'Account succesfully activated');
            return $this->login($clsRequest, $clsResponse);
        }
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
            
            if ($clsRecoverPass->validate() && $clsRecoverPass->insert()) {
                if ($this->sendRecoverMail($clsRecoverPass)){
                    Application::$clsApp->clsSession->setFlash(
                        'success'
                        , 'An email have been sent for the specified address, please, 
                        check your inbox in the next feel minutes.'
                    );
                    Application::$clsApp->clsResponse->redirect('/');
                }
            }
            return $this->render('password_recover', [
                'clsRecoverPass' => $clsRecoverPass
            ]);
            
        }
        return $this->render('password_recover', [
            'clsRecoverPass' => $clsRecoverPass
        ]);
    }

    public function sendRecoverMail(PassRecover $clsRecoverMail) {
        return Application::$clsApp->clsMail->sendMail(
            [
                'aRecipient' => [
                    'sRecipientEmail' => $clsRecoverMail->oUser->email
                ]
                , 'sSubject' => 'Password change request'
                , 'sBody' => $this->getRecoverMailBody($clsRecoverMail)
            ]
        );
    }

    private function getRecoverMailBody(PassRecover $clsRecoverMail) {
        $sUrl = Application::$COMMON_URL . '/change_password/?token=' . $clsRecoverMail->token;
        $sBody = 'Hi ' . $clsRecoverMail->oUser->getDisplayName() . ', <br>';
        $sBody .= 'To change your password, please, click on the link below.<br>';
        $sBody .= sprintf('<a href="%s">Change my password</a>'
            , $sUrl
        );
        $sBody .= ' or paste the address in your navigator <br>';
        $sBody .= $sUrl;
        return $sBody;
    }

    public function changePassword(Request $clsRequest, Response $clsResponse) {
        $clsRecoverPass = new PassRecover;
        $aBody = $clsRequest->getBody();
        $this->setLayout('auth');
        if (empty($aBody['token'])) {
            throw new InvalidTokenException();
        }
        $clsRecoverPass->loadData($aBody);
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
            , 'token' => $aBody['token']
        ]);
    }
    
    public function logout(Request $clsRequest, Response $clsResponse) {
        Application::$clsApp->logout();
        $clsResponse->redirect('/');
    }

    public function profile() {
        return $this->render('profile');
    }

    public function sendRegisterMail(User $clsUser) {
        return Application::$clsApp->clsMail->sendMail(
            [
                'aRecipient' => [
                    'sRecipientEmail' => $clsUser->email
                ]
                , 'sSubject' => 'Statera: Email confirmation'
                , 'sBody' => $this->getRegisterMailBody($clsUser)
            ]
        );
    }

    private function getRegisterMailBody(User $clsUser) {
        $sUrl = Application::$COMMON_URL . '/activate_account/?id=' . base64_encode($clsUser->id);
        $sBody = 'Hi ' . $clsUser->getDisplayName() . ', <br>';
        $sBody .= 'To activate your account, please, click on the link below.<br>';
        $sBody .= sprintf('<a href="%s">Activate my account</a>'
            , $sUrl
        );
        $sBody .= ' or paste the address in your navigator <br>';
        $sBody .= $sUrl;
        return $sBody;
    }
}