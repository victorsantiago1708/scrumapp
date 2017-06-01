<?php


class HomeController extends ControllerAbstract
{
    public function index(){
        $view = new View("views/home/index.phtml", array());
        $view->showContents();
    }
}