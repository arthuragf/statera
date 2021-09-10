<?php
namespace statera\core\db;
use statera\core\Application;

class MigrationsModel {

    public \PDO $oPdo;
    private $sRootDir;

    public function __construct(array $aConfig) {
        $sDsn = $aConfig['db']['sDsn'] ?? '';
        $sUser = $aConfig['db']['sUser'] ?? '';
        $sPassword = $aConfig['db']['sPassword'] ?? '';
        $this->oPdo = new \PDO($sDsn, $sUser, $sPassword);
        $this->oPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->sRootDir = $aConfig['sRootDir'];
    }

    private function createMigrationsTable() {
        $this->oPdo->exec('CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;');
    }

    public function applyMigrations() {
        $this->createMigrationsTable();
        $aFiles = scandir($this->sRootDir . '/migrations/migration_files');
        $aAppliedMigrations = $this->getAppliedMigrations();
        $aToApplyMigrations = array_diff($aFiles, $aAppliedMigrations);
        $aNewMigrations = [];
      
        foreach ($aToApplyMigrations as $sMigration) {
            if ($sMigration === '.' || $sMigration === '..') {
                continue;
            }

            require_once $this->sRootDir . '/migrations/migration_files/' . $sMigration;
            $sClsName = pathinfo($sMigration, PATHINFO_FILENAME);
            
            $oInstance = new $sClsName($this);
            $this->log('applying migration' . $sMigration);
            $oInstance->up();
            $this->log('applied migration' . $sMigration);
            $aNewMigrations[] = $sMigration;
            
        }
         
        if (!empty($aNewMigrations)) {
            $this->saveMigrations($aNewMigrations);
        } else {
            $this->log('All migrations are applied');
        }
    }

    private function getAppliedMigrations() {
        $oSql = $this->oPdo->prepare('SELECT migration FROM migrations');

        $oSql->execute();

        return $oSql->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $aNewMigrations) {

        $sNewMigrations = implode(',',array_map(fn($v) => "('$v')", $aNewMigrations));
        
        $oSql = $this->oPdo->prepare('INSERT INTO migrations (migration) VALUES ' . $sNewMigrations);

        $oSql->execute();
    }

    private function log($sMessage) {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $sMessage . PHP_EOL;
    }
}