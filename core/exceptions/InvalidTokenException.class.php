<?php

namespace statera\core\exceptions;

class InvalidTokenException extends \Exception {
    protected $message = 'You have entered an invalid token.';
    protected $code = 406;
}