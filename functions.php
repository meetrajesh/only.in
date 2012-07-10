<?php

// alias var_dump() to v() for ease of typing
function v(&$var) {
    var_dump($var);
}

function __autoload($class) {
    if (preg_match('/(.+)controller$/i', $class, $controller)) {
        // controllers
        require './controllers/' . strtolower($controller[1]) . '_controller.php';
    } else {
        // models
        if (file_exists($file = './models/' . strtolower($class) . '.php')) {
            require $file;
        } elseif (file_exists($file = './lib/' . strtolower($class) . '.php')) {
            require $file;
        }
    }
}

function in_str($needle, $haystack) {
    if (is_array($needle)) {
        // OR search - must have at least one needle in haystack
        foreach ($needle as $each_needle) {
            if (in_str($each_needle, $haystack)) {
                return true;
            }
        }
        return false;
    }
    return false !== strpos($haystack, $needle);
}

function error($msg) {
    die($msg);
}

function hsc($str) {
    #return htmlspecialchars($str);
    return htmlentities($str, ENT_QUOTES, "UTF-8"); 
}

function pts($key, $default='') {
    return !empty($_POST[$key]) ? hsc($_POST[$key]) : hsc($default);
}

function ago($time) {
    $units = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
    $lengths = array(60, 60, 24, 7, 4.3, 12);
    $delta = time() - $time;

    if ($delta < 0) {
        return 'right now';
    } else {
        while (($delta > $lengths[0]) && (count($lengths))) {
            $delta /= array_shift($lengths);
            array_shift($units);
        }
        $pluralize = ($delta == 1) ? '' : 's';
        return sprintf('%d %s%s ago', ceil($delta), $units[0], $pluralize);
    }
}