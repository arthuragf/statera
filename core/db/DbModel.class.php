<?php
namespace statera\core\db;
use statera\core\Application;
use statera\core\Model;

abstract class DbModel extends Model {
    abstract public function getTableName(): string;

    abstract public function getAttributes(): array;

    abstract public function primaryKey(): string;

    public function save() {
        $sTableName = $this->getTableName();
        $aAttributes = $this->getAttributes();
        $aParams = array_map(fn($v) => ':' . $v, $aAttributes);
        $oSql = self::prepare('INSERT INTO ' . $sTableName . '(' 
            . implode(', ', $aAttributes) 
            . ') VALUES (' 
            . implode(', ', $aParams) . ')');
        
        foreach ($aAttributes as $sAttribute)
            $oSql->bindValue(':' . $sAttribute, $this->{$sAttribute});            
        
        $oSql->execute();
        return true;
    }

    public function findOne($aWhere) {
        $sTableName = $this->getTableName();
        $aAttributes = array_keys($aWhere);
        $sWhere = implode(' AND ', array_map(fn($v) => $v . ' = :' . $v, array_keys($aWhere)));
        $oSql = self::prepare('SELECT * FROM ' . $sTableName  
            . ' WHERE ' . $sWhere);
            
        foreach ($aWhere as $sKey => $sValue)
            $oSql->bindValue(':' . $sKey, $sValue);            
        
        $oSql->execute();

        return $oSql->fetchObject(static::class);

    }

    public static function prepare($sSql) {
        return Application::$clsApp->clsDb->oPdo->prepare($sSql);
    }

}