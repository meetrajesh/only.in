<?php

class BaseController {

    protected function render($template, $data) {
        require './views/' . $template . '.html';
    }

}