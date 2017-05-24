<?php

function __autoload( $class ){
    if(file_exists('controller/'. $class .'.php')) {
        require_once('controller/' . $class . '.php');
    }

    if(file_exists('classes.php/'. $class .'.php')){
        require_once('classes.php/' . $class . '.php');
    }
}

class Routes
{
    static function routeRedirect(){
        $httpRequest = new HttpRequest();
        $classe = null;

        if($httpRequest->getController()!= null){
            $classe = $httpRequest->getController()."Controller";
        }
        try{
            if($classe != null){
                $instance = new $classe();
                $metodo = $httpRequest->getAction();

                if($instance!=null){
                    if(method_exists($instance, $metodo)){
                        $instance->$metodo();
                    }else{
                        throw new Exception("Metodo ".$metodo." nao existe na classe ". $classe );
                    }
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }

    }
}