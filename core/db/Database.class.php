<?php
namespace statera\core\db;
use statera\core\Application;

class Database {
    public \PDO $oPdo;
    public function __construct(array $aConfig) {
        $sDsn = $aConfig['sDsn'] ?? '';
        $sUser = $aConfig['sUser'] ?? '';
        $sPassword = $aConfig['sPassword'] ?? '';
        $this->oPdo = new \PDO($sDsn, $sUser, $sPassword);
        $this->oPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
        $aFiles = scandir(Application::$ROOT_DIR . '/migrations');
        $aAppliedMigrations = $this->getAppliedMigrations();
        $aToApplyMigrations = array_diff($aFiles, $aAppliedMigrations);
        $aNewMigrations = [];
      
        foreach ($aToApplyMigrations as $sMigration) {
            if ($sMigration === '.' || $sMigration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $sMigration;
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

    public function prepare($sSql) {
        return $this->oPdo->prepare($sSql);
    }

    protected function log($sMessage) {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $sMessage . PHP_EOL;
    }
}