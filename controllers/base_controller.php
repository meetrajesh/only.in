<?php

class BaseController {

    protected $_errors = array();

    protected function __construct() {
        if (!empty($_POST) || !empty($_GET) || !empty($_REQUEST)) {
            csrf::check();
        }
    }

    protected function _render($template, $data=array()) {
        $data['errors'] = $this->_errors;
        require './views/' . $template . '.php';
    }

    protected function _display_errors() {
        foreach ($this->_errors as $error) {
            echo '<span style="color: maroon;">' . hsc($error) . '</span>';
        }
    }

}