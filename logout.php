<?php
  session_start();
  unset($_SESSION);
  session_destroy();
  if (isset($_GET['error'])) {
    header("Location: index?error=".htmlspecialchars($_GET['error']));
  } else {
    header("Location: index");
  }
