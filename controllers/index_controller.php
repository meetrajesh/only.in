<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        $uri = ltrim($uri, '/');

        $args = array();
        if (in_str('/', $uri)) {
            // e.g. /user/signup
            list($controller, $action) = explode('/', $uri, 2);
            // if the action has additional params passed in, parse those as controller args
            if (in_str('/', $action)) {
                // e.g. /post/view/32
                list($action, $args) = explode('/', $action, 2);
                $args = explode('/', $args);
            }
        } elseif (!empty($uri)) {
            // e.g. /post
            list($controller, $action) = array($uri, 'view');
        } else {
            // e.g. /
            list($controller, $action) = array('index', 'view');
        }

        $class = ucwords($controller) . 'Controller';
        $obj = new $class;
        $obj->$action($args);

    }

    public function view() {
        $data['posts'] = post::get_recent();
        $this->_render('posts', $data);
    }


}