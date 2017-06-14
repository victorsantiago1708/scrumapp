<?php

require_once ("configuration/datasource/Datasource.php");
require_once ("classes.php/TypeEnum.php");
require_once ("classes.php/exceptions/ArquivoNaoEncontradoException.php");
require_once ("classes.php/exceptions/AtributosNulosException.php");

class ORM
{
    private $class = "";
    private $attributes = array();

    public function map_classes(){
        $scanned_directory = array_diff(scandir("model/"), array('..', '.'));
        foreach ($scanned_directory as $arquivo):
            $this->class = str_replace(".php", "", $arquivo);
            $this->attributes = array();
            self::create_table_model("model/".$arquivo);
        endforeach;
    }

    private function create_table_model($class){
        $classeContent = file_get_contents($class);

        $patternMapClass = "/(class|\$|\@)[A-Za-z\:\$\s]+/";
        $patternMapType = "/\@[A-Za-z\:]+/";
        $patterMapAttribute = "/\\$[A-Za-z]+/";

        $matches = array();
        preg_match_all($patternMapClass, $classeContent, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $line):
            if($line != "" && count($line) > 0){
                $posStringClass = strpos($line[0], "class");
                if($posStringClass > -1){
                    $class = str_replace("class", "", trim($line[0]));
                }else{
                    $type = "";
                    preg_match_all($patternMapType, trim($line[0]), $type, PREG_OFFSET_CAPTURE)[0];
                    $tipo = $type[0][0][0];
                    $tipo = str_replace("@Type:", "", $tipo);
                    $attributes = "";
                    preg_match_all($patterMapAttribute, trim($line[0]), $attributes, PREG_OFFSET_CAPTURE)[0];
                    $attribute = $attributes[0][0][0];
                    $attribute = str_replace("$", "", $attribute);
                    $this->attributes[$attribute] = $tipo;
                }
            }else{
                throw new ArquivoNaoEncontradoException("Arquivo: ".$class." fora do padrão!");
            }
        endforeach;

        try{
            self::create_table();
        }catch (AtributosNulosException $atributosNulosException){
            throw $atributosNulosException;
        }
    }

    private function create_table(){
        if(count($this->attributes) > 0){
            $sql  = "create table IF NOT EXISTS {$this->class} (";
            if(!array_key_exists('id', $this->attributes))
                $sql .= "id INT unique auto_increment not null primary key,";

            foreach ($this->attributes as $key => $value):
                if($key == "id"){
                    $sql .= "id ".self::get_type_sql($value)." not null auto_increment primary key,";
                }else{
                    $sql .= $key." ".self::get_type_sql($value)." not null,";
                }

            endforeach;

            $virgulapos = strrpos($sql, ",");
            $sql = substr($sql, 0, $virgulapos);
            $sql .= ");\n";
            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();
        }else{
            throw new AtributosNulosException("Nenhum atributo enviado para a criação da tabela: ".$this->class);
        }
    }

    private function get_type_sql($tipo){
        switch (strtolower($tipo)){
            case "string":
                return TypeEnum::String;
                break;
            case "long":
                return TypeEnum::Long;
                break;
            case "int":
                return TypeEnum::Integer;
                break;
            case "boolean":
                return TypeEnum::Boolean;
                break;
            case "double":
                return TypeEnum::Double;
                break;
            case "date":
                return TypeEnum::Date;
                break;
        }
    }
}