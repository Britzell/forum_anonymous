<?php
  include '../bdd.php';

  try {
    $pdo = new PDO("mysql:dbname=$database_name;host=$host", $user, $pass);
  } catch (\Exception $e) {
    echo $e;
  }
?>
