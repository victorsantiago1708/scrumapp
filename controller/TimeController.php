<?php

require_once ("model/Equipe.php");
require_once ("model/Usuario.php");

class TimeController extends ControllerAbstract
{
    public function index(){
        $times = Equipe::findAll();
        $view = new View("views/time/index.phtml", ["times" => $times]);
        $view->showContents();
    }

    public function novo(){
        $usuarios = Usuario::findAll();
        $view = new View("views/time/novo.phtml", ["usuarios" => $usuarios]);
        $view->showContents();
    }

    public function save(){
        $errors = array();
        $membros = array();

        if(isset(HttpRequest::$params["membros"]) && count(HttpRequest::$params["membros"]) > 0){
            foreach (HttpRequest::$params["membros"] as $membro):
                $membroObj = Usuario::get($membro);
                array_push($membros, $membroObj);
            endforeach;
        }else{
            array_push($errors, "Selecione os membros do time!");
        }

        if(HttpRequest::$params["nome"]!="")
            $nome = HttpRequest::$params["nome"];
        else
            array_push($errors, "Campo Nome está vazio!");

        if(HttpRequest::$params["categoria"]!="")
            $categoria = HttpRequest::$params["categoria"];
        else
            array_push($errors, "Campo Categoria está vazio!");

        $usuarios = Usuario::findAll();
        $view = new View("views/time/novo.phtml", ["usuarios" => $usuarios]);

        if(count($errors) > 0){
            View::$errors = $errors;
            $view->showContents();
        }else{
            $time = new Equipe();
            $time->setNome($nome);
            $time->setCategoria($categoria);
            $time->setMembros($membros);
            if($time->save()){
                View::$msgsSucesso = ["Time criado com sucesso"];
                $view->showContents();
            }

        }

    }

    public function edit(){
        $usuarios = Usuario::findAll();
        $id = HttpRequest::$params['id'];
        $equipe = Equipe::get($id);
        $view = new View("views/time/novo.phtml", ["usuarios" => $usuarios, "equipe" => $equipe]);
        $view->showContents();
    }
}