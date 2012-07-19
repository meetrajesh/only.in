<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        
        $routes = array('/$' => array('index', 'view', array('popular')), // empty route, just root domain
                        '/latest' => array('index', 'view', array('latest')),
                        '/popular' => array('index', 'view', array('popular')),
                        '/post/add' => array('post', 'add', array()),
                        '/post/add_comment' => array('post', 'add_comment', array()),
                        '/admin/delete' => array('admin', 'delete', array()),
                        '/user/signup' => array('user', 'signup', array()),
                        '/user/login' => array('user', 'login', array()),
                        '/user/logout' => array('user', 'logout', array()),
                        '/.{' . SUBIN_MIN_LEN . ',}/?' => array('subin', 'view', array()), // arbitrary subin
                        );

        foreach ($routes as $route => $dest) {
            if (preg_match("~^(${route})~", $uri, $match)) {
                list($controller, $action, $args) = $dest;
                // look for more args
                if (!empty($args)) {
                    $uri = str_replace($match[0], '', $uri);
                }
                $uri = trim($uri, '/');
                if (!empty($uri)) {
                    $args = array_merge($args, explode('/', $uri));
                }
                break;
            }
        }

        #v($controller, $action, $args);

        // 404
        if (empty($controller)) {
            die('404');
        }

        $class = ucwords($controller) . 'Controller';
        $obj = new $class;
        $obj->$action($args);

    }
    
    public static function api_route() {

        // check api key
        if (empty($_REQUEST['api_key']) || $_REQUEST['api_key'] != api_key(API_SECRET)) {
            return array('error' => 'invalid api key');
        }

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $method = preg_replace($regex, '', $_SERVER['REQUEST_URI']); // strip the prefix hostname

        return api::call($method, $_REQUEST);

    }

    public function view($args) {

        // grab the args
        $args = array_pad($args, 2, '');
        list($tab, $page) = $args;

        $page = !empty($page) && ctype_digit((string) $page) ? (int) $page : 1;

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