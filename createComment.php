<?php
  require 'inc/bdd.php';
  restrict();
  $idTopic = $_GET['id'];

  if (topicIsset($pdo, $idTopic) == 0) {
    echo "<p>Aucun topic trouvé.</p>";
    echo "<a href='home'>Retour</a>";
    exit();
  }

  if (!empty($_POST['comment'])) {
    $comment = createComment($pdo, $_POST['comment'], $idTopic, $_SESSION['user']['id_user']);
    if ($comment != true) {
      echo $comment;
    }
  }
?>


<form action="" method="post">
  <div class="form-group">
    <label for="comment">Commentaire :</label>
    <textarea name="comment" cols="70" rows="5" required></textarea>
  </div>
  <button type="submit" name="button">Créer</button>
</form>
