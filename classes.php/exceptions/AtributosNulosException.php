<?php

class AtributosNulosException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(utf8_decode($message), $code, $previous);
    }

}