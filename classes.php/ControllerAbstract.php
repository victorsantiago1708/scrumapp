<?php

class ControllerAbstract
{
    public function index(){

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

}