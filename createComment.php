<?php
  restrict($pdo);
  if (!empty($_POST)) {
    $idTopic = htmlspecialchars($_POST['id']);

    if (!empty($_POST['comment'])) {
      $cmt = createComment($pdo, $_POST['comment'], $idTopic, $_SESSION['user']['id_user']);
      ?>
        <script type="text/javascript">
          window.location.href = "<?= $_SESSION['REQUEST_SCHEME'] ?>://<?= $_SESSION['HTTP_HOST'] ?>/topic?id=<?= $idTopic ?>#cmt<?= $cmt ?>";
        </script>
      <?php
    }
  }
?>


<form action="" method="post">
  <div class="form-group">
    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
    <label for="comment">Votre commentaire :</label> <br>
    <textarea name="comment" cols="70" rows="5" required></textarea>
  </div>
  <button type="submit" name="button">Créer</button>
</form>
