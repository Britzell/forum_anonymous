<?php
  require 'inc/bdd.php';
  empty($_GET['id']) ? $idCategory = 0 : $idCategory = htmlspecialchars($_GET['id']);
  empty($_GET['p']) ? $p = 1 : $p = htmlspecialchars($_GET['p']);
  $topic = getTopic($pdo, $idCategory, $p*30-30);
  $lastPage = countTopic($pdo, false)/30+1;
  $lastPage = (int)$lastPage;
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
    <?php foreach ($topic as $k => $t): ?>
      <tr>
        <td><?= $t['name'] ?></td>
        <td><?= countComment($pdo, $t['id_topic']) ?></td>
        <td><?= $t['view'] ?></td>
        <td><?php $date = new DateTime($t['activity']); echo $date->format("d/m/Y à H:i"); ?></td>
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
  });
</script>
