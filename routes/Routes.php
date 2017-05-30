<?php

class Routes
{
    static function routeRedirect( ){
        $classe = null;

        if(HttpRequest::$controller!= null){
            $classe = HttpRequest::$controller."Controller";
        }

        try{
            if($classe != null){
                $instance = new $classe();
                $metodo = HttpRequest::$action;

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