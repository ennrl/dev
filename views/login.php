<div class="row justify-content-center">
    <div class="col-md-4">
        <form method="post">
            <h2><?= $t['login_title'] ?? 'Вход' ?></h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="<?= $t['username'] ?? 'Имя пользователя' ?>" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="<?= $t['password'] ?? 'Пароль' ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= $t['login'] ?? 'Войти' ?></button>
        </form>
    </div>
</div>
