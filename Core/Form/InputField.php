<?php

namespace App\Core\Form;

use App\Core\Model;

class InputField extends BaseField
{

    public string $type;

    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';

    function __construct(Model $model, string $attribute)
    {
        parent::__construct($model, $attribute);

        $this->type = self::TYPE_TEXT;
    }

    public function email() 
    {
        $this->type = self::TYPE_EMAIL;
        
        return $this;
    }

    public function password()
    {
        $this->type = self::TYPE_PASSWORD;
        
        return $this;
    }

    public function renderField() 
    {
        return sprintf('<input type="%s" class="form-control %s" value="%s" name="%s" id="%s">', [
            $this->type,
            $this->model->hasErrors($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute} ?? "",
            $this->attribute,
            $this->attribute,
        ]);
    }
}