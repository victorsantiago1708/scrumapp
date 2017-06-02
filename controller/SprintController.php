<?php

require_once ("model/Sprint.php");
require_once ("model/Usuario.php");

class SprintController extends ControllerAbstract
{
    public function delete()
    {
        parent::flashClear();
        if(isset(HttpRequest::$params["id"]) && HttpRequest::$params["id"]!=""){
            $sprint = Sprint::get(HttpRequest::$params["id"]);
            if($sprint->delete()){
                echo "true";
            }else{
                echo "Não foi possível excluir esse registro!";
                return false;
            }
        }
    }

    public function save(){
        parent::flashClear();
        $errors = array();
        $membros = array();

        if(isset(HttpRequest::$params['id']) && HttpRequest::$params['id']!=""){
            $sprint = Sprint::get(HttpRequest::$params['id']);
        }else{
            $sprint = new Sprint();
        }

        if(isset(HttpRequest::$params["responsavel"]) && count(HttpRequest::$params["responsavel"]) > 0){
            foreach (HttpRequest::$params["responsavel"] as $membro):
                $membroObj = Usuario::get($membro);
                array_push($membros, $membroObj);
            endforeach;
            $sprint->setResponsaveis($membros);
        }else{
            array_push($errors, "Selecione os membros do time!");
        }


        if(isset(HttpRequest::$params['nome']) && HttpRequest::$params['nome']!=""){
            $sprint->setNome(HttpRequest::$params['nome']);
        }else{
            array_push($errors, "Campo Nome está vazio!");
        }

        if(isset(HttpRequest::$params['descricao']) && HttpRequest::$params['descricao']!=""){
            $sprint->setDescricao(HttpRequest::$params['descricao']);
        }else{
            array_push($errors, "Campo Descrição está vazio!");
        }

        if(isset(HttpRequest::$params['projetoId']) && HttpRequest::$params['projetoId']!=""){
            $sprint->setProjeto_id(HttpRequest::$params['projetoId']);
        }else{
            array_push($errors, "Referência do projeto não encontrada, favor atualiza a página!");
        }

        if($sprint->save()){
            echo json_encode(["result" => true, "mensagem" => "Sprint salva com sucesso!"]);
        }else{
            echo json_encode(["result" => false, "mensagem" => "Não foi possível salvar a Sprint!"]);
        }

    }

    public function edit(){

    }

    public function novo(){

    }

    public function atualizaStatusSprint(){
        if(isset(HttpRequest::$params['id']) && HttpRequest::$params['id']!=""){
            $sprint = Sprint::get(HttpRequest::$params['id']);
            $sprint->setStatus(HttpRequest::$params['status']);
            $sprint->setResponsaveis($sprint->getResponsaveis());

            if($sprint->save()){
                echo json_encode(["result" => true]);
            }else{
                echo json_encode(["result" => false]);
            }
        }

    }
}