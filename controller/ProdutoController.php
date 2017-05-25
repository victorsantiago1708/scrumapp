<?php

class ProdutoController
{
    public function index(){
        print_r(HttpRequest::$params);
    }
}