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

    public function save(){
        $errors = array();

        if(HttpRequest::$params["nome"]!="")
            $nome = HttpRequest::$params["nome"];
        else
            array_push($errors, "Campo Nome está vazio!");

        if(HttpRequest::$params["descricao"]!="")
            $descricao = HttpRequest::$params["descricao"];
        else
            array_push($errors, "Campo Descrição está vazio!");

        if(isset(HttpRequest::$params["equipe"]) && HttpRequest::$params["equipe"]!="")
            $equipe = HttpRequest::$params["equipe"];
        else
            array_push($errors, "Selecione uma equipe que trabalhará no projeto");

        if(HttpRequest::$params["dataInicial"]!="")
            $dataInicial = HttpRequest::$params["dataInicial"];
        else
            array_push($errors, "Defina uma data de início para o projeto");

        if(HttpRequest::$params["dataTermino"]!="")
            $dataTermino = HttpRequest::$params["dataTermino"];
        else
            array_push($errors, "Defina uma data de término do projeto");

        $equipes = Equipe::findAll();
        $view = new View("views/projeto/novo.phtml", ["equipes" => $equipes]);

        if(count($errors) > 0){
            $view->setErrors($errors);
            $view->showContents();
        }else{
            $projeto = new Projeto();
            $projeto->setNome($nome);
            $projeto->setDescricao($descricao);
            $projeto->setEquipe(Equipe::get($equipe));
            $projeto->setDataInicio($dataInicial);
            $projeto->setDataTermino($dataTermino);
            if($projeto->save()){
                $view->setMsgsSucesso(["Projeto criado com sucesso"]);
                $view->showContents();

            }

        }



    }
}