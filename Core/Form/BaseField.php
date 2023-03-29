<?php
namespace App\Core\Form;

use App\Core\Model;

abstract class BaseField
{
    public Model $model;

    public string $attribute;

    function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderField();
    
    function __toString()
    {
        return sprintf('
                <div class="form-group my-2">
                    <label for="email">%s</label>
                    %s
                    %s
                </div>
            ',
            $this->label(),
            $this->renderField(),
            $this->showErrors()
        );
    }

    public function label() 
    {
        return $this->model->labels($this->attribute);
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
}
