<?php
namespace statera\core\form;
use statera\core\Model;

class InputField extends BaseField{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public string $sType;

    public function __construct(Model $clsModel, string $sAttribute) {
        parent::__construct($clsModel, $sAttribute);
        $this->sType = self::TYPE_TEXT;
    }

    public function passwordField() {
        $this->sType = self::TYPE_PASSWORD;
        return $this;
    }

    public function renderInput(): string {
        return sprintf(
            '<input type="%s" name="%s" value="%s" class="form-control %s">'
            , $this->sType
            , $this->sAttribute
            , ($this->sType != self::TYPE_PASSWORD) 
                ? $this->clsModel->{$this->sAttribute} 
                : ''
            , $this->clsModel->hasError($this->sAttribute) ? ' is-invalid' : ''
        );
    }
}