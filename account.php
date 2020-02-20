<?php
  require 'inc/bdd.php';
  restrict($pdo);
  empty($_GET['id']) ? $idUser = $_SESSION['user']['id_user'] : $idUser = htmlspecialchars($_GET['id']);

  $user = getUser($pdo, $idUser);
  $date = new DateTime($user['birthday']);

  $lastTopic = getLastUserTopic($pdo, $idUser, 3);
  $nCharacter = 128;
  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<div class="contentSet">
<div class="account-content">
  <div class="img-account">
        <?php if (file_exists("img/avatar/".$user['id_user'].".png")): ?>
        <img src="img/avatar/<?= $user['id_user'] ?>.png" alt="<?= $user['login'] ?> user logo">
      <?php else: ?>
        <img src="img/default.png" alt="default user logo">
      <?php endif; ?>
  </div>
  <div class="text-account">
          <p> <strong> Login : </strong><?= $user['login'] ?></p>
        <p><strong>Rôle : </strong> <?= getRole($pdo, $user['id_role']) ?></p>

        <p><strong>Vos derniers sujets de discution postés sur le forum :</strong></p>
        <?php foreach ($lastTopic as $k => $l): ?>
          <p><?= $l['name'] ?></p>
          <p><?= substr($l['content'], 0, $nCharacter) ?></p>
        <?php endforeach;
        ?>
  </div>

</div>



</div>
