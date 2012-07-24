<?
    $this->_add_css('less/posts.less');
    $this->_add_js('js/posts.js');
?>

<? $t->block('content'); ?>
    <?
        $post = $data['posts'][0];
        include('partial/post.php');
    ?>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>