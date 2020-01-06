<?php
  require 'inc/bdd.php';
  session_start();
  
  if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    redirect("home");
  }

  if (!empty($_POST['login']) && !empty($_POST['password'])) {
    if (login($pdo, $_POST['login'], $_POST['password'])) {
      redirect("home");
    } else {
      echo "Identifiants incorrects.";
    }
  }

  require 'inc/header.php';
?>

  <form action="" method="post">
    <div class="form-group">
      <label for="login">Email ou Login :</label>
      <input type="text" class="form-control" name="login" placeholder="Email ou Login">
    </div>
    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="password" class="form-control" name="password" placeholder="Mot de passe">
    </div>
    <button type="submit" name="button">Login</button>
  </form>

<?php require 'inc/footer.php' ?>
