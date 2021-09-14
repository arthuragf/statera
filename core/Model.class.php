<?php
namespace statera\core;

abstract class Model {

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const POST_ACTION_INSERT = 'insert';
    public const POST_ACTION_EDIT = 'edit';
    public array $aErrors = [];
    public string $sPostAction = self::POST_ACTION_INSERT;

    public function loadData($aData) {
        foreach ($aData as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;
    
    public function editRules():array {
        return array_merge($this->rules(), []);
    }

    public function labels():array {
        return [];
    }

    public function getLabel($sAttribute) {
        return $this->labels()[$sAttribute] ?? $sAttribute;
    }

    public function selectOptions():array {
        return [];
    }

    public function getSelectOptions($sAttribute):array {
        return $this->selectOptions()[$sAttribute] ?? [];
    }

    public function validate() {
        if ($this->sPostAction === self::POST_ACTION_INSERT)
            $aPostRules = $this->rules();
        else
            $aPostRules = $this->editRules();

        foreach ($aPostRules as $sAttribute => $aRules) {
            $sValue = $this->{$sAttribute};
            foreach ($aRules as $rule) {
                $sRuleName = $rule;
                $bAddError = true;

                if (!is_string($sRuleName)) {
                    $sRuleName = $rule[0];
                }
            
                if ($sRuleName === self::RULE_REQUIRED && empty($sValue)) {
                    if (!empty($rule['require_activation']) && $this->{$rule['field']} !== $rule['value']) {
                        $bAddError = false;
                    }
                    if($bAddError)
                        $this->addErrorForRule($sAttribute, self::RULE_REQUIRED);
                }

                if ($sRuleName === self::RULE_EMAIL && !filter_var($sValue, FILTER_VALIDATE_EMAIL)) {
                    if (!empty($rule['require_activation']) && $this->{$rule['field']} != $rule['value']) {
                        $bAddError = false;
                    }
                    if($bAddError)
                        $this->addErrorForRule($sAttribute, self::RULE_EMAIL);
                }

                if ($sRuleName === self::RULE_MIN && strlen($sValue) < $rule['min']) {
                    if (!empty($rule['require_activation']) && $this->{$rule['field']} != $rule['value']) {
                        $bAddError = false;
                    }
                    if($bAddError)
                        $this->addErrorForRule($sAttribute, self::RULE_MIN, $rule);
                }

                if ($sRuleName === self::RULE_MAX && strlen($sValue) > $rule['max']) {
                    if (!empty($rule['require_activation']) && $this->{$rule['field']} != $rule['value']) {
                        $bAddError = false;
                    }
                    if($bAddError)
                        $this->addErrorForRule($sAttribute, self::RULE_MAX, $rule);
                }

                if ($sRuleName === self::RULE_MATCH && $sValue !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    if (!empty($rule['require_activation']) && $this->{$rule['field']} != $rule['value']) {
                        $bAddError = false;
                    }
                    if($bAddError)
                        $this->addErrorForRule($sAttribute, self::RULE_MATCH, $rule);
                }

                if ($sRuleName === self::RULE_UNIQUE) {
                    $oClass = $rule['oClass'];
                    $sUniqueAttribute = $rule['sAttribute'] ?? $sAttribute;
                    $sTableName = $oClass->getTableName();
                    $oSql = Application::$clsApp->clsDb->prepare('SELECT * FROM ' 
                        . $sTableName 
                        . ' WHERE '
                        . $sUniqueAttribute . ' = :attr' 
                        . ' AND ' . $oClass->primaryKey() . ' != :id '
                    );
                    $oSql->bindValue(':attr', $sValue);
                    $oSql->bindValue(':id', Application::$clsApp->clsSession->get('user'));
                    $oSql->execute();
                    $oRecord = $oSql->fetchObject();
                    
                    if ($oRecord) {
                        $this->addErrorForRule($sAttribute, self::RULE_UNIQUE, ['field' => $this->getLabel($sAttribute)]);
                    }
                }
            }
        }
        return empty($this->aErrors);
    }

    private function addErrorForRule(string $sAttribute, string $sRule, $aParams = []) {
        $sMessage = $this->errorMessages()[$sRule] ?? '';
        foreach ($aParams as $key => $value) {
            $sMessage = str_replace("{{$key}}", $value, $sMessage);
        }
        $this->aErrors[$sAttribute][] = $sMessage;
    }

    public function addError(string $sAttribute, string $sMessage) {
        $this->aErrors[$sAttribute][] = $sMessage;
    }

    public function errorMessages() {
        return [
            self::RULE_REQUIRED => 'This field is required'
            , self::RULE_EMAIL => 'This field must be valid email adress'
            , self::RULE_MIN => 'Min Length of this field must be {min}'
            , self::RULE_MAX => 'Max Length of this field must be {max}'
            , self::RULE_MATCH => 'This field must be the same as {match}'
            , self::RULE_UNIQUE => 'Record with this {field} already exists'
        ];
    }

    public function hasError($sAttribute) {
        return $this->aErrors[$sAttribute] ?? false;
    }

    public function getFirstError($sAttribute) {
        return $this->aErrors[$sAttribute][0] ?? false;
    }
}