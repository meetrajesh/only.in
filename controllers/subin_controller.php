<?php

class SubinController extends BaseController {

    public function view($subin) {
        // grab the tab name if it exists
        $tab = 'popular';
        if (count($subin) == 1) {
            list($subin_name) = $subin;
        } else {
            list($subin_name, $tab) = $subin;
        }

        if (in_array($subin_name, array('popular', 'latest'))) {
            $subin['subin_id'] = 0;
            $tab = $subin_name;
        } else {
            $subin = subin::slug_to_subin($subin_name);
            if (empty($subin['subin_id'])) {
                $subin['subin_id'] = -1;
            }
        }

        if ($tab == 'popular') {
            $data['posts'] = post::get_popular($subin['subin_id']);
        } elseif ($tab == 'latest') {
            $data['posts'] = post::get_latest($subin['subin_id']);
        } else {
            $data['posts'] = array();
        }

        $this->_render('posts/base', $data);

    }

}