<?php

namespace statera\models;

use statera\core\Model;
use statera\core\Application;

class LoginForm extends Model {
    public string $email = '';
    public string $password = '';

    public function rules(): array {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function login() {
        $clsUser = new User();
        $oUser = $clsUser->findOne(['email' => $this->email, 'status' => $clsUser::STATUS_ACTIVE]);

        if (!$oUser) {
            $this->addError('email', 'User does not exist with this email');
            return false;
        }

        if (!password_verify($this->password, $oUser->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }

        return Application::$clsApp->login($oUser);
    }

    public function labels(): array {
        return [
            'email' => 'Your Email'
            , 'password' => 'Password'
        ];
    }
}