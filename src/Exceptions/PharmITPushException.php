<?php

namespace PharmIT\Push\Exceptions;

class PharmITPushException extends \Exception
{
    public function __construct($message, $code, Exception $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}
