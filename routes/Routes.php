<?php

class Routes
{
    static function routeRedirect( $http = null ){
        $httpRequest = $http;
        $classe = null;

        if($http!=null){
            if($httpRequest->getController()!= null){
                $classe = $httpRequest->getController()."Controller";
            }
        }
        try{
            if($classe != null){
                $instance = new $classe();
                $metodo = $httpRequest->getAction();

                if($instance!=null){
                    if(method_exists($instance, $metodo)){
                        $instance->$metodo();
                    }else{
                        throw new Exception("Metodo <b>".$metodo."</b> nao existe na classe <b>". $classe . "</b>" );
                    }
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }

    }
}