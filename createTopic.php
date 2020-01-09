<?php
  require 'inc/bdd.php';
  restrict($pdo);

  if (!empty($_POST['name']) && !empty($_POST['category']) && !empty($_POST['comment'])) {
    $topic = createTopic($pdo, $_POST['name'], $_POST['category'], $_POST['comment']);
    if ($topic != true) {
      echo $topic;
    }
  }

  $category = getCategory($pdo);

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<div class="contentSet">

<i class="fas fa-comments iconSet"></i> <br>
<h4>Nouveau sujet de discution</h4>

</div>

<div class="formSetTopic">
  <form class="settings" action="" method="post">
    <div class="form-group">
      <label for="name">Nom du topic :</label>
      <input type="text" name="name" required>
    </div>
    <div class="form-group">
      <label for="category">Categorie :</label>
      <select class="" name="category" required>
        <?php foreach ($category as $c): ?>
          <option value="<?= $c['id_category'] ?>"><?= $c['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="comment">Commentaire</label>
      <textarea name="comment" cols="70" rows="5" required></textarea>
    </div>
    <button type="submit" name="button">Créer</button>
  </form>
</div>
