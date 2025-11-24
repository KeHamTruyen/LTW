<?php
// $about expected
?>
<div class="row">
  <div class="col-md-8">
    <h1><?=htmlspecialchars($about['title'] ?? 'About')?></h1>
    <?php if (!empty($about['image'])): ?>
      <img src="<?=UPLOAD_URL . htmlspecialchars($about['image'])?>" alt="About image" class="img-fluid mb-3">
    <?php endif; ?>
    <div><?=nl2br(htmlspecialchars($about['description'] ?? ''))?></div>
    <h3>Mission</h3>
    <p><?=nl2br(htmlspecialchars($about['mission'] ?? ''))?></p>
    <h3>Vision</h3>
    <p><?=nl2br(htmlspecialchars($about['vision'] ?? ''))?></p>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <h5>Other</h5>
      <a href="?page=faq">View FAQs</a>
    </div>
  </div>
</div>
