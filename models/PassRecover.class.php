<?php
namespace statera\models;

use statera\core\Application;
use statera\core\db\DbModel;
use statera\core\exceptions\ExpiredTokenException;

class PassRecover extends DbModel{
    public string $token = '';
    public int $users_id = 0;
    public User $oUser;
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    

    public function getTableName(): string {
        return 'password_recover';
    }

    public function primaryKey(): string {
        return 'id';
    }

    public function getAttributes(): array {
        return ['token', 'users_id'];
    }

    public function insert() {
        $clsUser = new Application::$clsApp->sUserClass;
        $this->oUser = $clsUser->findOne(['email' => $this->email]);
        
        if (!$this->oUser) {
            $this->addError('email', 'That is no User with this Email');
            return false;
        }
        $this->users_id = $this->oUser->id;
        $this->token = bin2hex(random_bytes(4));
        return parent::insert();
    }

    public function rules(): array {
        return [
            'email' => [self::RULE_EMAIL]
        ];
    }

    public function editRules(): array
    {
        return [
            'password' => [
                self::RULE_REQUIRED
                , [self::RULE_MIN, 'min' => 8]
                , [self::RULE_MAX, 'max' => 24]
            ]
            , 'confirmPassword' =>[self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
            , 'token' => [self::RULE_REQUIRED]
        ];
    }
    public function labels(): array {
        return [
            'email' => 'Account email'
            , 'password' => 'New password'
            , 'confirmPassword' => 'Confirm new password'
        ];
    }

    public function validateToken($sToken) {
        $oRecoverPass = $this->findOne(['token' => $sToken]);
        if (!$oRecoverPass ||
            date_diff(
                new \DateTime(date('Y-m-d H:i:s'))
                , new \DateTime($oRecoverPass->created_at)
            )->h >= 2
        ) {
            throw new ExpiredTokenException();
        }
        $this->users_id = $oRecoverPass->users_id;
    }

    public function changePassword(){
        $clsUser = new Application::$clsApp->sUserClass;
        $this->oUser = $clsUser->findOne(
            [$clsUser->primaryKey() => $this->users_id]
        );
        $this->oUser->password = $this->password;
        $this->oUser->setPasswordHash();
        return $this->oUser->edit();
    }
}