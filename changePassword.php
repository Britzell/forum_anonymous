<?php
  require 'inc/bdd.php';
  restrict();

  if (!empty($_POST['password']) && !empty($_POST['password_confirm'])) {
    $req = changePassword($pdo, $_SESSION['user']['id_user'], $_POST['password'], $_POST['password_confirm']);
    if ($req != true) {
      echo $req;
    }
  }

  $user = getUser($pdo, $_SESSION['user']['id_user']);


?>

<form action="" method="post">
  <div class="form-group">
    <label for="password">Mot de passe :</label>
    <input type="password" class="form-control" name="password" placeholder="Mot de passe ..." required>
  </div>
  <div class="form-group">
    <label for="password_confirm">Confirmer votre mot de passe :</label>
    <input type="password" class="form-control" name="password_confirm" placeholder="Confirmer votre mot de passe ..." required>
  </div>
  <button type="submit" name="button">Valider</button>
</form>
