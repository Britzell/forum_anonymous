<?php
  require 'inc/bdd.php';
  restrict($pdo);
  $error = [];

  if (!empty($_POST['name']) && !empty($_POST['category']) && !empty($_POST['comment'])) {

    // Check if image file is a actual image or fake image
    if(isset($_FILES["topic"])) {
      $target_dir = "img/topic/";
      // $target_file = $target_dir . basename($_FILES["topic"]["name"]);
      $id = lastIdTopic($pdo)+1;
      $target_file = $target_dir . $id . ".png";
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      $check = getimagesize($_FILES["topic"]["tmp_name"]);
      if($check !== false) {
        //array_push($error, "Le fichier est une image - " . $check["mime"] . ".");
        $uploadOk = 1;
      } else {
        array_push($error, "Le fichier n'est pas une image.");
        $uploadOk = 0;
      }

      // Check if file already exists
      if (file_exists($target_file)) {
        $uploadOk = 0;
      }
      // Check file size
      if ($_FILES["topic"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["topic"]["tmp_name"], $target_file)) {
          array_push($error, "Le fichier ". basename( $_FILES["topic"]["name"]). " a été téléchargé.");
          $topic = createTopic($pdo, $_POST['name'], $_POST['category'], $_POST['comment']);
          if ($topic != true) {
            array_push($error, $topic);
          }
        } else {
          array_push($error, "Désolé, il y a eu une erreur lors du téléchargement de votre image.");
        }
      }
    }
  }

  $category = getCategory($pdo);

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<div class="contentSet">

<i class="fas fa-comments iconSet"></i> <br>
<h4>Nouveau sujet de discution</h4>

</div>

<<<<<<< HEAD
<div class="formNewTopic">
  <form class="settings" action="" method="post">
    <div class="form-group create">
=======
<div class="formSetTopic">
  <form class="settings" action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
>>>>>>> a8d66e361390e9e084f1b0d8f3aa120653c3e4d4
      <label for="name">Nom du topic :</label>
      <input type="text" name="name" required>
    </div>
    <div class="form-group create">
      <label for="category">Categorie :</label>
      <select class="" name="category" required>
        <?php foreach ($category as $c): ?>
          <option value="<?= $c['id_category'] ?>"><?= $c['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group create">
      <label for="comment">Contenu du topic :</label>
      <textarea name="comment" cols="70" rows="5" required></textarea>
    </div>
    <div class="form-group">
      <label for="topic">Image :</label> <br>
      <input type="file" name="topic" accept=".jpg,.png,.jpeg" required>
    </div>
    <?php foreach ($error as $e): ?>
      <p><?= $e ?></p>
    <?php endforeach; ?>
    <button type="submit" name="button">Créer</button>
  </form>
</div>
