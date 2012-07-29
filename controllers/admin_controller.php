<?php

class AdminController extends BaseController {

    public function delete() {

        if (isset($_POST['btn_delete'])) {
            csrf::check();
            if (post::delete_by_img_url($_POST['img_url'])) {
                $this->_msgs[] = 'Successfully deleted this post:<br/><br/><img src="' . hsc($_POST['img_url']) . '" />';
            } else {
                $this->_errors[] = 'Image not found or maybe already deleted';
            }
        }
        $this->_render('admin/delete');
    }
}