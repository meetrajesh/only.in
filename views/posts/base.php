<?
    $this->_add_css('less/posts.less');

    $this->_add_js('js/posts.js')
?>

<? $t->block('content'); ?>
    <? foreach ($data['posts'] as $post): ?>
        <section class="post cf" data-id="<?= $post['post_id']; ?>">
            <h2>Only in <?=hsc(ucwords($post['subin_name']))?><?=hsc($t->notempty($post['title'], ': '))?></h2>
            <span class="post-meta">
                posted <?= ago($post['stamp']); ?>
                <? if (!empty($post['user_id'])) : ?>
                    by <?= hsc($post['user_id']); ?>
                <? endif; ?>
            </span>
            <? if (!empty($post['img_url'])) : ?>
                <div class="post-image"><img src="<?=hsc($post['img_url'])?>" alt=""></div>
            <? endif ?>
            <div class="post-votebox">
                <div class="post-upvote" role="button">Like</div>
                <div class="post-downvote" role="button">Dislike</div>
                <span>
                    <?= ($post['score'] < 0)?'-':($post['score'] > 0)?'+':''; ?><?= number_format($post['score']); ?>
                </span>
            </div>
        </section>
    <? endforeach; ?>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>