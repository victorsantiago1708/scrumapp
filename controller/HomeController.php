<?php

/**
 * Created by PhpStorm.
 * User: RavTecnologia
 * Date: 31/05/2017
 * Time: 10:17
 */
class HomeController extends ControllerAbstract
{
    public function index(){
        $view = new View("views/home/index.phtml", array());
        $view->showContents();
    }
}