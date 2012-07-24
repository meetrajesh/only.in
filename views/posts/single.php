<? $t->block('content'); ?>
    <?
        $post = $data['posts'][0];
        include('partial/post.php');
    ?>

    <div class="comments-form cf">
        <h3>Post a new comment</h3>
        <textarea id="new-comment" rows="2" cols="64" placeholder="Enter comment here..."></textarea>
        <div class="btn" id="submit-comment"><span>Post Comment</span></div>
    </div>

    <div class="comments cf">
        <h3>Comments</h3>
        <? foreach ($data['comments'] as $comment) : ?>
            <? include('partial/comment.php'); ?>
        <? endforeach; ?>
    </div>
<? $t->endblock(); ?>

<? $this->_render('posts/base', $data); ?>