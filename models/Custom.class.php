<?php
namespace statera\models;

use statera\core\Model;

class Custom extends Model{
    public array $aLabels = [];

    public function __construct($aParams, $aLabels) {
        foreach ($aParams as $sParam => $mVal) {
            $this->{$sParam} = $mVal;
        }
        $this->aLabels = $aLabels;
    }

    public function rules(): array {
        return [];
    }
    public function labels(): array {
        return $this->aLabels;
    }
}