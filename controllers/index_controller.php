<?php

class IndexController extends BaseController {

    public static function route() {

        $regex = '/^' . preg_quote(PATH_PREFIX, '/') . '/';
        $uri = preg_replace($regex, '', $_SERVER['REQUEST_URI']);
        $uri = ltrim($uri, '/');

        if (in_str('/', $uri)) {
            list($controller, $action, $args) = explode('/', $uri, 3);
            $args = explode('/', $uri);
        } elseif (!empty($uri)) {
            list($controller, $action) = array($uri, 'view');
        } else {
            list($controller, $action) = array('index', 'view');
        }

        $class = ucwords($controller) . 'Controller';
        $obj = new $class;
        $obj->$action();

    }

    public function view() {
        $data['posts'] = Post::get_recent();
        $this->render('index', $data);
    }


}