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

    public function cycle($pointer, $list=array()) {
        $list = func_get_args();
        $pointer = array_shift($list);

        if (count($list)) {
            $pointer %= count($list);
            return $list[$pointer];
        } else {
            return false;
        }
    }

    public function pluralize($count, $plural='s', $singular='') {
        if (is_array($count)) {
            $count = count($count);
        }

        return ((int)$count == 1) ? $singular : $plural;
    }

}