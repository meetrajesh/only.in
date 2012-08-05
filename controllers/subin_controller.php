<?php

class SubinController extends BaseController {

    public static function get_matching_posts($subin_slug, $tab, $lt_id=0) {

        // set default tab if not provided
        if (!in_array($tab, array_keys(post::$POST_TABS))) {
            $tab = 'popular';
        }

        // default lt_id post id
        $lt_id = ($lt_id > 0) ? (int) $lt_id : 0;

        $subin = subin::slug_to_subin($subin_slug);
        $subin_id = empty($subin['subin_id']) ? 0 : $subin['subin_id'];

        if ($tab == 'latest') {
            $data['posts'] = post::get_latest($subin_id, 0, $lt_id);
        } elseif (in_array($tab, array_keys(post::$POST_TABS))) {
            $func = "get_${tab}";
            $data['posts'] = post::$func($subin_id, $lt_id);
        } else {
            $data['posts'] = array();
        }

        $data['tab'] = $tab;
        $data['subin_name'] = $subin['name'];
        $data['subin_slug'] = $subin['slug'];

        // grab the last post id
        $last_post = first(array_slice($data['posts'], -1));
        $data['last_post_id'] = notempty($last_post, 'post_id', 0);

        return $data;

    }

    public function view($args) {
        // grab the args
        $args = array_pad($args, 2, '');
        list($subin_slug, $tab) = $args;

        $data = self::get_matching_posts($subin_slug, $tab);
        $this->_render('posts/base', $data);
    }

    public function browse() {
        $data['places'] = subin::all_places();
        $this->_render('places/base', $data);
    }

}