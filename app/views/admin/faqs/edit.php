<?php
// $item, $_SESSION['csrf']
?>
<h2>Edit FAQ</h2>
<form method="post">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
  <div class="mb-3">
    <label class="form-label">Question</label>
    <input name="question" class="form-control" value="<?=htmlspecialchars($item['question'])?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Answer</label>
    <textarea name="answer" class="form-control" rows="6" required><?=htmlspecialchars($item['answer'])?></textarea>
  </div>
  <button class="btn btn-primary">Save</button>
  <a class="btn btn-secondary" href="?admin=1&page=faq">Cancel</a>
</form>
