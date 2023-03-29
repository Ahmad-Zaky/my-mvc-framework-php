<?php

namespace App\models;

use App\Core\Application;
use App\Core\DBModel as Model;

class Auth extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    public string $email = "";
    public string $password = "";

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
            "email",
            "password",
        ];
    }

    public function login() 
    {
        $message = "Email or Password is incorrect";
        if (! $user = User::findOne(["email" => $this->email])) {
            $this->addErrorMessage("email", $message);
            return false;
        }

        if (! password_verify($this->password, $user->password)) {
            $this->addErrorMessage("email", $message);
            return false;
        }

        return Application::$app->login($user);
    }

    public function logout()
    {
        return Application::$app->logout();
    }

    public function rules(): array
    {
        return [
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
            "password" => [self::RULE_REQUIRED],
        ];
    }

    public function labels(string $attribute = NULL): array|string
    {
        $labels = [];

        return ($attribute) 
            ? ($labels[$attribute] ?? ucfirst(str_replace("_", " ", $attribute)))
            : $labels;
    }

}