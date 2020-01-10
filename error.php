<?php

  if (isset($_GET)) {
    foreach ($_GET as $k => $e) {
      $error = htmlspecialchars($k);
      break;
    }
  }

  if ($error == "404") {
    $message = "Cette page n'exite pas.";
  }

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<div class="pageError">
  <h1><?= $error ?></h1>
  <p><?= !empty($message) ? $message : "" ?></p>
</div>
