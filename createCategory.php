<?php
  require 'inc/bdd.php';
  restrict($pdo);

  if (!empty($_POST['name']) && !empty($_POST['description'])) {
    $category = createCategory($pdo, $_POST['name'], $_POST['description']);
    if ($category != true) {
      echo $category;
    }
  }

  $category = getCategory($pdo);

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<div class="contentSet">

<i class="fas fa-american-sign-language-interpreting iconSet"></i> <br>
<h4>Nouvelle catégorie</h4>

</div>

<div class="formNewTopic">
  <form class="settings" action="" method="post">
    <div class="form-group create">
      <label for="name">Nom de la catégorie :</label>
      <input type="text" name="name" required>
    </div>
    <div class="form-group create">
      <label for="category">Sous catégorie :</label>
      <select class="" name="subcategory" required>
        <option value="0">Cette catégorie n'est pas une sous-catégorie</option>
        <?php foreach ($category as $c): ?>
          <option value="<?= $c['id_category'] ?>" <?= $c['id_category'] == "z" ? "disabled" : "" ?>><?= $c['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group create">
      <label for="description">Description :</label>
      <textarea name="description" cols="70" rows="5" required></textarea>
    </div>
    <button type="submit" name="button">Créer</button>
  </form>
</div>
