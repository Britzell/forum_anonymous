<?php
/*
 * query($pdo, $sql, $param) Requete SQL
 * d($var) Debug variable
 * register($pdo, $firstname, $lastname, $login, $birthday, $password)
 * login($pdo, $login, $password)
 * redirect($url) Redirection
 * restrict($nb = 1)  Restriction de la page, défaut tous ceux qui sont connectés, 1 -> User/Modo/Admin, 2 -> Modo/Admin, 3 -> Admin
 * createCategory($pdo, $name, $description, $subCategory = NULL) Créer une categorie
 * createComment($pdo, $content, $idTopic, $idUser) Créer un commentaire
 * createTopic($pdo, $name, $category, $comment) Créer un topic
 * getCategory($pdo) Récupérer toutes les catégories
 * ----10----
 * getComment($pdo, $idTopic, $idComment = false, $limit = 0) Récupérer les commantaires du topic
 * getTopic($pdo, $idCategory = 0, $limit = 0, $sort = "commentLast") Récupérer les topics
 * getUser($pdo, $id_user) Récupérer données utilisateur
 * setUser($pdo, $idUser) Update données utilisateur
 * editComment($pdo, $idTopic, $idComment, $edit) Editer un commentaire
 * changePassword($pdo, $idUser, $password, $password_confirm) Changer mot de passe
 * countComment($pdo, $idTopic) Nombre de commaintaire dans ce topic
 * countTopic($pdo, $idCategory = false)
 * getRole($pdo, $n) Obtenir le role de l'uilisateur (alphanumérique)
 * getLastUserTopic($pdo, $idUser, $n) Récupérer les n dernier topic ou l'utilisateur à écrit un commentaire
 * ----20----
 * topicIsset($pdo, $idTopic) Le topic exist ?
 * getIp() Récupérer l'ip de l'utilisateur
 * addView($pdo, $idTopic) Ajouté un vue sur un topic, interval 20 minutes
 * hotTopic($pdo, $idCategory, $n) Topic les plus vue de la category (0 = All) et le nombre de résultat
 * getNameCategory($pdo, $id)
 * getContentCmt($pdo, $id)
 */

  function query($pdo, $sql, $param = [])
  {
    $req = $pdo->prepare($sql);
    $req->execute($param);
    return $req;
  }

  function d($var)
  {
    echo "<pre>".var_dump($var)."</pre>";
  }

  function register($pdo, $firstname, $lastname, $login, $email, $birthday, $password, $password_confirm)
  {
    $firstname = htmlspecialchars($firstname);
    $lastname = htmlspecialchars($lastname);
    $login = htmlspecialchars($login);
    $password = htmlspecialchars($password);
    $password_confirm = htmlspecialchars($password_confirm);
    $unique = query($pdo, "SELECT COUNT(id_user) FROM user WHERE login = ? LIMIT 1", [$login])->fetch();
    // Si le login est unique
    if ($unique['COUNT(id_user)'] == 0) {
      $unique = query($pdo, "SELECT COUNT(id_user) FROM user WHERE email = ? LIMIT 1", [$email])->fetch();
      // Si l'email est unique
      if ($unique['COUNT(id_user)'] == 0) {
        $date = new DateTime();
        $birthday = new DateTime($birthday);
        $diff = date_diff($date, $birthday);
        // Si l'age est de plus de 13 ans
        if ($diff->format('%y') >= 13) {
          // Si les mots de passe sont identique
          if ($password == $password_confirm) {
            $options = ['cost' => 12];
            $pass = password_hash($password, PASSWORD_BCRYPT, $options);

            $req = query($pdo, "INSERT INTO user (email, login, password, firstname, lastname, birthday, createdAt, enable, timeout, id_role) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)", [
              $email,
              $login,
              $pass,
              $firstname,
              $lastname,
              $birthday->format('Y-m-d'),
              1,
              NULL,
              1
            ]);
            if ($req) {
              return true;
            } else {
              return "Tentative échouée.";
            }
          } else {
            return "Les mots de passe ne correspondent pas.";
          }
        } else {
          return "Vous n'avez pas l'âge requie pour vous inscrire.";
        }
      } else {
        return "Email déjà prit.";
      }
    } else {
      return "Login déjà prit.";
    }
  }

  function login($pdo, $login, $password)
  {
    $login = htmlspecialchars($login);
    $password = htmlspecialchars($password);
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
      $user = query($pdo, "SELECT * FROM user WHERE email = ? LIMIT 1", [$login])->fetch();
    } else {
      $user = query($pdo, "SELECT * FROM user WHERE login = ? LIMIT 1", [$login])->fetch();
    }

    if (password_verify($password, $user['password'])) {
      session_start();
      $_SESSION['user'] = $user;
      return true;
    } else {
      return false;
    }
  }

  function redirect($url)
  {
    header("Location: ".$url);
    exit();
  }

  function restrict($pdo, $nb = 1)
  {
    if (empty($_SESSION['user'])) {
      session_start();
    }
    $_SESSION['user'] = query($pdo, "SELECT * FROM user WHERE id_user = ? LIMIT 1", [$_SESSION['user']['id_user']])->fetch();
    if ($_SESSION['user']['enable'] == 0) {
      redirect("logout?error=1");
    }
    if (!isset($_SESSION['user']['id_role']) || $nb == 2 && $_SESSION['user']['id_role'] < 2 || $nb == 3 && $_SESSION['user']['id_role'] < 3) {
      redirect("logout?error=2");
    }
  }

  function createCategory($pdo, $name, $description, $subCategory = NULL)
  {
    $name = htmlspecialchars($name);
    $description = htmlspecialchars($description);
    $subCategory = htmlspecialchars($subCategory);

    $unique = query($pdo, "SELECT COUNT(id_category) FROM category WHERE name = ? LIMIT 1", [$name])->fetch();
    if ($unique['COUNT(id_category)'] == 0) {
      $req = query($pdo, "INSERT INTO category (name, description, id_category_main) VALUES (?, ?, ?)", [
        $name,
        $description,
        $subCategory == NULL ? NULL : $subCategory
      ]);
      return true;
    } else {
      return "Nom déjà utilisé";
    }
  }

  function createComment($pdo, $content, $idTopic, $idUser)
  {
    $req = query($pdo, "INSERT INTO comment (content, enable, id_topic, id_user) VALUES (?, ?, ?, ?)", [
      $content,
      1,
      $idTopic,
      $idUser
    ]);

    if ($req) {
      return $pdo->lastInsertId();
    } else {
      return false;
    }
  }

  function createTopic($pdo, $name, $category, $comment)
  {
    $name = htmlspecialchars($name);
    $category = htmlspecialchars($category);
    $comment = htmlspecialchars($comment);
    if ($category == "z") {
      return "Aucune cartegory enregistrée";
    }

    $idTopic = query($pdo, "INSERT INTO topic (name, enable, id_category, id_user) VALUES (?, ?, ?, ?)", [
      $name,
      1,
      $category,
      $_SESSION['user']['id_user']
    ]);
    $idTopic = $pdo->lastInsertId();

    $req = createComment($pdo, $comment, $idTopic, $_SESSION['user']['id_user']);

    if ($req) {
      return true;
    } else {
      return false;
    }
  }

  function getCategory($pdo)
  {
    $category = query($pdo, "SELECT * FROM category")->fetchAll();

    if (empty($category)) {
      $category[0]['id_category'] = "z";
      $category[0]['name'] = "Aucune categorie enregistrée";
    }

    return $category;
  }

  // ----10----

  function getComment($pdo, $idTopic, $idComment = false, $limit = 0)
  {
    if ($idComment != false) {
      $comment = query($pdo, "SELECT comment.*, user.login FROM comment, user WHERE comment.id_comment = ? AND comment.id_user = user.id_user LIMIT 1", [$idComment])->fetch();
    } else {
      $comment = query($pdo, "SELECT comment.*, user.login FROM comment, user WHERE comment.id_topic = ? AND comment.id_user = user.id_user ORDER BY createAt ASC LIMIT $limit, 30", [$idTopic])->fetchAll();
    }
    if (empty($comment)) {
      return false;
    }
    return $comment;
  }

  function getTopic($pdo, $idCategory = 0, $limit = 0, $sort = "commentLast")
  {
    if ($idCategory == 0) {
      $topic = query($pdo, "SELECT topic.*, user.login FROM topic, user WHERE topic.id_user = user.id_user LIMIT $limit, 30", [$idCategory])->fetchAll();
      if ($sort == "commentLast") {
        $topic = query($pdo, "SELECT DISTINCT topic.*, comment.createAt AS activity FROM topic, comment WHERE topic.id_topic = comment.id_topic ORDER BY comment.createAt DESC LIMIT $limit, 30")->fetchAll();
      } elseif ($sort == "commentFirst") {
        $topic = query($pdo, "SELECT DISTINCT topic.*, comment.createAt AS activity FROM topic, comment WHERE topic.id_topic = comment.id_topic ORDER BY comment.createAt ASC LIMIT $limit, 30")->fetchAll();
      } elseif ($sort == "topicLast") {
        $topic = query($pdo, "SELECT DISTINCT topic.*, comment.createAt AS activity FROM topic, comment WHERE topic.id_topic = comment.id_topic ORDER BY topic.id_topic DESC LIMIT $limit, 30")->fetchAll();
      } else {
        $topic = query($pdo, "SELECT DISTINCT topic.*, comment.createAt AS activity FROM topic, comment WHERE topic.id_topic = comment.id_topic ORDER BY topic.id_topic ASC LIMIT $limit, 30")->fetchAll();
      }
    } else {
      if ($sort == "commentLast") {
        $topic = query($pdo, "SELECT topic.*, user.login, comment.createAt AS activity FROM topic, user, comment WHERE id_category = ? AND topic.id_user = user.id_user AND topic.id_topic = comment.id_topic ORDER BY comment.createAt DESC LIMIT $limit, 30", [$idCategory])->fetchAll();
      } elseif ($sort == "commentFirst") {
        $topic = query($pdo, "SELECT topic.*, user.login, comment.createAt AS activity FROM topic, user, comment WHERE id_category = ? AND topic.id_user = user.id_user AND topic.id_topic = comment.id_topic ORDER BY comment.createAt ASC LIMIT $limit, 30", [$idCategory])->fetchAll();
      } elseif ($sort == "topicLast") {
        $topic = query($pdo, "SELECT topic.*, user.login, comment.createAt AS activity FROM topic, user, comment WHERE id_category = ? AND topic.id_user = user.id_user AND topic.id_topic = comment.id_topic ORDER BY topic.id_topic DESC LIMIT $limit, 30", [$idCategory])->fetchAll();
      } else {
        $topic = query($pdo, "SELECT topic.*, user.login, comment.createAt AS activity FROM topic, user, comment WHERE id_category = ? AND topic.id_user = user.id_user AND topic.id_topic = comment.id_topic ORDER BY topic.id_topic ASC LIMIT $limit, 30", [$idCategory])->fetchAll();
      }
    }

    $tempo = [];
    if (empty($topic)) {
      $tempo[0]['id_topic'] = "z";
      $tempo[0]['name'] = "Aucun topic enregistré";
      $tempo[0]['view'] = "0";
      $tempo[0]['createAt'] = "";
    } else {
      foreach ($topic as $k => $t) {
        $z = 0;
        foreach ($tempo as $key => $val) {
          if ($t['id_topic'] == $val['id_topic']) {
            $z = 1;
          }
        }
        if ($z == 0) {
          $v = query($pdo, "SELECT COUNT(id_view) AS view FROM view WHERE id_topic = ?", [$t['id_topic']])->fetch();
          $t['login'] = getLogin($pdo, $t['id_user']);
          $t['view'] = $v['view'];
          array_push($tempo, $t);
        }
      }
    }
    return $tempo;
  }

  function getUser($pdo, $idUser)
  {
    $user = query($pdo, "SELECT * FROM user WHERE id_user = ? LIMIT 1", [$idUser])->fetch();
    return $user;
  }

  function setUser($pdo, $idUser, $firstname, $lastname, $login, $email, $birthday)
  {
    $user = query($pdo, "UPDATE user SET firstname = ?, lastname = ?, login = ?, email = ?, birstday = ? WHERE id_user = ? LIMIT 1", [
      $idUser,
      htmlspecialchars($firstname),
      htmlspecialchars($lastname),
      htmlspecialchars($login),
      htmlspecialchars($email),
      htmlspecialchars($birthday)
    ]);
    return $user;
  }

  function editComment($pdo, $idComment, $edit)
  {
    $req = query($pdo, "UPDATE comment SET content = ?, updateAt = NOW() WHERE id_comment = ?", [
      htmlspecialchars($edit),
      $idComment
    ]);

    if ($req) {
      return true;
    } else {
      return false;
    }
  }

  function deleteComment($pdo, $idTopic, $idComment)
  {
    $req = query($pdo, "UPDATE comment SET enable = 0 WHERE id_comment = ? AND id_topic = ?", [$idComment, $idTopic]);

    if ($req) {
      return true;
    } else {
      return false;
    }
  }

  function changePassword($pdo, $idUser, $password, $password_confirm)
  {
    $password = htmlspecialchars($password);
    $password_confirm = htmlspecialchars($password_confirm);
    if ($password == $password_confirm) {
      $options = ['cost' => '12'];
      $pass = password_hash($password, PASSWORD_BCRYPT, $options);
      $req = query($pdo, "UPDATE user SET password = ? WHERE id_user = ?", [$pass, $idUser]);
      return true;
    } else {
      return "Les mots de passe ne correspondent pas.";
    }
  }

  function countComment($pdo, $idTopic)
  {
    $count = query($pdo, "SELECT count(id_comment) FROM comment WHERE id_topic = ? AND enable = 1", [$idTopic])->fetch();
    return $count['count(id_comment)'];
  }

  function countTopic($pdo, $idCategory = false)
  {
    if ($idCategory == false) {
      $count = query($pdo, "SELECT count(id_topic) FROM topic WHERE enable = 1", [$idCategory])->fetch();
    } else {
      $count = query($pdo, "SELECT count(id_topic) FROM topic WHERE id_category = ? AND enable = 1", [$idCategory])->fetch();
    }
    return $count['count(id_topic)'];
  }

  function getRole($pdo, $n)
  {
    $role = query($pdo, "SELECT name FROM role WHERE id_role = ?", [$n])->fetch();
    return $role['name'];
  }

  function getLastUserTopic($pdo, $idUser, $n)
  {
    $last = query($pdo, "SELECT topic.* FROM topic WHERE id_user = ? ORDER BY createAt DESC LIMIT $n", [$idUser])->fetchAll();
    return $last;
  }

  // ----20----

  function topicIsset($pdo, $idTopic)
  {
    $req = query($pdo, "SELECT EXISTS (SELECT id_topic FROM topic WHERE id_topic = ?) AS exist;", [$idTopic])->fetch();
    return $req['exist'];
  }

  function getIp() {
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
    }
  }

  function addView($pdo, $idTopic)
  {
    $ip = getIp();
    $count = query($pdo, "SELECT COUNT(id_view) AS c FROM view WHERE ip = ? AND id_topic = ? AND date BETWEEN DATE_SUB(NOW() , INTERVAL 20 MINUTE) AND NOW()", [$ip, $idTopic])->fetch();
    $count['c'] == 0 ? query($pdo, "INSERT INTO view(ip, id_topic) VALUES (?, ?)", [$ip, $idTopic]) : "";
  }

  function getLogin($pdo, $idUser)
  {
    $user = query($pdo, "SELECT login FROM user WHERE id_user = ? LIMIT 1", [$idUser])->fetch();
    return $user['login'];
  }

  function lastIdTopic($pdo)
  {
    $l = query($pdo, "SELECT id_topic FROM topic ORDER BY id_topic DESC LIMIT 1")->fetch();
    return $l['id_topic'];
  }

  function hotTopic($pdo, $idCategory, $n)
  {
    if ($idCategory) {
      $top = query($pdo, "SELECT id_topic, count(id_topic) FROM view GROUP BY id_topic ORDER BY count(id_topic) DESC LIMIT $n")->fetchAll();
    } else {
      $top = query($pdo, "SELECT id_topic, count(id_topic) FROM view GROUP BY id_topic ORDER BY count(id_topic) DESC LIMIT $n")->fetchAll();
    }

    $topic = [];

    foreach ($top as $k => $t) {
      array_push($topic, query($pdo, "SELECT topic.*, user.login, comment.createAt AS activity FROM topic, user, comment WHERE topic.id_topic = ? AND topic.id_user = user.id_user AND topic.id_topic = comment.id_topic LIMIT 1", [$t['id_topic']])->fetch());
    }

    $tempo = [];
    if (empty($topic)) {
      $tempo[0]['id_topic'] = "z";
      $tempo[0]['name'] = "Aucun topic enregistré";
      $tempo[0]['view'] = "0";
      $tempo[0]['createAt'] = "";
    } else {
      foreach ($topic as $k => $t) {
        $z = 0;
        foreach ($tempo as $key => $val) {
          if ($t['id_topic'] == $val['id_topic']) {
            $z = 1;
          }
        }
        if ($z == 0) {
          $v = query($pdo, "SELECT COUNT(id_view) AS view FROM view WHERE id_topic = ?", [$t['id_topic']])->fetch();
          $t['login'] = getLogin($pdo, $t['id_user']);
          $t['view'] = $v['view'];
          array_push($tempo, $t);
        }
      }
    }
    return $tempo;
  }

  function getNameCategory($pdo, $id)
  {
    $name = query($pdo, "SELECT name FROM category WHERE id = ?", [$id])->fetch();
    return $name['name'];
  }

  function getContentCmt($pdo, $id)
  {
    return query($pdo, "SELECT * FROM comment WHERE id_comment = ?", [$id])->fetch();
  }
?>
