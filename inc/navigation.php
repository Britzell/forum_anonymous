<header>
    <i class="fas fa-bars ouvre" onclick="ouvreMenu()"></i>
    <div class="logo">
      <img src="img/logo.png" alt="">
    </div>
    <nav>
      <i class="fas fa-times ferme" onclick="fermeMenu()"></i>
      <ul>
        <li><a href="topic?id=1">Forum</a></li>
        <li><a href="createTopic">Créer un nouveau sujet de discution</a></li>
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
