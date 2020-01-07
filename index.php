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
      $ERROR= "Identifiants incorrects.";
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
    <h1>Bienvenue chez les Anonymous...</h1>
    <form action="" method="post">
      <div class="form-group">
        <label for="login">Email ou Login :</label> <br>
        <input type="text" class="form-control" name="login" placeholder="Email ou Login">
      </div>
      <div class="form-group">
        <label for="password">Mot de passe :</label> <br>
        <input type="password" class="form-control" name="password" placeholder="Mot de passe">
      </div>
      <button type="submit" name="button">Login</button>
    </form>
  </div>

<?php if (!empty($ERROR)): ?>
  <p class="error">Identifiants incorrects.</p>
<?php endif; ?>


<p id="inscription" >Pas encore inscrit ? <br> <a href="register.php">Rejoins la communauté Anonymous</a></p>
</div>
<?php require 'inc/footer.php' ?>
