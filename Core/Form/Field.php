<?php

namespace App\Core\Form;

use App\Core\Model;

class Field
{
    public Model $model;

    public string $attribute;

    public string $type;

    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';

    function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->type = self::TYPE_TEXT;
    }

    function __toString()
    {
        return sprintf('
                <div class="form-group my-2">
                    <label for="email">%s</label>
                    <input type="%s" class="form-control %s" value="%s" name="%s" id="%s">
                    %s
                </div>
            ',
            $this->label(),
            $this->type,
            $this->model->hasErrors($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute} ?? "",
            $this->attribute,
            $this->attribute,
            $this->showErrors()
        );
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

    protected function showErrors()
    {
        $errors = "";
        foreach ($this->model->errors[$this->attribute] ?? [] as $error) {
            $errors .= "<li>{$error}</li>";
        }

        return "
            <div class=\"invalid-feedback\">
                <ul> {$errors} </ul>
            </div>
        ";
    }

    public function label() 
    {
        return $this->model->labels($this->attribute);
    }
}