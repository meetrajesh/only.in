<?php

function v(&$var) {
    var_dump($var);
}

function __autoload($class) {
    if (preg_match('/(.+)controller$/i', $class, $controller)) {
        // controllers
        require './controllers/' . strtolower($controller[1]) . '_controller.php';
    } else {
        // models
        require './models/' . strtolower($class) . '.php';
    }
}

function in_str($needle, $haystack) {
    return false !== strpos($haystack, $needle);
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function error($str) {
    die($str);
}

foreach ($_GET as $k => $v) {
    $_GET[$k] = (string)$v;
}

foreach ($_POST as $k => $v) {
    $_POST[$k] = (string)$v;
}

