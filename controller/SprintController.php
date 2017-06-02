<?php


class SprintController extends ControllerAbstract
{
    public function delete(){

    }

    public function save(){
        parent::flashClear();
        $errors = array();

        if(isset(HttpRequest::$params['id']) && HttpRequest::$params['id']!=""){
            $sprint = Sprint::get(HttpRequest::$params['id']);
        }else{
            $sprint = new Sprint();
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
            $sprint->setProjetoId(HttpRequest::$params['projetoId']);
        }else{
            array_push($errors, "Referência do projeto não encontrada, favor atualiza a página!");
        }

        if($sprint->save()){
            echo json_encode(["result" => true, "mensagem" => "Sprint salva com sucesso!"]);
        }else{
            echo json_encode(["result" => true, "mensagem" => "Não foi possível salvar a Sprint!"]);
        }

    }

    public function edit(){

    }

    public function novo(){

    }
}