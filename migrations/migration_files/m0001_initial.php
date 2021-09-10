<?php

use statera\core\db\MigrationsModel;

class m0001_initial {
    public MigrationsModel $clsDb;

    public function __construct (MigrationsModel $clsDb) {
        $this->clsDb = $clsDb;
    }
    
    public function up() {
        $this->clsDb->oPdo->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(512) NOT NULL,
                status TINYINT NOT NULL DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ');
    }

    public function down() {
        $this->clsDb->oPdo->exec('
            DROP TABLE users;
        ');
    }
}