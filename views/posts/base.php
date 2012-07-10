<?
    $this->_add_css('less/posts.less');
?>

<? $t->block('content'); ?>
    <? while ($row = $data['posts']->fetch_assoc()) : ?>
        <section class="post" data-id="<?= $row['post_id']; ?>">
            <h2>Only in <?=hsc(ucwords($row['subin_name']))?><?=hsc($t->notempty($row['title'], ': '))?></h2>
            <span class="post-meta">
                posted <?= ago($row['stamp']); ?>
                <? if (!empty($row['user_id'])) : ?>
                    by <?= hsc($row['user_id']); ?>
                <? endif; ?>
            </span>
            <? if (!empty($row['img_url'])) : ?>
                <div class="post-image"><img src="<?=hsc($row['img_url'])?>" alt=""></div>
            <? endif ?>
        </section>
    <? endwhile; ?>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>