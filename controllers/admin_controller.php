<?php

class AdminController extends BaseController {

    public function delete() {

        if (isset($_POST['btn_delete'])) {
            csrf::check();

            if (!empty($_POST['post_id'])) {
                list($status, $post_id, $img_url) = post::delete_by_id($_POST['post_id']);
            } elseif (!empty($_POST['img_url'])) {
                list($status, $post_id, $img_url) = post::delete_by_img_url($_POST['img_url']);
            }

            if ($status) {
                $this->_msgs[] = 'Successfully deleted this post:<br/><br/><img src="' . hsc($img_url) . '" />';
            } else {
                $this->_errors[] = 'Image not found or maybe already deleted';
            }
        }
        $this->_render('admin/delete');
    }
}