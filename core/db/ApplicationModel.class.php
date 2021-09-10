<?php
namespace statera\core\db;

class ApplicationModel {
    public \PDO $oPdo;
    public function __construct(array $aConfig) {
        $sDsn = $aConfig['sDsn'] ?? '';
        $sUser = $aConfig['sUser'] ?? '';
        $sPassword = $aConfig['sPassword'] ?? '';
        $this->oPdo = new \PDO($sDsn, $sUser, $sPassword);
        $this->oPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function prepare($sSql){
        return $this->oPdo->prepare($sSql);
    }
}