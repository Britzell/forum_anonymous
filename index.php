<?php
  require 'inc/bdd.php';
  require 'inc/header.php';
?>

  <form>
    <div class="form-group">
      <label for="login">Login :</label>
      <input type="text" class="form-control" name="login" placeholder="Login ...">
    </div>
    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="text" class="form-control" name="password" placeholder="Mot de passe ...">
    </div>
  </form>

<?php require 'inc/footer.php' ?>
