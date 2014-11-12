<?php $query = Database::query('select "id", "name" from "users"'); ?>
<ul>
<?php foreach($query as $row): ?>
  <li><a href="users/<?= $row->id ?>"><?= html($row->name) ?></a></li>
<?php endforeach; ?>
</ul>
