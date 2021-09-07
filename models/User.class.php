<?php
namespace statera\models;
use statera\core\UserModel;

class User extends UserModel{
    public function getTableName(): string {
        return '';
    } 

    public function getAttributes(): array {
        return [];
    } 

    public function primaryKey(): string {
        return '';
    } 

    public function getDisplayName(): string {
        return '';
    }

    public function rules(): array {
        return [];
    }
}