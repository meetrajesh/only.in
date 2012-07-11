<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        $uri = trim($uri, '/');

        $args = array();
        if (in_str('/', $uri) && !in_str(array('/latest', '/popular'), $uri)) {
            // e.g. /user/signup
            list($controller, $action) = explode('/', $uri, 2);
            // if the action has additional params passed in, parse those as controller args
            if (in_str('/', $action)) {
                // e.g. /post/view/32
                list($action, $args) = explode('/', $action, 2);
                $args = explode('/', $args);
            }
        } elseif (!empty($uri) && strlen($uri) > SUBIN_MIN_LEN) {
            // e.g. /toronto or /toronto/latest
            list($controller, $action, $args) = array('subin', 'view', explode('/', $uri));
        } else {
            // e.g. /
            list($controller, $action, $args) = array('index', 'view', 'popular');
        }

        $class = ucwords($controller) . 'Controller';
        $obj = new $class;
        $obj->$action($args);

    }
    
    public static function api_route() {

        // check api key
        if (empty($_REQUEST['api_key']) || $_REQUEST['api_key'] != sha1(floor(time() / 1800) . API_SECRET)) {
            return array('error' => 'invalid api key');
        }

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        $uri = preg_replace('/\?.*/', '', $uri);
        $uri = trim($uri, '/');

        list($controller) = explode('/', $uri);
        $method = str_replace('/', '_', $uri);

        $obj = new ApiController;

        // check if api method exists
        if (!method_exists($obj, $method)) {

            $api_methods = array_map(function($method) {
                    return '/' . preg_replace('/_/', '/', $method, 1);
                }, get_class_methods($obj));

            $errmsg = "Invalid API method: /${uri}.";
            $errmsg .= ' Full list is: ' . implode(', ', $api_methods);
            return array('error' => $errmsg);
        }

        return $obj->$method($_REQUEST);

    }

    public function view($tab='popular') {

        if ($tab == 'popular') {
            $data['posts'] = post::get_popular();
        } elseif ($tab == 'latest') {
            $data['posts'] = post::get_latest();
        } else {
            $data['posts'] = array();
        }

        $this->_render('posts/base', $data);
    }
}