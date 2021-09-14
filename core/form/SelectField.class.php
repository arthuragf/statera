<?php
namespace statera\core\form;
use statera\core\Model;

class SelectField extends BaseField{

    public function __construct(Model $clsModel, string $sAttribute, array $aParams = []) {
        parent::__construct($clsModel, $sAttribute, $aParams);
    }

    public function renderSelectOptions() {
        $sRet = '';
        foreach ($this->clsModel->getSelectOptions($this->sAttribute) as $mVal => $sDisplay) {
            $sRet .= sprintf('<option value="%s" %s>%s</option>'
                , $mVal
                , ($this->mValue == $mVal) ? 'selected' : ''
                , $sDisplay
            );
        }
        return $sRet;
    }
    public function renderInput(): string {
        return sprintf(
            '<select name="%s" class="form-control %s %s" %s>%s</select>'
            , $this->sAttribute
            , $this->clsModel->hasError($this->sAttribute) ? ' is-invalid' : ''
            , $this->sClasses
            , $this->sAttrs
            , $this->renderSelectOptions()
        );
    }
}