<?php
  require 'inc/bdd.php';
  session_start();

  if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    redirect("home");
  }

  if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['login']) && !empty($_POST['email']) && !empty($_POST['birthday']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {
    $error = register(
      $pdo,
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['login'],
      $_POST['email'],
      $_POST['birthday'],
      $_POST['password'],
      $_POST['password_confirm']
    );

    if ($error == true) {
      redirect("index");
    } else {
      echo $error;
    }
  }

  require 'inc/header.php';
?>

<video id="code" src="code2.mp4" autoplay loop >


</video>


<div id="red">
  <div class="formpart1">
    <img src="logo.gif" alt="">
  </div>

  <div class="formpart2">
    <h2>Nous rejoindre...</h2>
    <form action="" method="POST">
      <div id="formulaireflex">
      <div class="part1">
        <div class="form-group">
          <label for="firstname">Prénom :</label> <br>
          <input type="text" class="form-control" name="firstname" placeholder="Prénom ..." required>
        </div>
        <div class="form-group">
          <label for="lastaname">Nom :</label> <br>
          <input type="text" class="form-control" name="lastname" placeholder="Nom ..." required>
        </div>
        <div class="form-group">
          <label for="birthday">Date de naissance :</label> <br>
          <input type="date" class="form-control" name="birthday" required>
        </div>
        <div class="form-group">
          <label for="login">Login :</label> <br>
          <input type="text" class="form-control" name="login" placeholder="Login ..." required>
        </div>
      </div>
      <div class="part2">
        <div class="form-group">
          <label for="email">Email :</label> <br>
          <input type="email" class="form-control" name="email" placeholder="Email ..." required>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe :</label> <br>
          <input type="password" class="form-control" name="password" placeholder="Mot de passe ..." required>
        </div>
        <div class="form-group">
          <label for="password_confirm">Confirmer votre mot de passe :</label> <br>
          <input type="password" class="form-control" name="password_confirm" placeholder="Confirmer votre mot de passe ..." required>
        </div>
      </div>
        </div>
          <button type="submit" name="button">Valider</button>
    </form>
  </div>
  <p id="inscription" >Déjà inscrit ? <br> <a href="index.php">Connecte toi ici</a></p>


<?php require 'inc/footer.php' ?>
