<?php
namespace statera\core\form;
use statera\core\Model;

abstract class BaseField {
    public Model $clsModel;
    public string $sAttribute;
    public $mValue;
    public string $sClasses = '';
    public string $sAttrs = '';
    public string $sType = '';
    public const TYPE_HIDDEN = 'hidden';

    public function __construct(Model $clsModel, string $sAttribute, array $aParams) {
        $this->clsModel = $clsModel;
        $this->sAttribute = $sAttribute;
        $this->mValue = $this->clsModel->{$this->sAttribute};
        if (!empty($aParams['aAttributes'])) {
            $this->sAttrs = implode(' ', $aParams['aAttributes']);
        }
        if (!empty($aParams['aClasses'])) {
            $this->sClasses = implode(' ', $aParams['aClasses']);
        }
    }

    abstract public function renderInput(): string;

    public function __toString() {
        return sprintf('
            <div class="form-group mb-3 %s">
                <label class="form-label">%s</label>
                %s
                <div class="invalid-feedback">%s</div>
            </div>
        '
            , ($this->sType == self::TYPE_HIDDEN) ? 'd-none' : ''
            , $this->clsModel->getLabel($this->sAttribute)
            , $this->renderInput()
            , $this->clsModel->getFirstError($this->sAttribute)
         
        );

    }
}