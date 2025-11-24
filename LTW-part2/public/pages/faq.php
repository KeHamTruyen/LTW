<?php
// $items, $total, $page, $limit, $q available in controller
?>
<div class="row">
  <div class="col-md-8">
    <h1>FAQ</h1>
    <form class="mb-3" method="get">
      <input type="hidden" name="page" value="faq">
      <div class="input-group">
        <input name="q" value="<?=htmlspecialchars($q ?? '')?>" class="form-control" placeholder="Search question or answer">
        <button class="btn btn-primary">Search</button>
      </div>
    </form>

    <div class="accordion" id="faqList">
    <?php foreach($items as $i => $it): ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?=$it['id']?>">
          <button class="accordion-button <?= $i? 'collapsed':''?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$it['id']?>">
            <?=htmlspecialchars($it['question'])?>
          </button>
        </h2>
        <div id="collapse<?=$it['id']?>" class="accordion-collapse collapse <?= $i? '':'show'?>" data-bs-parent="#faqList">
          <div class="accordion-body">
            <?=nl2br(htmlspecialchars($it['answer']))?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>

    <!-- pagination -->
    <?php
    $pages = max(1, ceil($total/($limit ?? 6)));
    for($p=1;$p<=$pages;$p++): ?>
      <a class="btn btn-sm <?= ($p==$page)?'btn-primary':'btn-outline-secondary'?>" href="?page=faq&p=<?=$p?>&q=<?=urlencode($q ?? '')?>"><?=$p?></a>
    <?php endfor; ?>
  </div>

  <div class="col-md-4">
    <div class="card p-3">
      <h5>Contact</h5>
      <p>Need help? Contact admin.</p>
    </div>
  </div>
</div>
