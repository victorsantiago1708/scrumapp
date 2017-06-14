<?php

require_once ("configuration/datasource/Datasource.php");
require_once ("classes.php/TypeEnum.php");
require_once ("classes.php/exceptions/ArquivoNaoEncontradoException.php");
require_once ("classes.php/exceptions/AtributosNulosException.php");
require_once ("classes.php/exceptions/TabelaNaoExisteException.php");

class ORM
{
    private $class = "";
    private $attributes = array();
    private $juncoes = array();

    public function map_classes(){
        $scanned_directory = array_diff(scandir("model/"), array('..', '.'));
        foreach ($scanned_directory as $arquivo):
            $this->class = str_replace(".php", "", $arquivo);
            $this->attributes = array();
            self::create_table_model("model/".$arquivo);
        endforeach;
        self::gera_juncoes();
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
                    $tipoAtributo = trim(substr($tipo, strpos($tipo, ":")+1, strlen($tipo)));
                    $modificador = trim(substr($tipo, 1, strpos($tipo, ":")-1));
                    $attributes = "";
                    preg_match_all($patterMapAttribute, trim($line[0]), $attributes, PREG_OFFSET_CAPTURE)[0];
                    $attribute = $attributes[0][0][0];
                    $attribute = str_replace("$", "", $attribute);

                    if($modificador != "Type"){
                        if(array_key_exists($this->class, $this->juncoes)){
                            if(array_key_exists($modificador, $this->juncoes[$this->class])){
                                array_push($this->juncoes[$this->class][$modificador], [$attribute => $tipoAtributo]);
                            }else{
                                $this->juncoes[$this->class] = [$modificador => array()];
                                array_push($this->juncoes[$this->class][$modificador], [$attribute => $tipoAtributo]);
                            }
                        }else{
                            $this->juncoes[$this->class] = [$modificador => array()];
                            array_push($this->juncoes[$this->class][$modificador], [$attribute => $tipoAtributo]);
                        }
                    }else{
                        $this->attributes[$attribute] = $tipoAtributo;
                    }
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
        $tipoSql = "";

        switch (strtolower($tipo)){
            case "string":
                $tipoSql = TypeEnum::String;
                break;
            case "long":
                $tipoSql = TypeEnum::Long;
                break;
            case "int":
                $tipoSql = TypeEnum::Integer;
                break;
            case "boolean":
                $tipoSql = TypeEnum::Boolean;
                break;
            case "double":
                $tipoSql = TypeEnum::Double;
                break;
            case "date":
                $tipoSql = TypeEnum::Date;
                break;
            case "byte":
                return TypeEnum::Byte;
                break;
        }

        return $tipoSql;
    }

    private function gera_juncoes(){
        if(count($this->juncoes) > 0){
            $juncoes = $this->juncoes;
            foreach ($juncoes as $classe => $modificadores){
                foreach ($modificadores as $modificador => $atributos){
                    if($modificador == "HasMany"){
                        self::create_table_juncao($classe, $atributos);
                    }else if($modificador == "BelongsTo"){
                        self::create_reference_column($classe, $atributos);
                    }else if($modificador == "HasOne"){

                    }
                }
            }
        }
    }

    private function create_table_juncao($table, $colunas){
        try{
            $sql = "";
            foreach ($colunas as $colKey => $atributos){
                foreach ($atributos as $key => $value){
                    $sql .= "CREATE TABLE IF NOT EXISTS {$table}_{$key} (";
                    $sql .= "id int not null auto_increment primary key,";
                    $sql .= "{$table}_id int not null,";
                    if(!self::isPropertie($value)){
                        $sql .= "{$table}_{$value} int not null,";
                        $sql .= "FOREIGN KEY ({$table}_{$value}) REFERENCES ".strtolower($value)." (id), ";
                    }else{
                        $tipo = self::get_type_sql($value);
                        $sql .= "{$table}_{$value} {$tipo} not null,";
                    }
                    $sql .= "FOREIGN KEY ({$table}_id) REFERENCES ".strtolower($table)." (id)";
                    $sql .= ");";

                }
            }
            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();
        }catch(Exception $e){
            throw new TabelaNaoExisteException("Tabela {$table} não existe no banco de dados");
        }
    }

    private function create_reference_column($table, $colunas){
        try{
            $sql = "";
            foreach ($colunas as $colKey => $atributos){
                foreach ($atributos as $nomeAtributo => $tipoAtributo){
                    $sql .= "ALTER TABLE {$table}";
                    if(!self::isPropertie($tipoAtributo)){
                        $sql .= " ADD {$nomeAtributo} int not null,";
                    }else{
                        $tipo = self::get_type_sql($tipoAtributo);
                        $sql .= " ADD {$nomeAtributo} {$tipo} not null,";
                    }
                    $sql .= " ADD CONSTRAINT FOREIGN KEY({$nomeAtributo}) REFERENCES {$table} (id);";
                }
            }
            $query = Datasource::getInstance()->prepare($sql);
            $query->execute();
        }catch(Exception $e){
            if(!$e->getCode() != "42S21"){
                throw new TabelaNaoExisteException("Tabela {$table} não existe no banco de dados");
            }
        }
    }

    private function isPropertie($prop){
        $result = false;
        switch (strtolower($prop)){
            case "string":
                $result = true;
                break;
            case "int":
                $result = true;
                break;
            case "boolean":
                $result = true;
                break;
            case "double":
                $result = true;
                break;
            case "byte":
                $result = true;
                break;
        }
        return $result;
    }
}