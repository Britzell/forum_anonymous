<?php
  require 'inc/bdd.php';
  restrict($pdo);

  if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['login']) && !empty($_POST['email']) && !empty($_POST['birthday'])) {
    setUser($pdo, $_SESSION['user']['id_user'], $_POST['firstname'], $_POST['lastname'], $_POST['login'], $_POST['email'], $_POST['birthday']);
  }

  $user = getUser($pdo, $_SESSION['user']['id_user']);
  require 'inc/header.php';
  require 'inc/navigation.php';

?>

<div class="contentSet">

<i class="fas fa-cogs iconSet"></i> <br>
<h4>Les paramètres de votre compte</h4>

</div>

<div class="formSet">
  <form class="settings" style="text-align:center;" action="" method="post">
    <div class="form-group">
      <label for="name">Prénom :</label> <br>
      <input type="text" name="firstname" value="<?= $user['firstname'] ?>" required>
    </div>
    <div class="form-group">
      <label for="name">Nom :</label> <br>
      <input type="text" name="lastname" value="<?= $user['lastname'] ?>" required>
    </div>
    <div class="form-group">
      <label for="name">Login :</label> <br>
      <input type="text" name="login" value="<?= $user['login'] ?>" required>
    </div>
    <div class="form-group">
      <label for="name">Email :</label> <br>
      <input type="email" name="email" value="<?= $user['email'] ?>" required>
    </div>
    <div class="form-group">
      <label for="name">Date d'anniversaire :</label> <br>
      <input type="date" name="birthday" value="<?= $user['birthday'] ?>" required>
    </div>
    <button type="submit" name="button">Valider</button>
  </form>

  <p class="changMDP"><a href="changePassword">Changer mot de passe</a></p>
  <p class="changMDP"><a href="changeAvatar">Changer l'image de profil</a></p>
</div>
