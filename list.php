<?php
  require 'inc/bdd.php';
  restrict($pdo);
  empty($_GET['id']) ? $idCategory = 0 : $idCategory = htmlspecialchars($_GET['id']);
  empty($_GET['p']) ? $p = 1 : $p = htmlspecialchars($_GET['p']);
  $topic = getTopic($pdo, $idCategory, $p*30-30);
  $hotTopic = hotTopic($pdo, $idCategory, 3);
  $lastPage = countTopic($pdo, false)/30+1;
  $lastPage = (int)$lastPage;
  empty($_GET['sort']) ? $_GET['sort'] = "" : "";

  if ($_GET['sort'] == "commentFirst") {
    $topic = getTopic($pdo, $idCategory, $p*30-30, "commentFirst");
    $header = "Premières activités";
  } elseif ($_GET['sort'] == "topicLast") {
    $topic = getTopic($pdo, $idCategory, $p*30-30, "topicLast");
    $header = "Dernières discussions créées";
  } elseif ($_GET['sort'] == "topicFirst") {
    $topic = getTopic($pdo, $idCategory, $p*30-30, "topicFirst");
    $header = "Premières discussions créées";
  } else {
    $topic = getTopic($pdo, $idCategory, $p*30-30);
    $header = "Dernières activités";
  }

  $category = getCategory($pdo);

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<div class="listTopic">

  <?php if ($topic[0]['id_topic'] !== 'z'): ?>
  <section class="firstTopic">
  <div>
      <h1 class="topicH1">Rechercher parmis les topics</h1>
    </div>
  <article class="search">
        <input type="text" id="search" placeholder="Rechercher un topic">
        <ul id="searchData" hidden></ul>
      </article>
    <div>
      <h1 class="topicH1" >Discussions les plus vues</h1>
    </div>

    <div class="cards">
      <?php foreach ($hotTopic as $k => $t): ?>
        <article class="card carHot<?= $k ?>">
          <div class="cardInfo-hover">
            <div class="cardClock-info">
              <svg class="cardClock"  viewBox="0 0 24 24"><path d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
              </svg><span class="cardTime"><?php $date = new DateTime($t['createAt']); echo "Le ".$date->format("d/m/Y à H:i"); ?></span>
            </div>

          </div>
          <div class="cardImg"></div>
          <a href="topic?id=<?= $t['id_topic'] ?>" class="card_link">
            <div class="cardImg--hover"></div>
          </a>
          <div class="cardInfo">
            <span class="cardCategory"><?= $category[$t['id_category']-1]['name'] ?></span>
            <h3 class="cardTitle"><?= $t['name'] ?></h3>
            <span class="cardBy">by <a href="account?id=<?= $t['id_user'] ?>" class="cardAuthor" title="author"><?= $t['login'] ?></a></span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sectionTopic">
    <div class="topicSort">
      <div>
        <h1 class="topicH1"><?= $header ?></h1>
        <?php if ($idCategory != 0): ?>
          <a href="list">Retour au forum</a>
        <?php endif; ?>
      </div>
      <div class="sort">
        <form class="" action="" method="get" id="sortForm">
          <select class="" name="sort" placeholder="Trier" id="sort">
            <!-- <option value="" disabled="disabled">Trier :</option>
            <option value="commentLast" <?= $_GET['sort'] == "commentLast" ? "selected='true'" : "" ?>>Dernières activités</option>
            <option value="commentFirst" <?= $_GET['sort'] == "commentFirst" ? "selected='true'" : "" ?>>Premières activités</option>
            <option value="topicLast" <?= $_GET['sort'] == "topicLast" ? "selected='true'" : "" ?>>Dernières discussions créées</option>
            <option value="topicFirst" <?= $_GET['sort'] == "topicFirst" ? "selected='true'" : "" ?>>Premières discussions créées</option> -->
            <option value="" disabled="disabled" selected="true">Trier :</option>
            <option value="commentLast">Dernières activités</option>
            <option value="commentFirst">Premières activités</option>
            <option value="topicLast">Dernières discussions créées</option>
            <option value="topicFirst">Premières discussions créées</option>
          </select>
        </form>
      </div>
    </div>
    <div class="cards">
      <?php foreach ($topic as $k => $t): ?>
        <article class="card car<?= $k ?>">
          <div class="cardInfo-hover">
            <div class="cardClock-info">
              <svg class="cardClock"  viewBox="0 0 24 24"><path d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
              </svg><span class="cardTime"><?php $date = new DateTime($t['createAt']); echo "Le ".$date->format("d/m/Y à H:i"); ?></span>
            </div>

          </div>
          <div class="cardImg"></div>
          <a href="topic?id=<?= $t['id_topic'] ?>" class="card_link">
            <div class="cardImg--hover"></div>
          </a>
          <div class="cardInfo">
            <span class="cardCategory"><?= $category[$t['id_category']-1]['name'] ?></span>
            <h3 class="cardTitle"><?= $t['name'] ?></h3>
            <span class="cardBy">by <a href="#" class="cardAuthor" title="author"><?= $t['login'] ?></a></span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sectionSearch">
    <div>
      <h1 class="topicH1">Liste des categories</h1>
    </div>
    <div class="shadowSearch">
      <article class="listCategory">
        <ul class="list-cat">
          <?php foreach ($category as $k => $c): ?>
            <li><a href="?id=<?= $c['id_category'] ?>"><?= $c['name'] ?></a></li>
          <?php endforeach; ?>
        </ul>
    </div>
  </section>

</div>

<style>
  <?php foreach ($topic as $k => $t): ?>
  .car<?= $k ?> .cardImg, .car<?= $k ?> .cardImg--hover {
    <?php if (file_exists("img/topic/".$t['id_topic'].".png")): ?>
    background-image: url('img/topic/<?= $t['id_topic'] ?>.png');
    <?php else: ?>
    background-image: url('img/topic.png');
    <?php endif; ?>
  }
  <?php endforeach; ?>

  <?php foreach ($hotTopic as $k => $t): ?>
  .carHot<?= $k ?> .cardImg, .carHot<?= $k ?> .cardImg--hover {
    <?php if (file_exists("img/topic/".$t['id_topic'].".png")): ?>
    background-image: url('img/topic/<?= $t['id_topic'] ?>.png');
    <?php else: ?>
    background-image: url('img/topic.png');
    <?php endif; ?>
  }
  <?php endforeach; ?>
</style>
<?php else: ?>
<section class="firstTopic">
  <div >
    <h1 class="topicH1">Aucune disctuion n'est reliée à cette catégorie.</h1>
  </div>

  </section>
<?php endif; ?>


<script type="text/javascript">
  $(document).ready(function(){
    $("#search").on('input', function postinput(){
      var value = $(this).val();
      if (value == '') {
        $('#searchData').empty();
      } else {
        $.ajax({
          url: 'json/search.php',
          data: { search: value },
          type: 'post'
        }).done(function(data) {
          // console.log('Done: ', data);
          $('#searchData').empty();
          $('#searchData').show("slow");
          $.each(JSON.parse(data),function(key, value) {
            if (value.name == "NULL") {
              $("#searchData").append('<li>Aucun Topic trouvé</li>');
            } else {
              $("#searchData").append('<li><a href="topic?id='+value.id_topic+'">'+value.name+'</a></li>');
            }
          });
        }).fail(function() {
          $('#searchData').empty();
          $('#searchData').show("slow");
          $("#searchData").append('<li>Aucun Topic trouvé</li>');
        });
      }
    });

    $( "#sort" ).change(function() {
      $('#sortForm').submit();
    });
  });

</script>
