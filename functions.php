<?php

# clean up $_GET and $_POST, ensure all values are strings (no arrays)
foreach ($_GET as $k => $v) {
    $_GET[$k] = (string)$v;
}

foreach ($_POST as $k => $v) {
    $_POST[$k] = (string)$v;
}

# alias var_dump() to v() for ease of typing
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

function error($msg) {
    die($msg);
}

function hsc($str) {
    return htmlspecialchars($str);
}