<?php
namespace statera\models;

use statera\core\Application;
use statera\core\UserModel;

class User extends UserModel{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;
    public int $id = 0;
    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public int $status = self::STATUS_ACTIVE;
    public string $changePassword = '';

    public function getTableName(): string {
        return 'users';
    }

    public function primaryKey(): string {
        return 'id';
    }

    public function getAttributes(): array {
        return ['firstname', 'lastname', 'email', 'password', 'status'];
    }

    public function insert() {
        $this->status = self::STATUS_INACTIVE;
        $this->setPasswordHash();
        if (parent::insert())
            $this->id = Application::$clsApp->clsDb->oPdo->lastInsertId();
        return true;
    }

    public function edit() {
        $this->id = !empty($this->id) 
            ? $this->id
            : Application::$clsApp->clsSession->get('user');
        return parent::edit();
    }

    public function rules(): array {
        return [
            'firstname' => [self::RULE_REQUIRED]
            , 'lastname' => [self::RULE_REQUIRED]
            , 'email' => [
                self::RULE_REQUIRED
                , self::RULE_EMAIL
                , [self::RULE_UNIQUE, 'oClass' => $this] 
            ]
            , 'password' => [
                self::RULE_REQUIRED
                , [self::RULE_MIN, 'min' => 8]
                , [self::RULE_MAX, 'max' => 24]
            ]
            , 'confirmPassword' =>[self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function editRules(): array {
        return array_merge($this->rules(), [
            'password' => [
                [
                    self::RULE_REQUIRED
                    , 'require_activation' => true
                    , 'field' => 'changePassword'
                    , 'value' => 'on'
                ], [
                    self::RULE_MIN
                    , 'min' => 8
                    , 'require_activation' => true
                    , 'field' => 'changePassword'
                    , 'value' => 'on'
                ], [
                    self::RULE_MAX, 'max' => 24
                    , 'require_activation' => true
                    , 'field' => 'changePassword'
                    , 'value' => 'on'
                ]
            ]
            , 'confirmPassword' =>[
                [
                    self::RULE_REQUIRED
                    , 'require_activation' => true
                    , 'field' => 'changePassword'
                    , 'value' => 'on' 
                ], [
                    self::RULE_MATCH
                    , 'match' => 'password'
                    , 'require_activation' => true
                    , 'field' => 'changePassword'
                    , 'value' => 'on'
                ]
            ]
        ]);
    }

    public function labels(): array {
        return [
            'firstname' => 'First name'
            , 'lastname' => 'Last name'
            , 'email' => 'Email'
            , 'password' => 'Password'
            , 'confirmPassword' => 'Confirm password'
        ];
    }

    public function selectOptions():array {
        return [
            'status' => [
                '-1' => 'Select a status'
                , self::STATUS_ACTIVE => 'Active'
                , self::STATUS_INACTIVE => 'Inactive'
            ]
        ];
    }

    public function setPasswordHash() {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    public function getDisplayName(): string {
        return $this->firstname . ' ' . $this->lastname;
    }
}