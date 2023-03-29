<?php

namespace App\models;

use App\Core\DBModel as Model;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    public ?int $id;
    public string $first_name = "";
    public string $last_name = "";
    public string $email = "";
    public int $status = self::STATUS_INACTIVE;
    public string $password = "";
    public string $password_confirmation = "";

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public function tableName(): string 
    {
        return $this->table;
    }
    
    public function primaryKey(): string 
    {
        return $this->primaryKey;
    }
    
    public function attributes(): array
    {
        return [
            "first_name",
            "last_name",
            "email",
            "status",
            "password",
        ];
    }

    public function name()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function save() 
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        return parent::save();
    }

    public function rules(): array
    {
        return [
            "first_name" => [self::RULE_REQUIRED],
            "last_name" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, "class" => self::class]],
            "password" => [self::RULE_REQUIRED, [self::RULE_MIN, "min" => 6]],
            "password_confirmation" => [self::RULE_REQUIRED, [self::RULE_MATCH, "match" => "password"]],
        ];
    }

    public function labels(string $attribute = NULL): array|string
    {
        $labels = [
            "password_confirmation" => "Confirm password",
        ];

        return ($attribute) 
            ? ($labels[$attribute] ?? ucfirst(str_replace("_", " ", $attribute)))
            : $labels;
    }

}