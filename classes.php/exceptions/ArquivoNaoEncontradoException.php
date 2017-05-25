<?php

class ArquivoNaoEncontradoException extends Exception
{
    public function __construct($message = "Arquivo não encontrado",$code = 0, Throwable $previous = null)
    {
        parent::__construct(utf8_decode($message), $code, $previous);
    }
}