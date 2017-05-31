<?php

require_once ("model/Equipe.php");

class TimeController extends ControllerAbstract
{
    public function index(){
        $times = Equipe::findAll();
        $view = new View("views/time/index.phtml", ["times" => $times]);
        $view->showContents();
    }
}