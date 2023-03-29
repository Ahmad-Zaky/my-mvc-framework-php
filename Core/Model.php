<?php

namespace App\Core;

abstract class Model
{
    const RULE_REQUIRED = "required";
    const RULE_EMAIL = "email";
    const RULE_MIN = "min";
    const RULE_MAX = "max";
    const RULE_MATCH = "match";
    const RULE_UNIQUE = "unique";
    
    public array $errors = [];

    public function fill($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validate() 
    {
        foreach ($this->rules() as  $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = (is_array($rule)) ? $rule[0] : $rule;

                // Required
                if ($ruleName === self::RULE_REQUIRED && ! $value) {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }

                // Email
                if ($ruleName === self::RULE_EMAIL && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }

                // Min
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule["min"]) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }

                // Max
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule["max"]) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }

                // Match
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule["match"]}) {
                    $rule["match"] = $this->labels($rule["match"]);
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }

                // Unique
                if ($ruleName === self::RULE_UNIQUE) {
                    $class = $rule["class"];
                    $table = ($class)::tableName();
                    
                    $statement = self::prepare("SELECT * FROM {$table} WHERE {$attribute} = :{$attribute}");
                    $statement->bindValue(":{$attribute}", $value);
                    $statement->execute();
                    if ($statement->fetchObject()) {
                        $this->addError($attribute, self::RULE_UNIQUE, ["field" => $this->labels($attribute)]);
                    }

                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';

        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    public function addErrorMessage(string $attribute, string $message, $params = [])
    {
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    public function errorMessages() 
    {
        return [
            self::RULE_REQUIRED => "This field is required",
            self::RULE_EMAIL => "This field must be a valid email",
            self::RULE_MIN => "Min length must be at least {min}",
            self::RULE_MAX => "Max length must be at most {max}",
            self::RULE_MATCH => "This field must be the same as {match}",
            self::RULE_UNIQUE => "{field} already exists",
        ];
    }

    public function hasErrors($attribute) 
    {
        return $this->errors[$attribute] ?? false;
    }
    
    public function firstError($attribute) 
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public static function prepare($sql)
    {
        return Application::$app->db->connection()->prepare($sql);
    }

    abstract public function rules(): array;
    
    abstract public function labels(string $key = NULL): array|string;
}