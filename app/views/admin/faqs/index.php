<?php
// $items, $flash
?>
<h2>Admin - FAQ</h2>
<?php if(!empty($flash)): ?><div class="alert alert-info"><?=htmlspecialchars($flash)?></div><?php endif; ?>
<div class="mb-3">
  <a class="btn btn-success" href="?admin=1&page=faq_add">Add FAQ</a>
</div>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Question</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach($items as $it): ?>
    <tr>
      <td><?=$it['id']?></td>
      <td><?=htmlspecialchars($it['question'])?></td>
      <td>
        <a class="btn btn-sm btn-primary" href="?admin=1&page=faq_edit&id=<?=$it['id']?>">Edit</a>
        <a class="btn btn-sm btn-danger" href="?admin=1&page=faq_delete&id=<?=$it['id']?>" onclick="return confirm('Delete?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
