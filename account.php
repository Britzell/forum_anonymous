<?php
  require 'inc/bdd.php';
  restrict($pdo);
  empty($_GET['id']) ? $idUser = $_SESSION['user']['id_user'] : $idUser = htmlspecialchars($_GET['id']);

  $user = getUser($pdo, $idUser);
  $date = new DateTime($user['birthday']);

  $lastTopic = getLastUserTopic($pdo, $idUser, 3);
  $nCharacter = 128;
  require 'inc/header.php';
  require 'inc/navigation.php';
?>

<div class="contentSet">
  <div class="account-content">
    <div class="img-account">
      <?php if (file_exists("img/avatar/".$user['id_user'].".png")): ?>
        <img src="img/avatar/<?= $user['id_user'] ?>.png?t=<?= time() ?>" alt="<?= $user['login'] ?> user logo">
      <?php else: ?>
        <img src="img/default.png" alt="default user logo">
      <?php endif; ?>
    </div>
    <div class="text-account">
      <p> <strong> Login : </strong><?= $user['login'] ?></p>
      <p><strong>Rôle : </strong> <?= getRole($pdo, $user['id_role']) ?></p>

      <p><strong>Vos derniers sujets de discution postés sur le forum :</strong></p>
    </div>

  </div>

</div>

<section class="firstTopic mt-100">
  <div class="topicH1">
    <h2>Derniers sujets de discution postés par <?= $user['login'] ?></h2>
  </div>

  <div class="cards">
    <?php foreach ($lastTopic as $k => $t): ?>
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
          <span class="cardCategory"><?= getNameCategory($pdo, $t['id_category']-1) ?></span>
          <h3 class="cardTitle"><?= $t['name'] ?></h3>
          <span class="cardBy">by <a href="account?id=<?= $_GET['id'] ?? $idUser ?>" class="cardAuthor" title="author"><?= $user['login'] ?? $idUser ?></a></span>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>


<style>
<?php foreach ($lastTopic as $k => $t): ?>
.car<?= $k ?> .cardImg, .car<?= $k ?> .cardImg--hover {
  <?php if (file_exists("img/topic/".$t['id_topic'].".png")): ?>
  background-image: url('img/topic/<?= $t['id_topic'] ?>.png');
  <?php else: ?>
  background-image: url('img/topic.png');
  <?php endif; ?>
}
<?php endforeach; ?>
</style>
