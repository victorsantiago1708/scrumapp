<?php

class View
{
    private $view = "";
    static $params = array();
    private $content = "";
    static $errors = array();
    static $msgsSucesso = array();

    public function __construct( $view, $params = array() )
    {
        $this->view = $view;
        self::$params = $params;
    }

    private function getContents()
    {
        ob_start();
        if(isset($this->view))
            require_once $this->view;
        $this->content = ob_get_contents();
        ob_end_clean();
        return $this->content;
    }

    public static function getDivSuccess(){
        $html = "<div class='alert alert-success alert-dismissable'>";
        $html .= "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $html .= "<ul>";
        foreach (self::$msgsSucesso as $msg):
            $html .= "<li>".$msg."</li>";
        endforeach;
        $html .= "</ul>";
        $html .= "</div>";
        return $html;
    }

    public function setMsgsSucesso($msgs = array()){
        self::$msgsSucesso = $msgs;
    }

    /**
     * Imprime o arquivo de visualização
     */
    public function showContents()
    {
        echo $this->getContents();
        exit;
    }


    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param string $nome
     */
    public function setView($view)
    {
        $this->view = $view;
    }

}