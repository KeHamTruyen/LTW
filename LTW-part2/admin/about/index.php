<?php
// $about, $_SESSION['csrf']
?>
<h2>Admin - About Page</h2>
<?php if(!empty($_SESSION['flash'])): ?><div class="alert alert-info"><?=htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']);?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'])?>">
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input name="title" class="form-control" value="<?=htmlspecialchars($about['title'] ?? '')?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="6"><?=htmlspecialchars($about['description'] ?? '')?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Mission</label>
    <textarea name="mission" class="form-control"><?=htmlspecialchars($about['mission'] ?? '')?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Vision</label>
    <textarea name="vision" class="form-control"><?=htmlspecialchars($about['vision'] ?? '')?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Image (jpg/png/webp, max 2MB)</label>
    <?php if (!empty($about['image'])): ?>
      <div><img src="<?=UPLOAD_URL . htmlspecialchars($about['image'])?>" style="max-width:200px"></div>
    <?php endif;?>
    <input type="file" name="image" class="form-control">
  </div>
  <button class="btn btn-primary">Save</button>
</form>
