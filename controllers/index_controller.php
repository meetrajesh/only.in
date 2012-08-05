<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        $uri = strtolower($uri);
        
        $routes = array('/$' => array('index', 'view', array('popular')), // empty route, just root domain
                        '/404' => array('index', 'misc_page', array('404')),
                        '/tos' => array('index', 'misc_page', array('tos')),
                        '/contact' => array('index', 'misc_page', array('contact')),
                        '/places' => array('subin', 'browse', array()),
                        '/popular' => array('index', 'view', array('popular')),
                        '/latest' => array('index', 'view', array('latest')),
                        '/debated' => array('index', 'view', array('debated')),
                        '/top' => array('index', 'view', array('top')),
                        '/post/add' => array('post', 'add', array()),
                        '/post/add_comment' => array('post', 'add_comment', array()),
                        '/admin/delete' => array('admin', 'delete', array()),
                        '/user/signup' => array('user', 'signup', array()),
                        '/user/login' => array('user', 'login', array()),
                        '/user/logout' => array('user', 'logout', array()),
                        '/\D{' . SUBIN_MIN_LEN . ',}/\D+/?' => array('subin', 'view', array()), // arbitrary subin, particular tab
                        '/\D{' . SUBIN_MIN_LEN . ',}/(\d+)/?' => array('post', 'view', array()), // individual post
                        '/\D{' . SUBIN_MIN_LEN . ',}/?' => array('subin', 'view', array()), // arbitrary subin
                        );

        foreach ($routes as $route => $dest) {
            if (preg_match("#^${route}#", $uri, $match)) {
                list($controller, $action, $args) = $dest;
                $route_match = array_shift($match);

                // grab any regex matches if they exist
                if (!empty($match)) {
                    $args = array_merge($args, $match);
                }

                // parse the rest of the uri for additional args
                if (!empty($args)) {
                    $uri = str_replace($route_match, '', $uri);
                }
                $uri = trim($uri, '/');
                if (!empty($uri)) {
                    $args = array_merge($args, explode('/', $uri));
                }
                break;
            }
        }

        #v($controller, $action, $args); exit;

        // 404
        if (empty($controller)) {
            list($controller, $action, $args) = array('index', 'missing_404', array());
        }

        $class = ucwords($controller) . 'Controller';
        $obj = new $class;
        $obj->$action($args);

    }
    
    public static function api_route() {
        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $method = preg_replace($regex, '', $_SERVER['REQUEST_URI']); // strip the prefix hostname

        // check api key
        if ($method != '/help' && (empty($_REQUEST['api_key']) || $_REQUEST['api_key'] != api_key(API_SECRET))) {
            return array('error' => 'missing or invalid api_key');
        }

        return api::call($method, $_REQUEST);
    }

    // 404, tos and contact pages
    public function misc_page($args) {
        $this->_render('misc/' . $args[0]);
    }

    public function view($args) {

        // grab the tab arg
        $tab = notempty($args, 0, 'popular');

        $data = SubinController::get_matching_posts('', $tab);
        $this->_render('posts/base', $data);

    }
}