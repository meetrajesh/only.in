<? $t->block('content'); ?>
    <div class="page">
    <? foreach ($data['places'] as $place) : ?>
        <? $t->ifchanged(); ?>
            <h3><?= strtoupper(hsc(first($place['name']))); ?></h3>
        <? $t->endifchanged(); ?>
        <p><a href="<?= hsc(absolutize($place['permalink'])); ?>"><?= hsc($place['name']); ?></a></p>
    <? endforeach; ?>
    </div>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>