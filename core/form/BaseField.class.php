<?php
namespace statera\core\form;
use statera\core\Model;

abstract class BaseField {
    public Model $clsModel;
    public string $sAttribute;

    public function __construct(Model $clsModel, string $sAttribute) {
        $this->clsModel = $clsModel;
        $this->sAttribute = $sAttribute;
    }

    abstract public function renderInput(): string;

    public function __toString() {
        return sprintf('
            <div class="form-group mb-3">
                <label class="form-label">%s</label>
                %s
                <div class="invalid-feedback">%s</div>
            </div>
        '
            , $this->clsModel->getLabel($this->sAttribute)
            , $this->renderInput()
            , $this->clsModel->getFirstError($this->sAttribute)
         
        );

    }
}