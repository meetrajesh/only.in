<?php

class SubinController extends BaseController {

    public static function get_matching_posts($subin_slug, $tab, $page) {

        // set default tab if not provided
        if (!in_array($tab, array_keys(post::$PAGE_TABS))) {
            $tab = 'popular';
        }

        // default page number
        $page = ($page > 0) ? (int) $page : 1;

        $subin = subin::slug_to_subin($subin_slug);
        $subin_id = empty($subin['subin_id']) ? 0 : $subin['subin_id'];

        $data = array();

        if ($tab == 'latest') {
            $data['posts'] = post::get_latest($subin_id, 0, $page);
        } elseif (in_array($tab, array_keys(post::$PAGE_TABS))) {
            $func = "get_${tab}";
            $data['posts'] = post::$func($subin_id, $page);
        } else {
            $data['posts'] = array();
        }

        $data['tab'] = $tab;
        $data['subin_name'] = $subin['name'];
        $data['subin_slug'] = $subin['slug'];

        return $data;

    }

    public function view($args) {
        // grab the args
        $args = array_pad($args, 3, '');
        list($subin_slug, $tab, $page) = $args;

        $data = self::get_matching_posts($subin_slug, $tab, $page);
        $this->_render('posts/base', $data);
    }

    public function browse() {
        $data['places'] = subin::all_places();
        $this->_render('places/base', $data);
    }

}