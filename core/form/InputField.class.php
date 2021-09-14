<?php
namespace statera\core\form;
use statera\core\Model;

class InputField extends BaseField{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_CHECKBOX = 'checkbox';

    public string $sType = self::TYPE_TEXT;

    public function __construct(Model $clsModel, string $sAttribute, array $aParams = []) {
        parent::__construct($clsModel, $sAttribute, $aParams);
    }

    public function passwordField() {
        $this->sType = self::TYPE_PASSWORD;
        return $this;
    }

    public function checkboxField() {
        $this->sType = self::TYPE_CHECKBOX;
        return $this;
    }

    public function hiddenField() {
        $this->sType = self::TYPE_HIDDEN;
        return $this;
    }

    public function renderInput(): string {
        return sprintf(
            '<input type="%s" name="%s" value="%s" class="%s %s %s" %s %s>'
            , $this->sType
            , $this->sAttribute
            , $this->mValue = ($this->sType != self::TYPE_PASSWORD) 
                ? $this->mValue
                : ''
            , ($this->sType == self::TYPE_CHECKBOX) 
                ? 'form-check-input' 
                : 'form-control'
            , $this->clsModel->hasError($this->sAttribute) ? ' is-invalid' : ''
            , $this->sClasses
            , $this->sAttrs
            , ($this->sType === self::TYPE_CHECKBOX && $this->clsModel->{$this->sAttribute} === 1) 
                ? 'checked' 
                : ''
        );
    }
}