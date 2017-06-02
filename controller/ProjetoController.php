<?php

require_once ("model/Projeto.php");
require_once ("model/Equipe.php");
require_once ("model/Usuario.php");

class ProjetoController extends ControllerAbstract
{

    public function index(){
        parent::flashClear();
        $projetos = Projeto::findAll();
        $view = new View("views/projeto/index.phtml", ["projetos" => $projetos]);
        $view->showContents();
    }

    public function novo(){
        parent::flashClear();
        $equipes = Equipe::findAll();
        $view = new View("views/projeto/novo.phtml", ["equipes" => $equipes]);
        $view->showContents();
    }

    public function save(){
        parent::flashClear();
        $errors = array();

        if(isset(HttpRequest::$params["id"]) && HttpRequest::$params["id"]!=""){
            $projeto = Projeto::get(HttpRequest::$params["id"]);
            View::$msgsSucesso = ["Projeto atualizado com sucesso"];
        }else{
            $projeto = new Projeto();
            View::$msgsSucesso = ["Projeto criado com sucesso"];
        }

        if(HttpRequest::$params["nome"]!="")
            $projeto->setNome(HttpRequest::$params["nome"]);
        else
            array_push($errors, "Campo Nome está vazio!");

        if(isset(HttpRequest::$params["descricao"]) && HttpRequest::$params["descricao"]!="")
            $projeto->setDescricao(HttpRequest::$params["descricao"]);
        else
            array_push($errors, "Campo Descrição está vazio!");

        if(isset(HttpRequest::$params["equipe"]) && HttpRequest::$params["equipe"]!="")
            $projeto->setEquipe(HttpRequest::$params["equipe"]);
        else
            array_push($errors, "Selecione uma equipe que trabalhará no projeto");

        if(isset(HttpRequest::$params["dataInicial"]) && HttpRequest::$params["dataInicial"]!="")
            $projeto->setDataInicio(HttpRequest::$params["dataInicial"]);
        else
            array_push($errors, "Defina uma data de início para o projeto");

        if(HttpRequest::$params["dataTermino"]!="")
            $projeto->setDataTermino(HttpRequest::$params["dataTermino"]);
        else
            array_push($errors, "Defina uma data de término do projeto");

        $equipes = Equipe::findAll();
        $view = new View("views/projeto/novo.phtml", ["equipes" => $equipes, "projeto" => $projeto]);

        if(count($errors) > 0){
            View::$errors = $errors;
        }else{
            if(!$projeto->save()){
                if($projeto->getId() != null){
                    View::$errors = ["Não foi possível atualizar o registro"];
                }else{
                    View::$errors = ["Não foi possível criar o registro"];
                }
            }
        }
        $view->showContents();

    }

    public function edit(){
        parent::flashClear();
        $usuarios = Usuario::findAll();
        $id = HttpRequest::$params['id'];
        $projeto = Projeto::get($id);
        $equipes = Equipe::findAll();
        $view = new View("views/projeto/novo.phtml", ["projeto" => $projeto, "equipes" => $equipes]);
        $view->showContents();
    }

    public function delete()
    {
        parent::flashClear();
        if(isset(HttpRequest::$params["id"]) && HttpRequest::$params["id"]!=""){
            $projeto = Projeto::get(HttpRequest::$params["id"]);
            if($projeto->delete()){
                echo "true";
            }else{
                echo "Não foi possível excluir esse registro!";
                return false;
            }
        }
    }

    public function visualizar(){
        parent::flashClear();
        $id = HttpRequest::$params['id'];
        $projeto = Projeto::get($id);
        $view = new View("views/projeto/visualizar.phtml", ["projeto" => $projeto]);
        $view->showContents();
    }
}