<?php

class User extends Model
{
    private $nome = "";
    private $login = "";
    private $senha = "";
    private $userTipo = "ADMIN";

    public static function get($id){
        return Model::getModel($id, "User");
    }

    public static function findAll(){
        return Model::findAllModel("User");
    }
}