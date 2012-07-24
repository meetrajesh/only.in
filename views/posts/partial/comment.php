<div class="comment">
    <strong><?= (empty($comment['user_id']))?'anonymous':hsc($comment['username']); ?> said:</strong>
    <div><?= filter_text(hsc($comment['content'])); ?></div>
    <em><?= ago($comment['stamp']) ?></em>
</div>