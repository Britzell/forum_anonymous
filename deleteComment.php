<?php
  require 'inc/bdd.php';
  restrict($pdo, 2);
  $error = 0;

  if (!empty($_GET['id']) && !empty($_GET['c']) && $_SESSION['user']['id_role'] > 1) {
    $idTopic = htmlspecialchars($_GET['id']);
    $idComment = htmlspecialchars($_GET['c']);
    $nbComment = htmlspecialchars($_GET['k']);

    $req = deleteComment($pdo, $idTopic, $idComment);
    if ($req == true) {
      $nbComment--;
      redirect("topic?id=".$idTopic."#".$nbComment);
    } else {
      redirect("topic?id=".$idTopic."&e=1#".$nbComment);
    }

  } else {
    echo "<p>Aucun commentaire trouvé.</p>";
    echo "<a href='topic?id=".$idTopic."'>Retour</a>";
    exit();
  }
