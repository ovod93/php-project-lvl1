<form action="/users" method="get">
    <input type="search" name="term" value="<?= htmlspecialchars($term) ?>">
    <input type="submit" value="Search" />
</form>

<?php foreach ($users as $user): ?>
    <div>
        <?= htmlspecialchars($user['firstName']) ?>
    </div>
<?php endforeach ?>