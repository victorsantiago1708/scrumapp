<?php

class Usuario extends Model
{
    private $id = null;
    private $nome = "";
    private $login = "";
    private $senha = "";
    private $usuarioTipo = "ADMIN";
    private $ultimoAcesso = null;


    public function login(){
        if($this->login != "" && $this->senha != ""){
            $senha = md5($this->senha);

            $stmt = Datasource::getInstance()->prepare("select * from usuario where login = :login and senha = :senha");
            $stmt->bindParam(":login", $this->login);
            $stmt->bindParam(":senha", $senha);
            $stmt->execute();
            $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
            return self::create($rows, strtoupper("Usuario"))[0];
        }else{
            return null;
        }
    }

    public function beforeInsert(){
        $this->senha = md5($this->senha);
    }

    public function save( $id = null ){
        $sql = "";

        if($this->nome == "" || $this->login == "" || $this->senha == "" || $this->usuarioTipo == null){
            return false;
        }

        $this->beforeInsert();

        if($id == null){
            $sql = "Insert into usuario (nome, login, senha, usuarioTipo) 
                      values ('{$this->nome}', '{$this->login}', '{$this->senha}', '{$this->usuarioTipo}')";
        }else{
            $sql = "Update usuario set nome = '{$this->nome}', login = '{$this->login}', senha = '{$this->senha}', usuarioTipo = '{$this->usuarioTipo}',
                      ultimoAcesso = '{$this->ultimoAcesso}' where id = {$id}";
        }

        $query = Datasource::getInstance()->prepare($sql);
        $query->execute();

        return $query->rowCount() > 0;

    }

    public function delete(){
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = Datasource::getInstance()->prepare( $sql );
        $stmt->bindParam( ':id', $this->getId() );
        $result = $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function get($id){
        return Model::getModel($id, "Usuario");
    }

    public static function findAll(){
        return Model::findAllModel("Usuario");
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return string
     */
    public function getUsuarioTipo()
    {
        return $this->usuarioTipo;
    }

    /**
     * @param string $usuarioTipo
     */
    public function setUsuarioTipo($usuarioTipo)
    {
        $this->usuarioTipo = $usuarioTipo;
    }

    /**
     * @return null
     */
    public function getUltimoAcesso()
    {
        return $this->ultimoAcesso;
    }

    /**
     * @param null $ultimoAcesso
     */
    public function setUltimoAcesso($ultimoAcesso)
    {
        $this->ultimoAcesso = $ultimoAcesso;
    }


}