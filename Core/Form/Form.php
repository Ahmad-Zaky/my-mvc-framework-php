<?php

namespace App\Core\Form;

use App\Core\Model;

class Form
{
    protected static ?Model $model;

    public static function begin($action = "", $method = "GET", Model $model = null)
    {
        self::$model = $model;

        echo "<form action=\"{$action}\" method=\"{$method}\">";

        return new self;
    }

    public static function end()
    {
        echo "</form>";
    }

    public function field($attribute, $type = "text")
    {
        return new Field(self::$model, $attribute, $type);
    }
}