<?php

require_once ("model/Projeto.php");
require_once ("model/Equipe.php");

class ProjetoController extends ControllerAbstract
{

    public function index(){
        $projetos = Projeto::findAll();
        $view = new View("views/projeto/index.phtml", ["projetos" => $projetos]);
        $view->showContents();
    }

    public function novo(){
        $equipes = Equipe::findAll();
        $view = new View("views/projeto/novo.phtml", ["equipes" => $equipes]);
        $view->showContents();
    }
}