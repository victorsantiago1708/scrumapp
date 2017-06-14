<?php

class Projeto extends Model
{
    //@Type:Long
    private $id = null;

    //@Type:String
    private $nome = "";

    //@Type:String
    private $descricao = "";

    //@HasMany:Sprint
    private $sprints = Array();

    //@BelongsTo:Equipe
    private $equipe = null;

    //@Type:String
    private $status = "ANDAMENTO";

    //@Type:Date
    private $dataInicio = null;

    //@Type:Date
    private $dataTermino = null;

    public function __construct( )
    {
        //
    }

    public static function get($id){
        return Model::getModel($id, "Projeto");
    }

    public function getSprintsDisponiveis(){
        $sql = "Select * from sprint where status = 'DISPONIVEL' and projeto_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return Model::create($rows, "Sprint");
    }

    public function getSprintsAndamento(){
        $sql = "Select * from sprint where status = 'ANDAMENTO' and projeto_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return Model::create($rows, "Sprint");
    }

    public function getSprintsConcluidas(){
        $sql = "Select * from sprint where status = 'CONCLUIDA' and projeto_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return Model::create($rows, "Sprint");
    }

    public static function getByEquipe($equipe){
        $sql = "Select * from projeto where equipe = {$equipe->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        $projetos = Model::create($rows, "Projeto");
        return isset($projetos[0]) ? $projetos[0] : null;
    }

    public static function findAll(){
        return Model::findAllModel("Projeto");
    }

    public function addSprint( $sprint ){
        array_push($this->sprints, $sprint);
    }

    public function removeSprint( $sprint ){
        foreach (array_keys($this->sprints, $sprint) as $key) {
            unset($this->sprints[$key]);
        }
    }

    public function save( ){
        $sql = "";

        if($this->nome == "" || $this->descricao == "" || $this->equipe == null || $this->status == "" || $this->dataInicio == null || $this->dataTermino == null){
            return false;
        }

        try{
            Datasource::getInstance()->beginTransaction();

            $equipe = Equipe::get($this->equipe);

            if($this->id == null){
                $sql = "Insert into projeto (nome, descricao, status, equipe, dataInicio, dataTermino) 
                      values ('{$this->nome}', '{$this->descricao}', '{$this->status}', {$equipe->getId()}, '{$this->dataInicio}', '{$this->dataTermino}')";
            }else{
                $sql = "Update projeto set nome = '{$this->nome}', descricao = '{$this->descricao}', status = '{$this->status}', equipe = {$equipe->getId()}, dataInicio = '{$this->dataInicio}',
                      dataTermino = '{$this->dataTermino}' where id = {$this->id}";
            }

            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();

            Datasource::getInstance()->commit();

            return true;
        }catch (Exception $e){
            Datasource::getInstance()->rollBack();
            return false;
        }

    }

    public function delete(){
        try{
            Datasource::getInstance()->beginTransaction();

            if(count($this->getSprints()) > 0){
                foreach ($this->getSprints() as $key => $val){
                    $val->delete();
                }
            }

            $sql = "DELETE FROM projeto WHERE id = :id";
            $stmt = Datasource::getInstance()->prepare( $sql );
            $stmt->bindParam( ':id', $this->getId() );
            $result = $stmt->execute();

            Datasource::getInstance()->commit();
            return true;
        }catch (Exception $e){
            Datasource::getInstance()->rollBack();
            return false;
        }

    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getSprints()
    {
        $sql = "Select * from sprint where projeto_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return Model::create($rows, "Sprint");
    }

    public function setSprints($sprints)
    {
        $this->sprints = $sprints;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    public function getDataTermino()
    {
        return $this->dataTermino;
    }

    public function setDataTermino($dataTermino)
    {
        $this->dataTermino = $dataTermino;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getEquipe()
    {
        if($this->equipe!=null){
            return Equipe::get($this->equipe);
        }else{
            return null;
        }

    }

    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;
    }

}