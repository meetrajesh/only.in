<section class="post cf" data-id="<?= $post['post_id']; ?>">
    <h2><a href="<?= hsc(absolutize($post['permalink'])); ?>">Only in <?=hsc(ucwords($post['subin_name']))?><?=hsc($t->notempty($post['title'], ': '))?></a></h2>
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
    <div class="post-socialbox">
        <a href="<?= hsc(absolutize($post['permalink'])); ?>#comments" class="post-comment-btn btn" role="button">
            <span>Comments</span>
        </a>
        <a href="<?= hsc(absolutize($post['permalink'])); ?>#comments" class="post-comment-count"><?= $post['num_comments']; ?></a>
    </div>
</section>