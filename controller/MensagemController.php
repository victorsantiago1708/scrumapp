<?php

class MensagemController extends ControllerAbstract
{
    public function index(){
        parent::flashClear();
        $view = new View("views/mensagem/index.phtml", array());
        $view->showContents();
    }
}