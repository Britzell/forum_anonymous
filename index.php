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
      $error = "Identifiants incorrects.";
    }
  }

  if (!empty($_GET['error'])) {
    if ($_GET['error'] == 1) {
      $error = "Vous êtes banni.";
    } elseif ($_GET['error'] == 2) {
      $error = "Vous n'avez pas le droit d'accéder à cette page.";
    }
  }

  require 'inc/header.php';
?>

<video id="code" src="code2.mp4" autoplay loop >


</video>


<div class="formulaireConnexion">
  <div class="formpart1">
    <img src="logo.gif" alt="">
  </div>

  <div class="formpart2">
    <h2>Bienvenue chez les Anonymous...</h2>
    <form action="" method="post">
      <div class="form-group">
        <label for="login">Email ou Login :</label> <br>
        <input type="text" class="form-control" name="login" placeholder="Email ou Login">
      </div>
      <div class="form-group">
        <label for="password">Mot de passe :</label> <br>
        <input type="password" class="form-control" name="password" placeholder="Mot de passe">
      </div>

      <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
      <?php endif; ?>

      <button type="submit" name="button">Login</button>
    </form>
  </div>


<p id="inscription" >Pas encore inscrit ? <br> <a href="register.php">Rejoins la communauté Anonymous</a></p>
</div>
<?php require 'inc/footer.php' ?>
