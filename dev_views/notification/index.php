<!DOCTYPE html>
<html>
<head>
    <title><?= t('Notifications') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1><?= t('Notifications') ?></h1>

        <div class="notifications-list">
            <?php if (empty($notifications)): ?>
                <div class="alert alert-info">
                    <?= t('No notifications') ?>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="card mb-3 <?= $notification['read'] ? 'bg-light' : '' ?>">
                        <div class="card-body">
                            <?php switch($notification['type']): 
                                case 'new_task': ?>
                                    <h5 class="card-title"><?= t('New Task Available') ?></h5>
                                    <p><?= htmlspecialchars($notification['data']['task_title']) ?></p>
                                    <?php break; ?>
                                    
                                <?php case 'project_attached': ?>
                                    <h5 class="card-title"><?= t('Project Attached to Task') ?></h5>
                                    <p><?= t('Project') ?>: <?= htmlspecialchars($notification['data']['project_name']) ?></p>
                                    <?php break; ?>
                                    
                                <?php case 'project_modified': ?>
                                    <h5 class="card-title"><?= t('Project Modified') ?></h5>
                                    <p><?= t('File') ?>: <?= htmlspecialchars($notification['data']['filename']) ?></p>
                                    <?php break; ?>
                            <?php endswitch; ?>
                            
                            <div class="text-muted">
                                <?= date('d.m.Y H:i', $notification['created_at']) ?>
                            </div>
                            
                            <?php if (!$notification['read']): ?>
                                <button class="btn btn-sm btn-primary mt-2 mark-read" 
                                        data-notification-id="<?= $notification['id'] ?>">
                                    <?= t('Mark as Read') ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.querySelectorAll('.mark-read').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.dataset.notificationId;
                fetch('/notification/markRead', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'notification_id=' + notificationId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const card = this.closest('.card');
                        card.classList.add('bg-light');
                        this.remove();
                    }
                });
            });
        });
    </script>
</body>
</html>
