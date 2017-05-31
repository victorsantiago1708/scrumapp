<?php

class Sprint extends Model
{
    private $id = null;
    private $nome = "";
    private $descricao = "";
    private $status = "DISPONIVEL";
    private $projetoId = null;


    public function save( ){
        $sql = "";

        if($this->nome == "" || $this->descricao == "" || $this->status == "" || $this->projetoId == null ){
            return false;
        }

        if($this->id == null){
            $sql = "Insert into sprint (nome, descricao, status, projetoId) 
                      values ('{$this->nome}', '{$this->descricao}', '{$this->status}', {$this->getProjetoId()}";
        }else{
            $sql = "Update sprint set nome = '{$this->nome}', descricao = '{$this->descricao}', status = '{$this->status}', projetoId = {$this->getProjetoId()} where id = {$this->id}";
        }

        $query = Datasource::getInstance()->prepare($sql);
        $query->execute();

        return $query->rowCount() > 0;

    }

    public function delete(){
        $sql = "DELETE FROM sprint WHERE id = :id";
        $stmt = Datasource::getInstance()->prepare( $sql );
        $stmt->bindParam( ':id', $this->getId() );
        $result = $stmt->execute();
        return $stmt->rowCount() > 0;
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
    public function getProjetoId()
    {
        return $this->projetoId;
    }

    /**
     * @param null $projetoID
     */
    public function setProjetoId($projetoID)
    {
        $this->projetoId = $projetoID;
    }


}