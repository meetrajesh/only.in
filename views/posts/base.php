<?
    $this->_add_css('less/posts.less');
    $this->_add_js('js/posts.js');
?>

<? $t->block('content'); ?>
    <? foreach ($data['posts'] as $post): ?>
        <? include 'partial/post.php'; ?>
    <? endforeach; ?>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>