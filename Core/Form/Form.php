<?php

namespace App\Core\Form;

use App\Core\Model;

class Form
{
    
    public static function begin($action = "", $method = "GET")
    {
        echo "<form action=\"{$action}\" method=\"{$method}\">";

        return new self;
    }

    public static function end()
    {
        echo "</form>";
    }

    public function field(Model $model, $attribute) 
    {
        return new Field($model, $attribute);
    }
}