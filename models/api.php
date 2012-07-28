<?php

class api {

    public static function call($method, $args=array()) {

        $method = preg_replace('/\?.*/', '', $method); // no query params
        $method = trim($method, '/');

        list($controller) = explode('/', $method);
        $method = str_replace('/', '_', $method);

        $obj = new ApiController;

        // check if api method exists
        if (!method_exists($obj, $method)) {

            $api_methods = array_map(function($method) {
                    return '/' . preg_replace('/_/', '/', $method, 1);
                }, get_class_methods($obj));

            $errmsg = "Invalid API method: /${method}.";
            $errmsg .= ' Full list is: ' . implode(', ', $api_methods);
            return array('error' => $errmsg);
        }

        return $obj->$method($args);

    }

}