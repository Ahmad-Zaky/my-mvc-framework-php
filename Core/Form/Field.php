<?php

namespace App\Core\Form;

use App\Core\Model;

class Field
{
    public Model $model;

    public string $attribute;

    function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }
}