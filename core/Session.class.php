<?php

namespace statera\core;

class Session {
    protected const FLASH_KEY = 'flash_messages';

    public function __construct() {
        session_start();
        $aFlashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($aFlashMessages as $sKey => &$aFlashMessage) {
            $aFlashMessage['bRemove'] = true;
        }
        
        $_SESSION[self::FLASH_KEY] = $aFlashMessages;
        
    }

    public function setFlash($sKey, $sMessage) {
        $_SESSION[self::FLASH_KEY][$sKey] = [
            'sValue' => $sMessage,
            'bRemove' => false
        ];
    }

    public function getFlash($sKey) {
        return $_SESSION[self::FLASH_KEY][$sKey]['sValue'] ?? '';
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return $_SESSION[$key] ?? false;
    }

    public function unset($key) {
        unset($_SESSION[$key]);
    }

    public function __destruct() {
        $aFlashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($aFlashMessages as $sKey => &$aFlashMessage) {
            if ($aFlashMessage['bRemove']) {
                unset($aFlashMessages[$sKey]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $aFlashMessages;
    }
}