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
		self::applicationcss();
		self::applicationjs();
		self::resources();
    }
	
	static function applicationcss(){
		$uricss = self::$path."/app/assets/css/application.css";
		$boostrapuri = self::$path."/app/assets/bootstrap-3.3.7-dist/css/bootstrap.css";
        echo "<link rel='stylesheet' href='".$boostrapuri."' type='text/css' />";
		echo "<link rel='stylesheet' href='".$uricss."' type='text/css' />";
	}
	
	static function applicationjs(){
		$urijs = self::$path."/app/assets/javascript/application.js";
        $boostrapuri = self::$path."/app/assets/bootstrap-3.3.7-dist/js/bootstrap.js";
        $jqueryuri = self::$path."/app/assets/javascript/jquery-3.2.1.js";
        echo "<script src='".$jqueryuri."' type='text/javascript'></script>";
		echo "<script src='".$urijs."' type='text/javascript'></script>";
        echo "<script src='".$boostrapuri."' type='text/javascript'></script>";
	}
	
	static function resources(){
		$uriResources = "app/assets/resources/resources.php";
		if(file_exists($uriResources)){
			require_once( $uriResources );
		}
	}
	
	
}