<?php

class Projeto extends Model
{
    private $id = null;
    private $nome = "";
    private $descricao = "";
    private $sprints = Array();
    private $equipe = null;
    private $status = "ANDAMENTO";
    private $dataInicio = null;
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

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return array
     */
    public function getSprints()
    {
        $sql = "Select * from sprint where projeto_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return Model::create($rows, "Sprint");
    }

    /**
     * @param array $sprints
     */
    public function setSprints($sprints)
    {
        $this->sprints = $sprints;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param null $dataInicio
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @return null
     */
    public function getDataTermino()
    {
        return $this->dataTermino;
    }

    /**
     * @param null $dataTermino
     */
    public function setDataTermino($dataTermino)
    {
        $this->dataTermino = $dataTermino;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getEquipe()
    {
        if($this->equipe!=null){
            return Equipe::get($this->equipe);
        }else{
            return null;
        }

    }

    /**
     * @param null $equipe
     */
    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;
    }




}