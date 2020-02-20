<?php
  require 'inc/bdd.php';
  restrict($pdo);
  $error = 0;

  if (!empty($_GET['id']) && !empty($_GET['c'])) {
    $idTopic = htmlspecialchars($_GET['id']);
    $idComment = htmlspecialchars($_GET['c']);

    $cmt = getComment($pdo, $idTopic, $idComment);


    if ($cmt['id_user'] != $_SESSION['user']['id_user']) {
      die("Ce commentaire ne vous appartiens pas !");
    }

    if (!empty($_POST['edit'])) {
      $req = editComment($pdo, $idComment, $_POST['edit']);
      if ($req) {
        redirect("topic?id=$idTopic#c$idComment");
      } else {
        echo "Opération échouée.";
      }
    }

    $cmt = getComment($pdo, $idTopic, $idComment);


    if ($cmt == false) {
      $error = 1;
    }
  } else {
    $error = 1;
  }

  if ($error == 1) {
    echo "<p>Aucun commentaire trouvé.</p>";
    echo "<a href='home'>Retour</a>";
    exit();
  }
  require 'inc/header.php';
  require 'inc/navigation.php';
?>

<div class="contentSet">

<i class="fas fa-comments iconSet"></i> <br>
<h4>Editer votre sujet</h4>

</div>

<div class="formNewTopic">
<form class="settings" action="?id=<?= $idTopic ?>&c=<?= $idComment ?>" method="post">
  <div class="form-group">
    <label for="edit">Edit :</label>
    <textarea name="edit" cols="95" rows="10" required><?= $cmt['content'] ?></textarea>
  </div>
  <button type="submit" name="button">Valider</button>
</form>
</div>
