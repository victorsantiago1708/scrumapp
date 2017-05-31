<?php

class View
{
    private $view = "";
    private $params = array();
    private $content = "";

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


}