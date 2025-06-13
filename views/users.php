<h2><?= $t['user_management'] ?? 'Управление пользователями' ?></h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="post" class="mb-4">
    <div class="row">
        <div class="col">
            <input type="text" name="username" class="form-control" placeholder="<?= $t['username'] ?? 'Имя пользователя' ?>" required>
        </div>
        <div class="col">
            <input type="password" name="password" class="form-control" placeholder="<?= $t['password'] ?? 'Пароль' ?>" required>
        </div>
        <div class="col">
            <select name="role" class="form-control">
                <option value="user"><?= $t['role_user'] ?? 'Ученик' ?></option>
                <option value="admin"><?= $t['role_admin'] ?? 'Админ' ?></option>
            </select>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-success"><?= $t['add_user'] ?? 'Добавить' ?></button>
        </div>
    </div>
</form>
<table class="table">
    <thead>
        <tr>
            <th><?= $t['username'] ?? 'Имя пользователя' ?></th>
            <th><?= $t['role'] ?? 'Роль' ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
