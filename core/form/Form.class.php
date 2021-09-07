<?php
namespace statera\core\form;
use statera\core\Model;

class Form {
    public static function begin($sAction, $sMethod) {
        echo sprintf('<form action="%s" method="%s">', $sAction, $sMethod);
        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public function InputField(Model $clsModel, $sAttribute) {
        return new InputField ($clsModel, $sAttribute);
    }

    public function TextareaField(Model $clsModel, $sAttribute) {
        return new TextareaField ($clsModel, $sAttribute);
    }
}