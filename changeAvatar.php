<?php
  require 'inc/bdd.php';
  restrict($pdo);
  $error = array();

  // Check if image file is a actual image or fake image
  if(isset($_FILES["avatar"])) {
    $target_dir = "img/avatar/";
    // $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $target_file = $target_dir . $_SESSION['user']['id_user'] . ".png";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check !== false) {
      //array_push($error, "Le fichier est une image - " . $check["mime"] . ".");
      $uploadOk = 1;
    } else {
      array_push($error, "Le fichier n'est pas une image.");
      $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      rename($target_file, $target_dir . $_SESSION['user']['id_user'] . "_back.png");
      if (file_exists($target_file)) {
        $uploadOk = 0;
      }
    }
    // Check file size
    if ($_FILES["avatar"]["size"] > 500000) {
      array_push($error, "Désolé, votre image est trop volumineuse.");
      $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") { // && $imageFileType != "gif"
      array_push($error, "Désolé, seuls les fichiers JPG, JPEG et PNG sont autorisés.");
      $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      array_push($error, "Désolé, votre fichier n'a pas été téléchargé.");
      // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        array_push($error, "Le fichier ". basename( $_FILES["avatar"]["name"]). " a été téléchargé.");
        array_push($error, "Votre image de profil a bien été modifier.");
        unlink($target_dir . $_SESSION['user']['id_user'] . "_back.png");
      } else {
        array_push($error, "Désolé, il y a eu une erreur lors du téléchargement de votre image.");
        unlink($target_dir);
        rename($target_dir . $_SESSION['user']['id_user'] . "_back.png", $target_file);
      }
    }
  }

  require 'inc/header.php';
  require 'inc/navigation.php';
?>

<div class="contentSet">

<i class="fas fa-cogs iconSet"></i> <br>
<h4>Changer l'image de profil</h4>

</div>

<div class="formSet">
  <form class="settings" action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="avatar">Image :</label> <br>
      <input type="file" name="avatar" accept=".jpg,.png,.jpeg" required>
    </div>
    <?php foreach ($error as $e): ?>
      <p><?= $e ?></p>
    <?php endforeach; ?>
    <button type="submit" name="button">Valider</button>
  </form>
</div>
