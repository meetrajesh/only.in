<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        
        $routes = array('/$' => array('index', 'view', array('popular')), // empty route, just root domain
                        '/404' => array('index', 'missing_404', array()),
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
                        '/.{' . SUBIN_MIN_LEN . ',}/(\d+)/?' => array('post', 'view', array()),
                        '/.{' . SUBIN_MIN_LEN . ',}/?' => array('subin', 'view', array()), // arbitrary subin
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

        // check api key
        if (empty($_REQUEST['api_key']) || $_REQUEST['api_key'] != api_key(API_SECRET)) {
            return array('error' => 'invalid api key');
        }

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $method = preg_replace($regex, '', $_SERVER['REQUEST_URI']); // strip the prefix hostname

        return api::call($method, $_REQUEST);

    }

    // 404 handler
    public function missing_404($args) {
        $this->_render('404');
    }

    public function view($args) {

        // grab the args
        $args = array_pad($args, 2, '');
        list($tab, $page) = $args;

        $page = !empty($page) && ctype_digit((string) $page) ? (int) $page : 1;

        if ($tab == 'latest') {
            $data['posts'] = post::get_latest(0, 0, $page);
        } elseif (in_array($tab, array_keys(post::$PAGE_TABS))) {
            $func = 'get_' . $tab;
            $data['posts'] = post::$func(0, $page);
        } else {
            $data['posts'] = array();
        }

        $data['tab'] = $tab;
        $this->_render('posts/base', $data);
    }
}