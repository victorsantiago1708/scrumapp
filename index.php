<?php
//require_once("init/ApplicationPHP.php");
//require_once("routes/Routes.php");
//
//ApplicationPHP::getInstance()->runApp();
//Routes::routeRedirect();
//require_once ("classes.php/ORM.php");
//$orm = new ORM();
//echo $orm->conteudo;

require_once ("classes.php/TypeEnum.php");
require_once ("configuration/datasource/Datasource.php");


function map_models(){
    $dir = "mapping/";
    $files = array_diff(scandir($dir), array('..', '.'));

    foreach ($files as $file):
        echo $dir.$file."<br/>";
        make_model($dir.$file, "scrumapp");
    endforeach;
}

function make_model($modeltxt, $database){
    $file = file_get_contents($modeltxt);
    $conteudo = $file;
    $matches = array();
    preg_match_all('/[(\$a-zA-Z\s\=\s\")]+/', $conteudo, $matches, PREG_OFFSET_CAPTURE);

    $content = "";
    $classe = "";
    $atributes = array();

    foreach ($matches[0] as $property):
        foreach($property as $propertyName):
            if(!is_numeric($propertyName)){
                if(strpos($propertyName,"class") > -1){
                    $classe = str_replace("class", "", $propertyName);
                    $content .= "create table $classe(\n";
                }else{
                    $atribute = array_filter(explode("=", $propertyName));
                    if(strlen(trim($atribute[0])) > 0){

                        $atributeName = trim(str_replace("$", "", $atribute[0]));
                        $type = trim(str_replace("\"", "", $atribute[1]));

                        if($atributeName == "id") {
                            $content .= "\t".$atributeName . " " . TypeEnum::Long . " not null auto_increment primary key,\n";
                        }else{
                            switch ($type){
                                case "String":
                                    $content .= "\t".$atributeName." ".TypeEnum::String.",\n";
                                    break;
                                case "Long":
                                    $content .= "\t".$atributeName." ".TypeEnum::Long.",\n";
                                    break;
                                case "Date":
                                    $content .= "\t".$atributeName." ".TypeEnum::Date.",\n";
                                    break;
                                case "Integer":
                                    $content .= "\t".$atributeName." ".TypeEnum::Integer.",\n";
                                    break;
                                case "Text":
                                    $content .= "\t".$atributeName." ".TypeEnum::Text.",\n";
                                    break;
                            }
                        }
                    }
                    //
                }
            }
        endforeach;
    endforeach;

    $virgulapos = strrpos($content, ",");
    $content = substr($content, 0, $virgulapos)."\n";
    $content .= ");";

    echo $content."<br/>";

    $query = Datasource::getInstance()->prepare("Use $database;\n".$content );
    $query->execute();

    $myfile = fopen("tables/$classe.sql", "w") or die("Unable to open file!");
    fwrite($myfile, $content);
    fclose($myfile);
}

map_models();
?>
