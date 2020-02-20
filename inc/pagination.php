<?php
  empty($_GET['p']) ? $p = 1 : $p = $_GET['p'];
  !isset($lastPage) ? "Variable lastPage manquante" : "";
  $p == 1 ? $np = 5 : ($p == 2 ? $np = 4 : $np = 3);
  $firstGet = 0;
  $url = "";
  foreach ($_GET as $k => $g) {
    $firstGet == 0 ? $url = "?" : $url .= "&";
    $firstGet = 1;
    $k != "p" ? $url .= $k."=".$g : "";
  }
  isset($_GET['p']) ? $url .= "p=" : (empty($_GET) ? $url .= "?p=" : $url .= "&p=");
  $p == $lastPage ? ($p > 3 ? $lp = 3 : $lp = 2) : $lp = 2;
  // &laquo; &raquo;

?>
  <ul class="pagination">
    <li class="">
      <a class="" href="<?= $url."1" ?>">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li class="">
      <a class="" href="<?= $p == 1 ? $url."1" : $url.($p-1) ?>">
        <span aria-hidden="true">&lsaquo;</span>
      </a>
    </li>
    <?php if ($p > 2): ?>
      <?php for ($i=$p-$lp; $i < $p; $i++): ?>
        <li class=""><a class="" href="<?= $url.$i ?>"><?= $i ?></a></li>
      <?php endfor; ?>
    <?php elseif ($p == 2): ?>
      <li class=""><a class="" href="<?= $url."1" ?>">1</a></li>
    <?php endif; ?>
    <li class=" active"><a class="" href="<?= $url.$p ?>"><?= $p ?></a></li>
    <?php for ($i=$p+1; $i < $p+3; $i++): ?>
      <?php if ($i <= $lastPage): ?>
        <li class=""><a class="" href="<?= $url.$i ?>"><?= $i ?></a></li>
      <?php endif; ?>
    <?php endfor; ?>
    <li class="">
      <a class="" href="<?= $p >= (int)$lastPage ? $url.$lastPage : $url.++$p ?>">
        <span aria-hidden="true">&rsaquo;</span>
      </a>
    </li>
    <li class="">
      <a class="" href="<?= $url.$lastPage ?>">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
