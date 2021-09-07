<?php
namespace statera\core\form;
use statera\core\Model;

class TextareaField extends BaseField{
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
            '<textarea name="%s" class="form-control %s">%s</textarea>'
            , $this->sAttribute
            , $this->clsModel->hasError($this->sAttribute) ? ' is-invalid' : ''
            , $this->clsModel->{$this->sAttribute}
        );
    }
}