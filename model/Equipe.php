<?php


class Equipe extends Model
{
    private $id = null;
    private $nome = "";
    private $membros = array();
    private $categoria = "";


    public function save( ){
        $sql = "";

        if($this->nome == "" || $this->categoria == ""){
            return false;
        }

        if($this->id == null){
            $sql = "Insert into equipe (nome, categoria) 
                      values ('{$this->nome}', '{$this->categoria}')";
        }else{
            $sql = "Update equipe set nome = '{$this->nome}', categoria = '{$this->categoria}' where id = {$this->id}";
        }

        $query = Datasource::getInstance()->prepare($sql);
        $query->execute();

        return $query->rowCount() > 0;

    }

    public function delete(){

        $sql = "DELETE FROM equipe WHERE id = :id";
        $stmt = Datasource::getInstance()->prepare( $sql );
        $stmt->bindParam( ':id', $this->getId() );
        $result = $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function get($id){
        return Model::getModel($id, "Equipe");
    }

    public static function findAll(){
        return Model::findAllModel("Equipe");
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
     * @return array
     */
    public function getMembros()
    {
        return $this->membros;
    }

    /**
     * @param array $membros
     */
    public function setMembros($membros)
    {
        $this->membros = $membros;
    }

    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param string $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }


}