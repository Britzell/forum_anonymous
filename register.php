<?php
  require 'inc/bdd.php';
  require 'inc/header.php';
?>

  <form>
    <div class="form-group">
      <label for="firstname">Prénom :</label>
      <input type="text" class="form-control" name="firstname" placeholder="Prénom ...">
    </div>
    <div class="form-group">
      <label for="lastaname">Nom :</label>
      <input type="text" class="form-control" name="lastaname" placeholder="Nom ...">
    </div>
    <div class="form-group">
      <label for="login">Login :</label>
      <input type="text" class="form-control" name="login" placeholder="Login ...">
    </div>
    <div class="form-group">
      <label for="birthday">Date de naissance :</label>
      <input type="date" class="form-control" name="birthday">
    </div>
    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="text" class="form-control" name="password" placeholder="Mot de passe ...">
    </div>
    <div class="form-group">
      <label for="password_confirm">Confirmer votre mot de passe :</label>
      <input type="text" class="form-control" name="password_confirm" placeholder="Confirmer votre mot de passe ...">
    </div>
  </form>

<?php require 'inc/footer.php' ?>
