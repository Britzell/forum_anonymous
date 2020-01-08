<?php
  require 'inc/bdd.php';
  restrict();
  $error = 0;

  if (!empty($_GET['id']) && !empty($_GET['c'])) {
    $idTopic = htmlspecialchars($_GET['id']);
    $idComment = htmlspecialchars($_GET['c']);

    if (!empty($_POST['edit'])) {
      $req = editComment($pdo, $idTopic, $idComment, $_POST['edit']);
      if ($req == false) {
        echo "Opération échouée.";
      }
    }

    $comment = getComment($pdo, $idTopic, $idComment);

    if ($comment == false) {
      $error = 1;
    } elseif ($comment['id_user'] != $_SESSION['user']['id_user']) {
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

?>

<form action="?id=<?= $idTopic ?>&c=<?= $idComment ?>" method="post">
  <div class="form-group">
    <label for="edit">Edit :</label>
    <textarea name="edit" cols="70" rows="5" required></textarea>
  </div>
  <button type="submit" name="button">Valider</button>
</form>