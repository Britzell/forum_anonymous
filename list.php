<?php
  require 'inc/bdd.php';
  restrict($pdo);
  empty($_GET['id']) ? $idCategory = 0 : $idCategory = htmlspecialchars($_GET['id']);
  empty($_GET['p']) ? $p = 1 : $p = htmlspecialchars($_GET['p']);
  $topic = getTopic($pdo, $idCategory, $p*30-30);
  $lastPage = countTopic($pdo, false)/30+1;
  $lastPage = (int)$lastPage;
  empty($_POST['sort']) ? $_POST['sort'] = "" : "";

  if ($_POST['sort'] == "commentFirst") {
    $topic = getTopic($pdo, $idCategory, $p*30-30, "commentFirst");
  } elseif ($_POST['sort'] == "topicLast") {
    $topic = getTopic($pdo, $idCategory, $p*30-30, "topicLast");
  } elseif ($_POST['sort'] == "topicFirst") {
    $topic = getTopic($pdo, $idCategory, $p*30-30, "topicFirst");
  } else {
    $topic = getTopic($pdo, $idCategory, $p*30-30);
  }

  require 'inc/header.php';
    require 'inc/navigation.php';
?>

<table>
  <thead>
    <tr>
      <th>Discution</th>
      <th>Réponse</th>
      <th>Vues</th>
      <th>Activité</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <form class="" action="" method="post" id="sortForm">
          <select class="" name="sort" placeholder="Trier" id="sort">
            <option value="" disabled="disabled">Trier</option>
            <option value="commentLast" <?= $_POST['sort'] == "commentLast" ? "selected='true'" : "" ?>>Dernière activité</option>
            <option value="commentFirst" <?= $_POST['sort'] == "commentFirst" ? "selected='true'" : "" ?>>Première activité</option>
            <option value="topicLast" <?= $_POST['sort'] == "topicLast" ? "selected='true'" : "" ?>>Dernières discution créés</option>
            <option value="topicFirst" <?= $_POST['sort'] == "topicFirst" ? "selected='true'" : "" ?>>Premières discution</option>
          </select>
        </form>
      </td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <?php foreach ($topic as $k => $t): ?>
      <tr>
        <td><a href="topic?id=<?= $t['id_topic'] ?>"><?= $t['name'] ?></a></td>
        <td><?= countComment($pdo, $t['id_topic']) ?></td>
        <td><?= $t['view'] ?></td>
        <td><?php $date = new DateTime($t['createAt']); echo $date->format("d/m/Y à H:i"); ?></td>
      </tr>
    <?php endforeach; ?>
</tbody>
</table>


<input type="text" id="search" placeholder="Rechercher un topic">
<ul id="searchData" hidden></ul>

<?php require 'inc/pagination.php'; ?>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#search").on('input', function postinput(){
      var value = $(this).val();
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
            $("#searchData").append('<li>'+value.name+'</li>');
          }
        });
      }).fail(function() {
        $('#searchData').empty();
        $('#searchData').show("slow");
        $("#searchData").append('<li>Aucun Topic trouvé</li>');
      });
    });

    $( "#sort" ).change(function() {
      $('#sortForm').submit();
    });
  });

</script>
