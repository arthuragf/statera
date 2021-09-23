<?php
namespace statera\core;
class Request {

    public $aBase64Fields = [];

    public function getPath() {
        $sPath = $_SERVER['REQUEST_URI'] ?? '/';
        $nPosition = strpos($sPath, '?');

        if ($nPosition === false) {
            return $sPath;
        }

        return substr($sPath, 0, $nPosition - 1);
    }

    public function getMethod() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet() {
        return $this->getMethod() === 'get';
    }

    public function isPost() {
        return $this->getMethod() === 'post';
    }

    public function getBody() {
        $aBody = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $aBody[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                if (in_array($key, $this->aBase64Fields)) {
                    $aBody[$key] = base64_decode($aBody[$key]);
                }
            }
        }

        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $aBody[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $aBody;
    }
}