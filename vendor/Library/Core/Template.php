<?php

/**
 *
 */

namespace Library\Core;

class Template
{
    public $view_path;
    public $variables;
    public $view;

    /**
     * 
     */
    public function __construct($view_path = '')
    {
        $this->view_path = $view_path;
    }

    /**
     * 
     */
    public function set($variable, $value)
    {
        $this->variables[$variable] = $value;
    }

    /**
     * 
     */
    public function prepare()
    {
        extract($this->variables);
        ob_start();
        include ($this->view_path);
        $this->view = ob_get_contents();
        ob_end_clean();
    }

    /**
     * 
     */
    public function render()
    {
        $this->prepare();
        echo $this->view;
    }
}