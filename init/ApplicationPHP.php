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
		self::load_entidades();
		self::load_resources();
    }

	private final static function load_resources(){
		$uriResources = "app/assets/resources/resources.php";
		if(file_exists($uriResources)){
			require_once( $uriResources );
		}
	}

	private final static function load_entidades(){
        $Orm = new ORM();
        try{
            $Orm->map_classes();
        }catch (ArquivoNaoEncontradoException $arquivoNaoEncontradoException){
            echo "<br/><b>".$arquivoNaoEncontradoException->getMessage()."</b>";
        }catch (AtributosNulosException $atributosNulosException){
            echo "<br/><b>".$atributosNulosException->getMessage()."</b>";
        }
    }
	
	
}