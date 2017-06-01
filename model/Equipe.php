<?php


class Equipe extends Model
{
    private $id = null;
    private $nome = "";
    private $membros = array();
    private $categoria = "";


    public function save( ){
        $sql = "";

        if($this->nome == "" || $this->categoria == "" || count($this->membros) <= 0){
            return false;
        }

        if($this->id == null){
            $sql = "Insert into `equipe` (`nome`, `categoria`) 
                      values ('{$this->nome}', '{$this->categoria}')";
        }else{
            $sql = "Update `equipe` set `nome` = '{$this->nome}', `categoria` = '{$this->categoria}' where `id` = {$this->id}";
        }

        $query = Datasource::getInstance()->prepare($sql);
        $query->execute();

        if($query->rowCount() > 0){
            if(!$this->saveMembros(self::getEquipeRecenteAdd())){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }

    }

    private static function getEquipeRecenteAdd(){
        $sql = "Select * from equipe order by id desc limit 1";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        if(isset(self::create($rows, strtoupper("Equipe"))[0])){
            return self::create($rows, strtoupper("Equipe"))[0];
        }else{
            return null;
        }
    }

    public function saveMembros($equipe = null){
        if($equipe != null){
            $sql = "delete from equipe_membros where equipe_id = {$equipe->getId()}";
            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();

            foreach ($this->membros as $membro):
                $sql = "Insert into equipe_membros (`equipe_id`, `usuario_id`) values ({$equipe->getId()},{$membro->getId()})";
                $query = Datasource::getInstance()->prepare($sql);
                $query->execute();
            endforeach;

            return true;
        }else{
            return false;
        }
    }

    public function delete(){

        $sql = "DELETE FROM equipe_membros WHERE equipe_id = :id";
        $stmt = Datasource::getInstance()->prepare( $sql );
        $stmt->bindParam( ':id', $this->getId() );
        $result = $stmt->execute();

        if($stmt->rowCount() > 0){
            $sql = "DELETE FROM equipe WHERE id = :id";
            $stmt = Datasource::getInstance()->prepare( $sql );
            $stmt->bindParam( ':id', $this->getId() );
            $result = $stmt->execute();
        }

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
        $sql = "Select * from equipe_membros where equipe_id = {$this->getId()}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        $membros = array();
        foreach ($rows as $row):
            $membro = Usuario::get($row['usuario_id']);
            array_push($membros, $membro);
        endforeach;
        return $membros;
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