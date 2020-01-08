<?php
  require '../../bdd.php';

  try {
    $pdo = new PDO("mysql:dbname=$database_name;host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    echo $e;
  }

  require '../inc/function.php';
?>
