<?php

namespace App\Core;

abstract class DBModel extends Model
{
    abstract public function tableName(): string; 
    
    abstract public function primaryKey(): string;

    abstract public function attributes(): array; 

    public function save() 
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":{$attr}", $attributes);

        $statement = self::prepare(
            "INSERT INTO $tableName (". implode(', ', $attributes) .") VALUES (". implode(", ", $params) .")"
        );

        foreach ($attributes as $attribute) {
            $statement->bindValue(":{$attribute}", $this->{$attribute});
        }

        $statement->execute();

        return true;
    }

    public static function findOne($where)
    {
        $class = static::class;
        $table = (new $class)->tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $table WHERE $sql");

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();

        return $statement->fetchObject($class);
    }
}