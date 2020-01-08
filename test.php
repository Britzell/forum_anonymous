<?php
  require 'inc/bdd.php';
  $idCategory = htmlspecialchars($_GET['id']);

  $topic = getTopic($pdo, $idCategory);
  require 'inc/pagination.php';
?>

<?php foreach ($topic as $k => $t): ?>
  <p>Nom : <?= $t['name'] ?></p>
  <p>Nombre de commantaire : <?= countComment($pdo, $t['id_topic']) ?></p>
<?php endforeach; ?>


<input type="text" id="search" placeholder="Rechercher un topic">
<ul id="searchData" hidden>

</ul>




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
