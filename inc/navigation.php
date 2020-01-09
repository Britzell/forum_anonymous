<nav>
  <div class="logo">
      <a href="home.php"><img src="img/logo.png" alt=""></a>
  </div>
  <div class="naviga">
    <ul>
      <li><a href="list">Forum</a></li>
      <li><a href="createTopic">Créer un nouveau sujet de discution</a></li>
      <?php if (!empty($_SESSION['user']['id_role']) && $_SESSION['user']['id_role'] > 1): ?>
        <li><a href="createCategory">Créer une categorie</a></li>
      <?php endif; ?>
      <li><a href="account">Mon profil</a></li>
      <li><a href="settings">Paramètres</a></li>
      <li><a href="logout"><i class="fas fa-times-circle"></i></a></li>
    </ul>
  </div>
</nav>
