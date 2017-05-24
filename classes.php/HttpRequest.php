<?php 

class HttpRequest {

    static  $server;
    static  $request;
    static  $params = array();
    private $controller;
    private $action;

    public function __construct(){
        self::$request = explode("/", $_SERVER["REQUEST_URI"]);
        if(isset(self::$request[1]))
            self::$server = self::$request[1];
        if(isset(self::$request[2]))
            $this->controller = self::$request[2];
        if(isset(self::$request[3]) && !is_numeric(self::$request[3])){
            $this->action = self::$request[3];
        }else if(isset(self::$request[3])){
            self::$params = ['id' => self::$request[3]];
        }

        if(isset(self::$request[4]))
            self::$params = self::$request[4];
    }

    public function getController(){
        return $this->controller;
    }

    public function getAction(){
        return $this->action;
    }

}
?>