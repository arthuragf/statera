<?php
namespace statera\core\form;
use statera\core\Model;

class TextareaField extends BaseField{

    public function __construct(Model $clsModel, string $sAttribute, array $aParams = []) {
        parent::__construct($clsModel, $sAttribute, $aParams);
    }

    public function renderInput(): string {
        return sprintf(
            '<textarea name="%s" class="form-control %s %s" %s>%s</textarea>'
            , $this->sAttribute
            , $this->clsModel->hasError($this->sAttribute) ? ' is-invalid' : ''
            , $this->sClasses
            , $this->sAttrs
            , $this->mValue
        );
    }
}