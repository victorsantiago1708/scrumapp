<?php 

class HttpRequest {

    static  $server = null;
    static  $request = null;
    static  $params = array();
    static  $controller = null;
    static  $action = null;
    static  $httpMethod = "";

    public function __construct(){
        self::$httpMethod = $_SERVER['REQUEST_METHOD'];
        self::$request = explode("/", $_SERVER["REQUEST_URI"]);

        if(isset(self::$request[1]))
            self::$server = self::$request[1];
        if(isset(self::$request[2]))
            self::$controller = self::$request[2];
        if(isset(self::$request[3]) && self::$request[3] != "" && !is_numeric(self::$request[3])){
            $posparam = strpos(self::$request[3], "?");
            if($posparam != false){
                self::$action = substr(self::$request[3],0, $posparam);
                $params = explode("&", substr(self::$request[3],$posparam+1));
                foreach ($params as $param):
                    $paramName = substr($param, 0, strpos($param, "="));
                    $paramVal = substr($param, strpos($param, "=")+1);
                    self::$params[$paramName] =  $paramVal;
                endforeach;
            }else{
                self::$action = self::$request[3];
            }
        }else if(!isset(self::$request[3]) || self::$request[3] == ""){
            self::$action = "index";
        }else if( (isset(self::$request[3]) && is_numeric(self::$request[3])) ){
            self::$params = ['id' => self::$request[3]];
        }

        if( isset($_POST) && !empty($_POST) ){
            self::$params = $_POST;
        }

        if(isset(self::$request[4]))
            self::$params = self::$request[4];
    }

}
?>