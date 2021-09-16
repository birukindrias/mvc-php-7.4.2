<?php

namespace App\core\db;

use App\core\App;
use App\core\Model;

abstract class dbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function primaryKey(): string;

    public function save()
    {
        $tableName = $this->tableName();

        $attrs = $this->attrs();
        $attributes = array_map(fn ($n) => ":$n", $attrs);
        $stmt = self::prepare("
                                INSERT INTO
                                $tableName (".implode(',', $attrs).')
                                VALUES('.implode(',', $attributes).')
        ');
        foreach ($attrs as $attribute) {
            $stmt->bindValue(":$attribute", $this->{$attribute});
        }

        return $stmt->execute();
    }

    public function findOne($get)
    {
        $tableName = static::tableName();
        $wher = array_keys($get);
        $param = implode(' AND ', array_map(fn ($n) => "$n = :$n", $wher));
        $stmt = self::prepare("SELECT * FROM
                              $tableName WHERE 
                              $param
        ");
        foreach ($get as $key => $item) {
            $stmt->bindValue(":$key", $item);
        }
        $stmt->execute();

        return $stmt->fetchObject(static::class);
    }

    public function findAll()
    {
        $tableName = static::tableName();
        $stmt = self::prepare("SELECT * FROM
                              $tableName 
        ");

        $stmt->execute();

        return $stmt->fetchAll(static::class);
    }

    public static function prepare($sql)
    {
        return App::$app->db->pdo->prepare($sql);
    }
}
