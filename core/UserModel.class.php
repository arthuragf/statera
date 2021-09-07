<?php
namespace statera\core;
use statera\core\db\DbModel;

abstract class UserModel extends DbModel {
    abstract public function getDisplayName(): string;
}