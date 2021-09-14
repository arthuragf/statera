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

        $this->clsDb->oPdo->exec('
            CREATE TABLE IF NOT EXISTS password_recover (
                id INT AUTO_INCREMENT PRIMARY KEY,
                users_id INT NOT NULL,
                token VARCHAR(8) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (users_id) REFERENCES users(id)
            ) ENGINE=INNODB;
        ');
    }

    public function down() {
        $this->clsDb->oPdo->exec('
            DROP TABLE users;
        ');

        $this->clsDb->oPdo->exec('
            DROP TABLE password_recover;
        ');
    }
}