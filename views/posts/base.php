<?
    $this->_add_css('less/posts.less');
?>

<? $t->block('content'); ?>
    <? foreach ($data['posts'] as $post): ?>
        <section class="post" data-id="<?= $post['post_id']; ?>">
            <h2>Only in <?=hsc(ucwords($post['subin_name']))?><?=hsc($t->notempty($post['title'], ': '))?></h2>
            <span class="post-meta">
                <? if (IS_DEV): ?>
                    id is <?=$post['post_id']?>,
                    <?=isset($post['rank']) ? 'rank is ' . $post['rank'] . ', ' : ''?>
                <? endif; ?>
                posted <?= ago($post['stamp']); ?>
                <? if (!empty($post['user_id'])) : ?>
                    by <?= hsc($post['user_id']); ?>
                <? endif; ?>
            </span>
            <? if (!empty($post['img_url'])) : ?>
                <div class="post-image"><img src="<?=hsc($post['img_url'])?>" alt=""></div>
            <? endif ?>
        </section>
    <? endforeach; ?>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>