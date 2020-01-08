<?php
  require 'bdd.php';
  $search = htmlspecialchars($_POST['search']);
  // $search = htmlspecialchars($_GET['search']);
  $data = query($pdo, "SELECT id_topic, name, enable FROM topic WHERE name LIKE '$search%' LIMIT 5")->fetchAll();
  if (count($data) > 0) {
    echo json_encode($data);
  } else {
    echo '[{"name":"NULL"}]';
  }
