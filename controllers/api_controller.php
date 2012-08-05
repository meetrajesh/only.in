<?php

class ApiController extends BaseController {

    public function __construct() {
        // skip csrf check
        $this->_tpl = new template();
    }

    public function help($data) {
        return array('/user/username_exists' => array('inputs' => array('username' => 'string'),
                                                      'outputs' => array('username_exists' => 'bool')),

                     '/user/is_fake_user' => array('inputs' => array('username' => 'string'),
                                                  'outputs' => array('is_fake_user' => 'bool')),

                     '/user/create' => array('inputs' => array(array('username' => 'string'),
                                                              array('password' => 'string'),
                                                              array('name' => 'string'),
                                                              array('email' => 'string')),
                                                  'outputs' => array('user_id' => 'int')),

                     '/post/create' => array('inputs' => array(array('user_id' => 'int (optional)'),
                                                               array('username' => 'string (optional)'),
                                                               array('title' => 'string (optional)'),
                                                               array('content' => 'string (optional)'),
                                                               array('caption' => 'string (optional)'),
                                                               array('stamp' => 'int (optional)'),
                                                               array('subin_name' => 'string'),
                                                               array('num_upvotes' => 'int (optional)'),
                                                               array('num_downvotes' => 'int (optional)')),
                                             'outputs' => array(array('post_id' => 'int'),
                                                                array('permalink' => 'string'))),

                     '/comment/create' => array('inputs' => array(array('post_id' => 'int'),
                                                                  array('parent_comment_id' => 'int (optional)'),
                                                                  array('comment' => 'string'),
                                                                  array('user_id' => 'int (optional)'),
                                                                  array('username' => 'string (optional'),
                                                                  array('num_upvotes' => 'int (optional)'),
                                                                  array('num_downvotes' => 'int (optional)')),
                                                'outputs' => array(array('comment_id' => 'int'),
                                                                   array('comment_count' => 'int'),
                                                                   array('comment_html' => 'string'))),

                     '/post/vote' => array('inputs' => array(array('post_id' => 'int'),
                                                             array('user_id' => 'int (optional)'),
                                                             array('vote' => 'int')),
                                           'outputs' => array(array('vote_id' => 'int'),
                                                              array('score' => 'int'))),

                     '/posts/get' => array('inputs' => array(array('subin_slug' => 'string (optional)'),
                                                             array('tab' => 'string'),
                                                             array('page' => 'int')),
                                           'outputs' => array(array('subin_slug' => 'string'),
                                                              array('tab' => 'string'),
                                                              array('page' => 'int'))),

                     '/subins/popular' => array('inputs' => array(),
                                                'outputs' => array('popular' => 'array')),

                     '/subin/search' => array('inputs' => array('search_str' => 'string'),
                                              'outputs' => array('results' => 'array')),
                     );

    }

    public function user_username_exists($data) {
        return array('username_exists' => user::does_username_exist($data['username']));
    }

    public function user_is_fake_user($data) {
        return array('is_fake_user' => user::is_fake_user($data['username']));
    }

    public function user_create($data) {

        $is_fake_user = true;
        $user_id = user::create($data['username'], $data['password'], $data['name'], $data['email'], $is_fake_user);

        if (false === $user_id || $user_id == 0 || !ctype_digit((string) $user_id)) {
            return array('error' => $user_id);
        }

        return array('user_id' => $user_id);
    }

    public function post_create($data) {

        // set optional fields
        foreach (array('user_id', 'title', 'content', 'caption') as $field) {
            $data[$field] = checkreturn($data, $field);
        }

        // use the current timestamp if it doesn't exist
        $data['stamp'] = empty($data['stamp']) ? time() : (int) $data['stamp'];
        
        // get the userid from the username
        $data['user_id'] = user::getid($data['username']);

        // create the subin if it doesn't exist
        $subin_id = subin::create($data['subin_name'], $data['user_id']);

        // error check
        if (!ctype_digit((string) $subin_id)) {
            return array('post_id' => -1, 'error' => $subin_id);
        }

        // add the post to the particular subin
        $post_id = post::add($subin_id, $data['user_id'], $data['title'], $data['content'], $data['caption'], null, $data['stamp']);

        if (!ctype_digit((string) $post_id)) {
            return array('post_id' => -1, 'error' => $post_id);
        }

        // insert the upvotes
        for ($i=0; $i < $data['num_upvotes']; $i++) {
            vote::add($data['user_id'], $post_id, 0, 1);
        }

        // insert the downvotes
        for ($i=0; $i < $data['num_downvotes']; $i++) {
            vote::add($data['user_id'], $post_id, 0, -1);
        }

        // return the post id
        $post = array('post_id' => $post_id, 'title' => $data['title'], 'subin_slug' => subin::slug_from_subin_id($subin_id));
        return array('post_id' => $post_id, 'permalink' => absolutize(post::get_permalink($post)));

    }

    public function comment_create($data) {

        // use the current timestamp if it doesn't exist
        $data['stamp'] = (int) notempty($data, 'stamp', time());
        $data['parent_comment_id'] = (int) notempty($data, 'parent_comment_id', 0);
        $data['post_id'] = (int) notempty($data, 'post_id', 0);
        $data['comment'] = trim($data['comment']);

        // get the userid from the username if it exists
        $data['user_id'] = (!isset($data['user_id']) && !empty($data['username'])) ? user::getid($data['username']) : 0;
        $data['user_id'] = (int)$data['user_id'];

        // add the comment to the given post id
        if (empty($data['comment'])) {
            return array('comment_id' => -1, 'error' => 'empty comment body');
        } elseif (!empty($data['post_id']) && post::exists($data['post_id'])) {
            $comment_id = comment::add($data['user_id'], $data['post_id'], $data['parent_comment_id'], $data['comment']);
        } else {
            return array('comment_id' => -1, 'error' => 'invalid post id');
        }

        // insert the upvotes
        if (isset($data['num_upvotes']) && $data['num_upvotes'] > 0) {
            for ($i=0; $i < $data['num_upvotes']; $i++) {
                vote::add($data['user_id'], 0, $comment_id, 1);
            }
        }

        // insert the downvotes
        if (isset($data['num_downvotes']) && $data['num_downvotes'] > 0) {
            for ($i=0; $i < $data['num_downvotes']; $i++) {
                vote::add($data['user_id'], 0, $comment_id, -1);
            }
        }

        // return the comment id
        return array('comment_id' => $comment_id, 
                     'comment_count' => count(comment::get_all($data['post_id'])),
                     'comment_html' => $this->_buffer('posts/comment', array('comment_id' => $comment_id,
                                                                             'user_id' => $data['user_id'],
                                                                             'username' => user::get_username($data['user_id']),
                                                                             'content' => $data['comment'],
                                                                             'stamp' => $data['stamp'])));

    }

    public function post_vote($data) {
        
        $user_id = !empty($data['user_id']) ? (int)$data['user_id'] : 0;
        $post_id = !empty($data['post_id']) ? (int)$data['post_id'] : 0;
        $vote = isset($data['vote']) ? (int) $data['vote'] : 0;

        // check if vote is either -1 or 1
        if (abs($vote) !== 1) {
            return array('error' => 'vote is either missing or must be either -1 or 1');
        }

        // check if post exists
        if (empty($post_id) || !post::exists($post_id)) {
            return array('error' => 'invalid post id');
        }

        // check if already voted before
        if (!IS_DEV && !vote::can_vote_again($post_id)) {
            return array('error' => 'no more votes left for this post!', 'score' => vote::format_score(vote::get_score($post_id)));
        }

        $vote_id = vote::add($user_id, $post_id, 0, $vote);
        return array('post_id' => $post_id, 'vote_id' => $vote_id, 'score' => vote::format_score(vote::get_score($post_id)));

    }

    public function posts_get($data) {

        $subin_slug = notempty($data, 'subin_slug');
        $tab = notempty($data, 'tab', 'popular');
        $page = notempty($data, 'page', 1);

        // set default tab if not provided
        if (!in_array($tab, array_keys(post::$POST_TABS))) {
            $tab = 'popular';
        }

        $data = SubinController::get_matching_posts($subin_slug, $tab, $page);

        $html = $this->_buffer('posts/api', $data);
        return array('subin_slug' => $subin_slug,
                     'tab' => $tab,
                     'page' => $page,
                     'html' => trim($html));

    }

    public function subins_popular($data) {
        return array('popular' => subins::get_popular());
    }

    public function subin_search($data) {
        return array('results' => subin::search($data['search_str']));
    }

}