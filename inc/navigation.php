
<header>
    <i class="fas fa-bars ouvre" onclick="ouvreMenu()"></i>
    <div class="logo">
    <a href="home">  <img src="img/logo.png" alt=""></a>
    </div>
    <nav>
      <i class="fas fa-times ferme" onclick="fermeMenu()"></i>
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
    </nav>
  </header>

  <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
    <script>
      function ouvreMenu(){
        $("nav").addClass("menupresent")
      }
      function fermeMenu(){
        $("nav").removeClass("menupresent")
      }
    </script>
