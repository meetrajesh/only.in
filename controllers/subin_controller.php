<?php

class SubinController extends BaseController {

    public function view($args) {
        // grab the args
        $args = array_pad($args, 3, '');
        list($subin_name, $tab, $page) = $args;

        // set default tab if not provided
        if (!in_array($tab, array('popular', 'latest'))) {
            $tab = 'popular';
        }

        // default page number
        $page = (int)$page > 0 ? $page : 1;

        $subin = subin::slug_to_subin($subin_name);
        $subin_id = empty($subin['subin_id']) ? -1 : $subin['subin_id'];

        if ($tab == 'popular') {
            $data['posts'] = post::get_popular($subin_id, $page);
        } elseif ($tab == 'latest') {
            $data['posts'] = post::get_latest($subin_id, $page);
        } else {
            $data['posts'] = array();
        }

        $this->_render('posts/base', $data);

    }

}