<?php

class ControllerAbstract
{
    public function index(){

    }

    public function novo(){

    }

    public function delete(){

    }

    public function save(){

    }

    public function edit(){

    }

    public function redirect($link){
        header("location: $link");
    }

    public function flashClear(){
        View::$msgsSucesso = array();
        View::$params = array();
        View::$errors = array();
    }

}