<?php

class ORM
{
    public $conteudo = "";
    public function map_class( $classpath ){
        ob_start();
        $this->conteudo = ob_get_contents();
//        $matches = array();
//        //preg_match_all('/(\$([A-Za-z]+\:))\w+/', ob_get_contents(), $matches, PREG_OFFSET_CAPTURE);
//        echo ob_get_contents();
        ob_end_clean();
    }
}