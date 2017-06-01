<?php

function __autoload( $class ){
    if(file_exists('classes.php/'. $class .'.php')){
        require_once('classes.php/' . $class . '.php');
    }

    if(file_exists('controller/'. $class .'.php')) {
        require_once('controller/' . $class . '.php');
    }
}

class ApplicationPHP
{
    private static $instance = null;
	static $path = "";
	static $http = null;
	
    private function __construct(){}

    public static function getInstance(){
        if(self::$instance!=null)
            return self::$instance;
        else
            return new ApplicationPHP();
    }

    public function runApp(){
        self::$http = new HttpRequest();
		self::$path = dirname($_SERVER['PHP_SELF']);
		self::resources();
    }

	static function resources(){
		$uriResources = "app/assets/resources/resources.php";
		if(file_exists($uriResources)){
			require_once( $uriResources );
		}
	}
	
	
}