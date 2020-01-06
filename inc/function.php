<?php
/*
 * query($sql, $param) Requete SQL
 * d($var) Debug variable
 * register($pdo, $firstname, $lastname, $login, $birthday, $password)
 * login($pdo, $login, $password)
 * redirect($url) Redirection
 * restrict($nb = 1)  Restriction de la page, défaut tous ceux qui sont connectés, 1 -> User/Modo/Admin, 2 -> Modo/Admin, 3 -> Admin
 */

  function query($pdo, $sql, $param)
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

?>
