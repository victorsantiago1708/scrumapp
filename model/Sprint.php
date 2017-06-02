<?php

class Sprint extends Model
{
    private $id = null;
    private $nome = "";
    private $descricao = "";
    private $status = "DISPONIVEL";
    private $projeto_id = null;
    private $responsaveis = array();

    public static function get($id){
        return Model::getModel($id, "Sprint");
    }

    public function save( ){
        $sql = "";

        if($this->nome == "" || $this->descricao == "" || $this->status == "" || $this->projeto_id == null ){
            return false;
        }

        try {
            Datasource::getInstance()->beginTransaction();

            if ($this->id == null) {
                $sql = "Insert into sprint (nome, descricao, status, projeto_id) 
                          values ('{$this->nome}', '{$this->descricao}', '{$this->status}', {$this->getProjeto_id()})";
            } else {
                $sql = "Update sprint set nome = '{$this->nome}', descricao = '{$this->descricao}', status = '{$this->status}', projeto_id = {$this->getProjeto_id()} where id = {$this->id}";
            }
            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();

            if($this->id != null){
                $this->saveResponsaveis($this);
            }else{
                $this->saveResponsaveis(self::getSprintRecenteAdd());
            }

            Datasource::getInstance()->commit();

            return true;
        }catch (Exception $e){
            Datasource::getInstance()->rollBack();
            return false;
        }

    }

    private static function getSprintRecenteAdd(){
        $sql = "Select * from sprint order by id desc limit 1";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );

        if(isset(self::create($rows, strtoupper("Sprint"))[0])){
            return self::create($rows, strtoupper("Sprint"))[0];
        }else{
            return null;
        }
    }

    public function saveResponsaveis($sprint = null){
        if($sprint != null){
            $sql = "delete from sprint_responsaveis where sprint_id = {$sprint->getId()}";
            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();

            foreach ($this->responsaveis as $responsavel):
                $sql = "Insert into sprint_responsaveis (sprint_id, usuario_id) values ({$sprint->getId()},{$responsavel->getId()})";
                $query = Datasource::getInstance()->prepare($sql);
                $query->execute();
            endforeach;

            return true;
        }else{
            return false;
        }
    }

    public function delete(){

        try{
            Datasource::getInstance()->beginTransaction();
            $sql = "DELETE FROM sprint WHERE id = :id";
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
    public function getProjeto_id()
    {
        return $this->projeto_id;
    }

    /**
     * @param null $projetoID
     */
    public function setProjeto_id($projetoID)
    {
        $this->projeto_id = $projetoID;
    }

    /**
     * @return array
     */
    public function getResponsaveis()
    {
        $sql = "Select * from sprint_responsaveis where sprint_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        $responsaveis = array();
        foreach ($rows as $row):
            $membro = Usuario::get($row['usuario_id']);
            array_push($responsaveis, $membro);
        endforeach;
        return $responsaveis;
    }

    /**
     * @param array $responsaveis
     */
    public function setResponsaveis($responsaveis)
    {
        $this->responsaveis = $responsaveis;
    }



}