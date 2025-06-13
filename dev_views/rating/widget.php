<div class="rating-widget" data-project-id="<?= $project['id'] ?>">
    <div class="current-rating mb-2">
        <h6><?= t('Project Rating') ?></h6>
        <div class="d-flex align-items-center">
            <div class="stars me-2">
                <?php
                $avgRating = $projectRating['average'];
                for ($i = 1; $i <= 5; $i++) {
                    echo '<span class="star ' . ($i <= round($avgRating) ? 'active' : '') . '">★</span>';
                }
                ?>
            </div>
            <small>(<?= number_format($avgRating, 1) ?> / <?= $projectRating['count'] ?> <?= t('votes') ?>)</small>
        </div>
    </div>

    <?php if ($canRate): ?>
    <div class="rate-project">
        <h6><?= t('Rate This Project') ?></h6>
        <div class="rating-input">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <span class="star rate-star <?= ($userRating == $i ? 'active' : '') ?>" 
                  data-rating="<?= $i ?>">★</span>
            <?php endfor; ?>
        </div>
        <?php if ($userRating): ?>
        <button class="btn btn-sm btn-danger mt-2 remove-rating">
            <?= t('Remove Rating') ?>
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.star { cursor: pointer; font-size: 20px; color: #ddd; }
.star.active { color: #ffd700; }
.rate-star:hover, .rate-star:hover ~ .rate-star { color: #ffd700; }
</style>

<script>
document.querySelectorAll('.rating-widget').forEach(widget => {
    const projectId = widget.dataset.projectId;
    
    widget.querySelectorAll('.rate-star').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            fetch('/rating/rate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `project_id=${projectId}&rating=${rating}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    widget.querySelector('.remove-rating')?.addEventListener('click', function() {
        fetch('/rating/removeRating', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `project_id=${projectId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>
