<?php

class View
{
    private $view = "";
    private $params = array();
    private $content = "";
    private $errors = array();
    private $msgsSucesso = array();

    public function __construct( $view, $params = array() )
    {
        $this->view = $view;
        $this->params = $params;
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

    public function getMsgsSucesso(){
        return $this->msgsSucesso;
    }

    public function getDivSuccess(){
        $html = "<div class='alert alert-success alert-dismissable'>";
        $html .= "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $html .= "<ul>";
        foreach ($this->msgsSucesso as $msg):
            $html .= "<li>".$msg."</li>";
        endforeach;
        $html .= "</ul>";
        $html .= "</div>";
        return $html;
    }

    public function setMsgsSucesso($msgs = array()){
        $this->msgsSucesso = $msgs;
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

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }




}