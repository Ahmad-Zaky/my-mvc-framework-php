<?php

namespace App\Core\Form;

use App\Core\Model;

class TextareaField extends BaseField
{
    function __construct(Model $model, string $attribute)
    {
        parent::__construct($model, $attribute);
    }

    public function renderField() 
    {
        return sprintf('<textarea class="form-control %s" name="%s" id="%s">%s</textarea>', [
            $this->model->hasErrors($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute} ?? "",
            $this->attribute,
            $this->attribute,
        ]);
    }
}