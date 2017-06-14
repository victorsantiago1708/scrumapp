<?php

class Mensagem extends Model
{
    //@Type:Long
    private $id = null;

    //@BelongsTo:Usuario
    private $remetente = null;

    //@Type:Long
    private $destinatario = null;

    //@Type:String
    private $mensagem = "";

    //@Type:Date
    private $dataEnvio = null;

    public static function get($id){
        return Model::getModel($id, "Mensagem");
    }

    public static function findAll(){
        return Model::findAllModel("Mensagem");
    }

    public function save( ){
        $sql = "";

        if($this->remetente == null || $this->destinatario == null || $this->mensagem == ""){
            return false;
        }

        try{
            Datasource::getInstance()->beginTransaction();

            $this->dataEnvio = date("d-m-Y H:i:s");

            if($this->id == null){
                $sql = "Insert into mensagem (remetente, destinatario, mensagem, dataEnvio) 
                      values ({$this->remetente}, {$this->destinatario}, '{$this->mensagem}', '{$this->dataEnvio}')";
            }else{
                $sql = "Update mensagem set remetente = {$this->remetente}, destinatario = {$this->destinatario}, mensagem = '{$this->mensagem}', dataEnvio = '{$this->dataEnvio}',
                      where id = {$this->id}";
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

            $sql = "DELETE FROM mensagem WHERE id = :id";
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
}