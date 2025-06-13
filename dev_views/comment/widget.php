<div class="comments-widget" data-project-id="<?= $projectId ?>">
    <h5><?= t('Comments') ?></h5>
    
    <div class="comments-list mb-3">
        <?php foreach ($comments as $comment): ?>
            <div class="card mb-2" data-comment-id="<?= $comment['id'] ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <?= date('d.m.Y H:i', $comment['created_at']) ?>
                            <?php if ($comment['filename']): ?>
                                | <?= htmlspecialchars($comment['filename']) ?>
                            <?php endif; ?>
                        </small>
                        <?php if ($comment['user_id'] === $currentUserId): ?>
                            <button class="btn btn-sm btn-danger delete-comment">
                                <?= t('Delete') ?>
                            </button>
                        <?php endif; ?>
                    </div>
                    <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($comment['text'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form class="add-comment-form">
        <input type="hidden" name="project_id" value="<?= $projectId ?>">
        <div class="mb-3">
            <textarea name="text" class="form-control" rows="3" required 
                      placeholder="<?= t('Add a comment...') ?>"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= t('Submit') ?></button>
    </form>
</div>

<script>
document.querySelector('.add-comment-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/comment/add', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});

document.querySelectorAll('.delete-comment').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.closest('.card').dataset.commentId;
        
        if (confirm('<?= t("Delete this comment?") ?>')) {
            fetch('/comment/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
});
</script>
