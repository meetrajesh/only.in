<?php

// alias var_dump() to v() for ease of typing
function v($var1, $var2=null, $var3=null) {
    switch (func_num_args()) {
    case 1:
        var_dump($var1);
        break;
    case 2:
        var_dump($var1, $var2);
        break;
    case 3:
        var_dump($var1, $var2, $var3);
        break;
    }
}

function d($var) {
    var_dump($var);
    exit;
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

function api_key($api_secret) {
    return $api_secret;
}

function absolutize($relative) {
    $url = BASE_URL;
    $prefix = PATH_PREFIX;
    if (!empty($prefix)) {
        $url .= '/' . PATH_PREFIX;
    }
    $url .= '/' . ltrim($relative, '/');
    return $url;
}

function filter_text($text, $replace_urls=false) {
    if ($replace_urls) {
        $text = preg_replace('~(https?://twitter.com/.+/status/\d+)~i', '(<a href="\1" target="_blank">tweet</a>)', $text, -1, $count);
        if ($count == 0) {
            $text = preg_replace('~(https?://(?:.*\.)?(.+\.(us|in|org|com|net|info|biz|edu|gov|uk|ca|de|jp|fr|au|ru|ch|it|es|mil))\S*)~i', '(<a href="\1" target="_blank">\2</a>)', $text);
        }
    }
    $text = '<p>' . str_replace(array("\n\n", "\n"), array('</p><p>', '<br>'), $text) . '</p>';
    return $text;
}

// convert text to slug 
function slug_from_name($name) {
    $name = trim(strtolower($name));
    // get rid of funky chars
    $name = str_replace(array("\r", "\n", "\t"), '', $name);
    // replace sequence of white-space or underscores with hyphen
    $name = preg_replace('/[\s_]+/', '-', $name);
    // get rid of all non-word chars
    $name = preg_replace('/[^\w-]/', '', $name);
    // replace underscores with hyphens
    $name = str_replace('_', '-', $name);
    // replace multiple hyphens with single hyphen
    $name = preg_replace('/-+/', '-', $name);

    return $name;
}

function first($data) {
    if (is_array($data)) {
        return reset($data);
    } else {
        return substr($data, 0, 1);
    }
}

function left($str, $n) {
    return substr($str, 0, $n);
}

function checkreturn($array, $key, $default='') {
    return isset($array[$key]) ? $array[$key] : $default;
}

function notempty($array, $key, $default='') {
    if (is_array($array)) {
        return !empty($array[$key]) ? $array[$key] : $default;
    } else {
        return !empty($array) ? $array : $default;
    }
}

// shorthand for sprintf/vsprintf
function spf($format, $args=array()) {
    $args = func_get_args();
    $format = array_shift($args);
    if (isset($args[0]) && is_array($args[0])) {
        $args = $args[0];
    }
    return vsprintf($format, $args);
}

function add_define($key, $val) {
    if (!defined($key)) {
        define($key, $val);
    }
}