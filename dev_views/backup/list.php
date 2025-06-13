<!DOCTYPE html>
<html>
<head>
    <title><?= t('Project Backups') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= t('Project Backups') ?></h5>
                <button class="btn btn-primary create-backup" data-project-id="<?= $projectId ?>">
                    <?= t('Create Backup') ?>
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($backups)): ?>
                    <p class="text-muted"><?= t('No backups available') ?></p>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($backups as $backup): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div><?= date('d.m.Y H:i', $backup['created_at']) ?></div>
                                    <small class="text-muted"><?= $backup['name'] ?></small>
                                </div>
                                <button class="btn btn-sm btn-success restore-backup" 
                                        data-project-id="<?= $projectId ?>"
                                        data-backup-name="<?= $backup['name'] ?>">
                                    <?= t('Restore') ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.create-backup')?.addEventListener('click', function() {
            const projectId = this.dataset.projectId;
            fetch('/backup/create', {
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

        document.querySelectorAll('.restore-backup').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('<?= t("Are you sure you want to restore this backup?") ?>')) {
                    const projectId = this.dataset.projectId;
                    const backupName = this.dataset.backupName;
                    fetch('/backup/restore', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `project_id=${projectId}&backup_name=${backupName}`
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
</body>
</html>
