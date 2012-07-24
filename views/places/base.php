<? $t->block('content'); ?>
    <div class="page">
    <? foreach ($data['places'] as $place) : ?>
        <? $t->ifchanged(); ?>
            <h3><?= strtoupper(hsc(substr($place['name'], 0, 1))); ?></h3>
        <? $t->endifchanged(); ?>
        <p><a href="<?= hsc(absolutize($place['permalink'])); ?>"><?= hsc($place['name']); ?></a></p>
    <? endforeach; ?>
    </div>
<? $t->endblock(); ?>

<? $this->_render('base', $data); ?>