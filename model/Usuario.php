<?php

class Usuario extends Model
{

    //@Type:Long
    private $id = null;

    //@Type:String
    private $nome = "";

    //@Type:String
    private $login = "";

    //@Type:String
    private $senha = "";

    //@Type:String
    private $usuarioTipo = "ADMIN";

    //@Type:Date
    private $ultimoAcesso = null;

    //@Type:Boolean
    private $logado = false;


    public function logar(){
        if($this->login != "" && $this->senha != ""){
            $senha = md5($this->senha);
            $stmt = Datasource::getInstance()->prepare("select * from usuario where login = :login and senha = :senha");
            $stmt->bindParam(":login", $this->login);
            $stmt->bindParam(":senha", $senha);
            $stmt->execute();
            $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
            $user = self::create($rows, strtoupper("Usuario"))[0];
            $user->setLogado(true);
            $user->save();
            session_start();
            $_SESSION['usuario'] = $user;
            return $user;
        }else{
            return null;
        }
    }

    public function logoff(){
        $this->setLogado(false);
        unset($_SESSION['usuario']);
        session_destroy();
        return $this->save();
    }

    public function beforeInsert(){
        $this->senha = md5($this->senha);
    }

    public function save( ){
        $sql = "";

        if($this->nome == "" || $this->login == "" || $this->senha == "" || $this->usuarioTipo == null){
            return false;
        }

        $this->beforeInsert();

        try{
            Datasource::getInstance()->beginTransaction();

            if($this->id == null){
                $sql = "Insert into usuario (nome, login, senha, usuarioTipo, logado) 
                      values ('{$this->nome}', '{$this->login}', '{$this->senha}', '{$this->usuarioTipo}'), {$this->logado}";
            }else{
                $sql = "Update usuario set nome = '{$this->nome}', login = '{$this->login}', senha = '{$this->senha}', usuarioTipo = '{$this->usuarioTipo}',
                      ultimoAcesso = '{$this->ultimoAcesso}', logado = {$this->logado} where id = {$this->id}";
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
            $sql = "DELETE FROM usuario WHERE id = :id";
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

    public static function get($id){
        return Model::getModel($id, "Usuario");
    }

    public static function findAll(){
        return Model::findAllModel("Usuario");
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getUsuarioTipo()
    {
        return $this->usuarioTipo;
    }

    public function setUsuarioTipo($usuarioTipo)
    {
        $this->usuarioTipo = $usuarioTipo;
    }

    public function getUltimoAcesso()
    {
        return $this->ultimoAcesso;
    }

    public function setUltimoAcesso($ultimoAcesso)
    {
        $this->ultimoAcesso = $ultimoAcesso;
    }

    public function isLogado()
    {
        return $this->logado;
    }

    public function setLogado($logado)
    {
        $this->logado = $logado;
    }

}