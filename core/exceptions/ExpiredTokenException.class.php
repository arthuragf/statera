<?php

namespace statera\core\exceptions;

class ExpiredTokenException extends \Exception {
    protected $message = 'This token has expired, please generate a new one';
    protected $code = 406;
}