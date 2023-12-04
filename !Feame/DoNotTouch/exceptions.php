<?php

class RequireError extends Exception
{
    public function __construct($path, $code = 0, Throwable $previous = null) {
        $message = "Required path: {$path} is invalid\n";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class ClassError extends Exception
{
    public function __construct($Class, $code = 0, Throwable $previous = null) {
        $message = "Requested Class: {$Class} does not exist.\n";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class VariableError extends Exception
{
    public function __construct($var, $code = 0, Throwable $previous = null) {
        $message = "Requested Variable: {$var} does not exist.\n";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}