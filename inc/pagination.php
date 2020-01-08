<?php
  empty($_GET['p']) ? $p = 1 : $p = $_GET['p'];
  $p == 1 ? $np = 5 : ($p == 2 ? $np = 4 : $np = 3);
  $firstGet = 0;
  foreach ($_GET as $k => $g) {
    $firstGet == 0 ? $url = "?" : $url .= "&";
    $k != "p" ? $url .= $k."=".$g : $url .= $k."=";
  }
  $countComment = countComment($pdo, 1);
?>
<nav>
  <ul class="">
    <li class="">
      <a class="" href="<?= $p == 1 ? $url."1" : $url.($p-1) ?>">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php if ($p > 2): ?>
      <?php for ($i=$p-2; $i < $p; $i++): ?>
        <li class=""><a class="" href="<?= $url.$i ?>"><?= $i ?></a></li>
      <?php endfor; ?>
    <?php elseif ($p == 2): ?>
      <li class=""><a class="" href="<?= $url."1" ?>">1</a></li>
    <?php endif; ?>
    <li class=" active"><a class="" href="<?= $url.$p ?>"><?= $p ?></a></li>
    <?php for ($i=$p+1; $i < $p+$np; $i++): ?>
      <li class=""><a class="" href="<?= $url.$i ?>"><?= $i ?></a></li>
    <?php endfor; ?>
    <li class="">
      <a class="" href="<?= $url.++$p ?>">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
