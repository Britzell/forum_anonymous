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
 * getComment($pdo, $idTopic) Récupérer les commantaires du topic
 * getTopic($pdo) Récupérer tous les topics
 * getUser($pdo, $id_user) Récupérer données utilisateur
 * setUser($pdo, $idUser) Update données utilisateur
 * editComment($pdo, $idTopic, $idComment, $edit) Editer un commentaire
 * changePassword($pdo, $idUser, $password, $password_confirm) Changer mot de passe
 * countComment($pdo, $idTopic) Nombre de commaintaire dans ce topic
 * getRole($pdo, $n) Obtenir le role de l'uilisateur (alphanumérique)
 * getLastUserTopic($pdo, $idUser, $n) Récupérer les n dernier topic ou l'utilisateur à écrit un commentaire
 * topicIsset($pdo, $idTopic) Le topic exist ?
 * ----20----
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

  function restrict($nb = 1)
  {
    session_start();
    if (!isset($_SESSION['user']['id_role']) || $nb == 2 && $_SESSION['user']['id_role'] < 2 || $nb == 3 && $_SESSION['user']['id_role'] < 3) {
      redirect("logout");
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
      return true;
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

  function getComment($pdo, $idTopic, $idComment = false)
  {
    if ($idComment != false) {
      $comment = query($pdo, "SELECT comment.*, user.login FROM comment, user WHERE comment.id_comment = ? AND comment.id_user = user.id_user LIMIT 1", [$idComment])->fetch();
    } else {
      $comment = query($pdo, "SELECT comment.*, user.login FROM comment, user WHERE comment.id_topic = ? AND comment.id_user = user.id_user", [$idTopic])->fetchAll();
    }
    if (empty($comment)) {
      return false;
    }
    return $comment;
  }

  function getTopic($pdo, $idCategory)
  {
    $topic = query($pdo, "SELECT * FROM topic WHERE id_category = ?", [$idCategory])->fetchAll();

    if (empty($topic)) {
      $topic[0]['id_topic'] = "z";
      $topic[0]['name'] = "Aucun topic enregistré";
    }

    return $topic;
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

  function editComment($pdo, $idTopic, $idComment, $edit)
  {
    $edit = htmlspecialchars($edit);

    $req = query($pdo, "UPDATE comment SET edit = ?, updateAt = NOW() WHERE id_comment = ? AND id_topic = ?", [
      $edit,
      $idComment,
      $idTopic
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

  function getRole($pdo, $n)
  {
    $role = query($pdo, "SELECT name FROM role WHERE id_role = ?", [$n])->fetch();
    return $role['name'];
  }

  function getLastUserTopic($pdo, $idUser, $n)
  {
    $last = query($pdo, "SELECT DISTINCT comment.id_topic, comment.content, topic.name FROM comment, topic WHERE comment.id_user = ? AND comment.id_topic = topic.id_topic ORDER BY comment.id_comment DESC LIMIT $n", [$idUser])->fetchAll();
    return $last;
  }

  function topicIsset($pdo, $idTopic)
  {
    $req = query($pdo, "SELECT EXISTS (SELECT id_topic FROM topic WHERE id_topic = ?) AS exist;", [$idTopic])->fetch();
    return $req['exist'];
  }

?>
