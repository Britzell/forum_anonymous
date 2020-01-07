<?php
  require 'inc/bdd.php';
  restrict();

  if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['login']) && !empty($_POST['email']) && !empty($_POST['birthday'])) {
    setUser($pdo, $_SESSION['user']['id_user'], $_POST['firstname'], $_POST['lastname'], $_POST['login'], $_POST['email'], $_POST['birthday']);
  }

  $user = getUser($pdo, $_SESSION['user']['id_user']);


?>

<form action="" method="post">
  <div class="form-group">
    <label for="name">Prénom :</label>
    <input type="text" name="firstname" value="<?= $user['firstname'] ?>" required>
  </div>
  <div class="form-group">
    <label for="name">Nom :</label>
    <input type="text" name="lastname" value="<?= $user['lastname'] ?>" required>
  </div>
  <div class="form-group">
    <label for="name">Login :</label>
    <input type="text" name="login" value="<?= $user['login'] ?>" required>
  </div>
  <div class="form-group">
    <label for="name">Email :</label>
    <input type="email" name="email" value="<?= $user['email'] ?>" required>
  </div>
  <div class="form-group">
    <label for="name">Date d'anniversaire :</label>
    <input type="date" name="birthday" value="<?= $user['birthday'] ?>" required>
  </div>
  <button type="submit" name="button">Valider</button>
</form>

<a href="changePassword">Changer mot de passe</a>
