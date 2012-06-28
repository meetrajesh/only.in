<?
    $this->_enqueue_stylesheets('less/posts.less');
?>

<? $t->block('content'); ?>
    <? while ($row = $data['posts']->fetch_assoc()) : ?>
        <section class="post" data-id="<?= $row['post_id']; ?>">
            <h2></h2>
        </section>
    <?php endwhile; ?>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>