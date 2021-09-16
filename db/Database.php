<?php

namespace App\core\db;

use App\core\App;
use App\migrations\m0001_add_pass_column;
use App\migrations\m0001_chat;
use App\migrations\m0001_ini;
use App\migrations\m0001_post;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $pass = $config['pass'];
        $dsn = $config['dsn'];
        $user = $config['user'];
        $this->pdo = new \PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applieMigration()
    {
        $neawmigs = [];

        $this->createtable();
        $applied = $this->appliedMigration();
        $filles = scandir(App::$ROOT_DIR.'/migrations');
        foreach ($filles as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $classmain = pathinfo($file, PATHINFO_FILENAME);

            $neawmigs[] = $classmain;
            $amisgs = array_diff($neawmigs, $applied);
            // require_once App::$ROOT_DIR.'/migrations/'.$file;
            $className = new m0001_ini();
            $className->up();
            $pass = new  m0001_add_pass_column();
            $pass->up();
            $pass = new  m0001_post();
            $pass->up();
            $pass = new  m0001_chat();
            $pass->up();

            $appmigs[] = $amisgs;
        }
        if (!empty($amisgs)) {
            echo 'APPLING MIGRATIONS ';

            return $this->save($amisgs);
        } else {
            echo 'ALL MIGRATIONS ARE APPLIED';
        }
    }

    public function createtable()
    {
        $this->pdo->exec('
         CREATE TABLE IF NOT EXISTS migrations
        (
            id INT(11) unsigned NOT NULL AUTO_INCREMENT,
            migration VARCHAR(25),
            PRIMARY KEY (id),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
        ENGINE=INNODB;
        ');
    }

    public function appliedMigration()
    {
        $stmt = self::prepare('SELECT migration FROM migrations');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function save($neewmigs)
    {
        $str = implode(',', array_map(fn ($n) => "('$n')", $neewmigs));
        $sql = "INSERT  INTO
                migrations(migration)
                VALUES $str";

        $stmt = self::prepare($sql);

        return $stmt->execute();
    }

    public static function prepare($sql)
    {
        return App::$app->db->pdo->prepare($sql);
    }
}
