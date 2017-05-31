<?php


abstract class Model
{
    public static function create( $params, $classe ){
        $elements = array();
        foreach ($params as $key => $value){
            $element = new $classe();
            foreach ($value as $key2 => $value2){
                $set = "set";
                $method = $set.ucfirst($key2);
                $element->$method($value2);
            }
            array_push($elements, $element);
        }

        return $elements;
    }

    public static function getModel($id, $classe){
        $sql = "Select * from {strtolower($classe)} where id = {$id}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return self::create($rows, strtoupper($classe))[0];
    }

    public static function findAllModel($classe){
        $sql = "Select * from {strtolower($classe)}";
        $result = Datasource::getInstance()->query( $sql );
        $rows = $result->fetchAll( PDO::FETCH_ASSOC );
        return self::create($rows, strtoupper($classe));
    }

}