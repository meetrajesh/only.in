<?
    $post = $data['posts'][0];
?>

<? if (!empty($post['title'])) : ?>
    <? $t->block('title'); ?>
        <?= hsc($post['title']) ?> | Only.in
    <? $t->endblock(); ?>
<? endif; ?>

<? $t->block('meta') ?>
    <meta property="og:image" content="<?= hsc($post['img_url']); ?>"/>
    <meta property="og:title" content="<?= hsc($t->notempty($post['title'], '', ' | ')); ?>Only in <?= hsc(ucwords($post['subin_name'])); ?>"/>
<? $t->endblock(); ?>

<? $t->block('content'); ?>
    <? include('partial/post.php'); ?>

    <div id="comments">
        <div class="comments-form cf">
            <h3>Post a new comment</h3>
            <textarea id="new-comment" rows="2" cols="64" placeholder="Enter comment here..."></textarea>
            <button class="btn" id="submit-comment"><span>Post Comment</span></button>
        </div>

        <div class="comments cf">
            <h3>Comments</h3>
            <? foreach ($data['comments'] as $comment) : ?>
                <? include('partial/comment.php'); ?>
            <? endforeach; ?>
        </div>
    </div>
<? $t->endblock(); ?>

<? $this->_render('posts/base', $data); ?>