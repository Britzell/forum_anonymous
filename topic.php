<?php
  require 'inc/bdd.php';
  restrict();
  $idTopic = htmlspecialchars($_GET['id']);

  $comment = getComment($pdo, $idTopic);

  if ($comment == false) {
    echo "<p>Ce topic n'existe pas.</p>";
    echo "<a href='home'>Retour</a>";
    exit();
  }

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<?php foreach ($comment as $k => $c): ?>
  <?php if ($c['enable'] == 1): ?>
    <?php if (file_exists("img/avatar/".$c['id_user'].".png")): ?>
      <img src="img/avatar/<?= $c['id_user'] ?>.png" alt="<?= $c['login'] ?> user logo">
    <?php else: ?>
      <img src="img/default.png" alt="default user logo">
    <?php endif; ?>
    <p><?= $c['login'] ?></p>
    <h4>Message :</h4>
    <p><?= $c['content'] ?></p>
    <p>Le <?php $date = new DateTime($c['createAt']); echo $date->format("d/m/y à H:i"); ?></p>
    <?php if (!empty($c['edit'])): ?>
      <h4>Edit :</h4>
      <p><?= $c['edit'] ?></p>
      <p>Le <?php $date = new DateTime($c['updateAt']); echo $date->format("d/m/y à H:i"); ?></p>
    <?php endif; ?>
    <?php if ($c['id_user'] == $_SESSION['user']['id_user']): ?>
      <a href="editComment?id=<?= $idTopic ?>&c=<?= $c['id_comment'] ?>&k=<?= $k ?>">Edit</a>
    <?php endif; ?>
    <?php if ($_SESSION['user']['id_role'] > 1): ?>
      <a href="deleteComment?id=<?= $idTopic ?>&c=<?= $c['id_comment'] ?>">Supprimer</a>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; ?>
