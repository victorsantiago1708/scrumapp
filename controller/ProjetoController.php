<?php

require_once ("model/Projeto.php");

class ProjetoController extends ControllerAbstract
{

    public function index(){
        $projetos = Projeto::findAll();
        $view = new View("views/projeto/index.phtml", ["projetos" => $projetos]);
        $view->showContents();
    }
}