<?php
namespace statera\core\db;
use statera\core\Application;
use statera\core\Model;

abstract class DbModel extends Model {
    abstract public function getTableName(): string;

    abstract public function getAttributes(): array;

    abstract public function primaryKey(): string;

    public function insert() {
        $sTableName = $this->getTableName();
        $aAttributes = $this->getAttributes();
        $aParams = array_map(fn($v) => ':' . $v, $aAttributes);
        $oSql = Application::$clsApp->clsDb->prepare('INSERT INTO ' . $sTableName . '(' 
            . implode(', ', $aAttributes) 
            . ') VALUES (' 
            . implode(', ', $aParams) . ')');


        
        foreach ($aAttributes as $sAttribute)
            $oSql->bindValue(':' . $sAttribute, $this->{$sAttribute});    
                    
        
        $oSql->execute();
        
        return true;
    }

    public function edit() {
        $sTableName = $this->getTableName();
        $aAttributes = $this->getAttributes();
        array_unshift($aAttributes, $this->primaryKey());
        $aParams = array_map(fn($v) => ':' . $v, $aAttributes);
        $sSql = 'UPDATE ' . $sTableName . ' SET ';
        for($i=0;$i<count($aAttributes);$i++){
            if ($i==0)
                continue;
            $sSql .= $aAttributes[$i] . ' = ' . $aParams[$i];
            if (count($aAttributes) > 1 && $i < count($aAttributes) - 1)
                $sSql .= ', ';
        }
        $sSql .= ' WHERE ' . $aAttributes[0] . ' = ' . $aParams[0];
        $oSql = Application::$clsApp->clsDb->prepare($sSql);
        
        foreach ($aAttributes as $sAttribute)
            $oSql->bindValue(':' . $sAttribute, $this->{$sAttribute});            
        
        $oSql->execute();
        return true;
    }

    public function findOne($aWhere) {
        $sTableName = $this->getTableName();
        $sWhere = implode(' AND ', array_map(fn($v) => $v . ' = :' . $v, array_keys($aWhere)));
        $oSql = $this->prepare('SELECT * FROM ' . $sTableName  
            . ' WHERE ' . $sWhere);
            
        foreach ($aWhere as $sKey => $sValue)
            $oSql->bindValue(':' . $sKey, $sValue);            
        
        $oSql->execute();

        return $oSql->fetchObject(static::class);

    }

    public static function prepare($sSql){
        return Application::$clsApp->clsDb->prepare($sSql);
    }
}