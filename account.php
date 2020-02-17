<?php
  require 'inc/bdd.php';
  restrict($pdo);
  empty($_GET['id']) ? $idUser = $_SESSION['user']['id_user'] : $idUser = htmlspecialchars($_GET['id']);

  $user = getUser($pdo, $idUser);
  $date = new DateTime($user['birthday']);

  $lastTopic = getLastUserTopic($pdo, $idUser, 5);
  $nCharacter = 128;
  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<p>Login : <?= $user['login'] ?></p>
<p>Role : <?= getRole($pdo, $user['id_role']) ?></p>

<?php if (file_exists("img/avatar/".$user['id_user'].".png")): ?>
  <img src="img/avatar/<?= $user['id_user'] ?>.png" alt="<?= $user['login'] ?> user logo">
<?php else: ?>
  <img src="img/default.png" alt="default user logo">
<?php endif; ?>

<?php foreach ($lastTopic as $k => $l): ?>
  <p><?= $l['name'] ?></p>
  <p><?= substr($l['content'], 0, $nCharacter) ?></p>
<?php endforeach;
?>
