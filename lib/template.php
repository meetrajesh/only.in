<?php

class template {

    protected $_block;
    protected $_blocks = array();

    public function block($name) {
        $this->_block = $name;
        ob_start();
    }

    public function endblock($output = false) {
        $content = ob_get_clean();

        if (!isset($this->_blocks[$this->_block])) {
            $this->_blocks[$this->_block] = $content;
        }

        if ($output) {
            echo $this->_blocks[$this->_block];
        }

        unset($this->_block);
    }

}