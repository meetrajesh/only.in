<? if (!isset($data['api'])):
     $this->_add_css('less/posts.less');
     $this->_add_js('js/posts.js');
   endif;
?>

<? $t->block('content'); ?>
    <? foreach ($data['posts'] as $post): ?>
        <? include 'partial/post.php'; ?>
    <? endforeach; ?>
<? $t->endblock(); ?>

<? if (!isset($data['api'])):
     $this->_render('base', $data);
   endif;
?>