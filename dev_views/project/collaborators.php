<!DOCTYPE html>
<html>
<head>
    <title><?= t('Project Collaborators') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5><?= t('Project Collaborators') ?>: <?= htmlspecialchars($project['name']) ?></h5>
            </div>
            <div class="card-body">
                <?php if ($isOwner): ?>
                    <form class="mb-4" id="inviteForm">
                        <div class="input-group">
                            <input type="text" class="form-control" name="username" 
                                   placeholder="<?= t('Enter username') ?>" required>
                            <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                            <button type="submit" class="btn btn-primary">
                                <?= t('Invite User') ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <h6><?= t('Current Collaborators') ?>:</h6>
                <ul class="list-group">
                    <?php foreach ($collaborators as $collaborator): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($collaborator['username']) ?>
                            <?php if ($collaborator['id'] === $currentUserId && !$isOwner): ?>
                                <button class="btn btn-sm btn-danger leave-project" 
                                        data-project-id="<?= $project['id'] ?>">
                                    <?= t('Leave Project') ?>
                                </button>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('inviteForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/collaboration/invite', {
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

        document.querySelectorAll('.leave-project').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('<?= t("Are you sure you want to leave this project?") ?>')) {
                    fetch('/collaboration/leave', {
                        method: 'POST',
                        body: new URLSearchParams({
                            project_id: this.dataset.projectId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.href = '/projects';
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
