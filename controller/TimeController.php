<?php

require_once ("model/Equipe.php");
require_once ("model/Usuario.php");
require_once ("model/Projeto.php");

class TimeController extends ControllerAbstract
{
    public function index(){
        parent::flashClear();
        $times = Equipe::findAll();
        $view = new View("views/time/index.phtml", ["times" => $times]);
        $view->showContents();
    }

    public function novo(){
        parent::flashClear();
        $usuarios = Usuario::findAll();
        $view = new View("views/time/novo.phtml", ["usuarios" => $usuarios]);
        $view->showContents();
    }

    public function save(){
        parent::flashClear();
        $errors = array();
        $membros = array();

        if(isset(HttpRequest::$params["id"]) && HttpRequest::$params["id"]!=""){
            $time = Equipe::get(HttpRequest::$params["id"]);
            View::$msgsSucesso = ["Time atualizado com sucesso"];
        }else{
            $time = new Equipe();
            View::$msgsSucesso = ["Time criado com sucesso"];
        }

        if(isset(HttpRequest::$params["membros"]) && count(HttpRequest::$params["membros"]) > 0){
            foreach (HttpRequest::$params["membros"] as $membro):
                $membroObj = Usuario::get($membro);
                array_push($membros, $membroObj);
            endforeach;
            $time->setMembros($membros);
        }else{
            array_push($errors, "Selecione os membros do time!");
        }


        if(HttpRequest::$params["nome"]!="")
            $time->setNome(HttpRequest::$params["nome"]);
        else
            array_push($errors, "Campo Nome está vazio!");

        if(HttpRequest::$params["categoria"]!="")
            $time->setCategoria(HttpRequest::$params["categoria"]);
        else
            array_push($errors, "Campo Categoria está vazio!");



        $usuarios = Usuario::findAll();
        $view = new View("views/time/novo.phtml", ["usuarios" => $usuarios, "equipe" => $time]);

        if(count($errors) > 0){
            View::$errors = $errors;
        }else{
            if(!$time->save()) {
                if($time->getId() != null){
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
        $equipe = Equipe::get($id);
        $view = new View("views/time/novo.phtml", ["usuarios" => $usuarios, "equipe" => $equipe]);
        $view->showContents();
    }

    public function delete()
    {
        parent::flashClear();
        if(isset(HttpRequest::$params["id"]) && HttpRequest::$params["id"]!=""){
            $time = Equipe::get(HttpRequest::$params["id"]);
            $projeto = Projeto::getByEquipe($time);

            if($projeto != null){
                echo "Não foi possível atualizar o registro, pois está vinculado a um projeto em andamento!";
                return false;
            }

            if($time->delete()){
                echo "true";
            }else{
                echo "false";
                return false;
            }
        }
    }
}