<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        $uri = rtrim($uri, '/');
        
        $routes = array('$' => array('index', 'view', array('popular')),
                        '/latest' => array('index', 'view', array('latest')),
                        '/popular' => array('index', 'view', array('popular')),
                        );

        foreach ($routes as $route => $dest) {
            if (preg_match("~^(${route})~", $uri, $match)) {
                list($controller, $action, $args) = $dest;
                // look for more args
                $uri = trim(str_replace($match[0], '', $uri), '/');
                if (!empty($uri)) {
                    $args = array_merge($args, explode('/', $uri));
            }
                break;
            }
        }

        $all_controllers = array('admin', 'user', 'subin', 'post');

        if (empty($controller)) {
            foreach ($all_controllers as $cont) {
                if (preg_match("~^/${cont}/~", $uri)) {
                    $args = explode('/', $uri);
                    $controller = array_shift($args);
                    $action = array_shift($args);
                    break;
                }
            }
        }

        // look for subin
        if (empty($controller) && strlen($uri) > SUBIN_MIN_LEN) {
            $uri = trim($uri, '/');
            // e.g. /toronto or /toronto/latest
            list($controller, $action, $args) = array('subin', 'view', explode('/', $uri));
        }

        #v($controller, $action, $args);

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

    public function view($args) {

        $page = 1;
        if (count($args) == 1) {
            list($tab) = $args;
        } elseif (ctype_digit($args[1])) {
            list($tab, $page) = $args;
        }

        if ($tab == 'popular') {
            $data['posts'] = post::get_popular(0, $page);
        } elseif ($tab == 'latest') {
            $data['posts'] = post::get_latest(0, $page);
        } else {
            $data['posts'] = array();
        }

        $this->_render('posts/base', $data);
    }
}