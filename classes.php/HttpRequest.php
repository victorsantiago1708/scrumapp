<?php 

class HttpRequest {

    static  $server = null;
    static  $request = null;
    static  $params = array();
    static  $controller = null;
    static  $action = null;

    public function __construct(){
        self::$request = explode("/", $_SERVER["REQUEST_URI"]);
        if(isset(self::$request[1]))
            self::$server = self::$request[1];
        if(isset(self::$request[2]))
            self::$controller = self::$request[2];
        if(isset(self::$request[3]) && self::$request[3] != "" && !is_numeric(self::$request[3])){
            self::$action = self::$request[3];
        }else if(!isset(self::$request[3]) || self::$request[3] == ""){
            self::$action = "index";
        }else if( (isset(self::$request[3]) && is_numeric(self::$request[3])) ){
            self::$params = ['id' => self::$request[3]];
        }
        if(isset(self::$request[4]))
            self::$params = self::$request[4];
    }

}
?>