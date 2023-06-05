<?php 

require_once('include/connection.php');

ob_start();
session_start();

if($_POST) {
    $username = $_POST['username'];
    $postPw = $_POST['password'];

    $sql = "SELECT username, `password` FROM `users`
    WHERE username = :username
    ";

    $sth = $dbh->prepare($sql);
    $sth->bindParam(':username', $username, PDO::PARAM_STR);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $result_nbr = $sth->rowCount();

    if(isset($result['password'])){
        $hashedPw = $result['password'];
    }else{
        $hashedPw = '';
    }
    
    if(empty($username) || empty($postPw)) {
        $msg_error = "Merci de rentrer vos identifiants";
    } else {
        if(password_verify($postPw, $hashedPw)) {           
            $_SESSION['username'] = $username;

            $expiration = time() + (30 * 60);
            $cookie_name = "userLoggedIn";
            $cookie_value = $username;
            setcookie($cookie_name, $cookie_value, $expiration, "/");

            header('Location: index.php');
            exit(); // Ajout d'un exit après la redirection
        } elseif($result_nbr < 1) {
            $msg_error = "Identifiant ou mot de passe incorrect";
        }else {
            $msg_error = "Mot de passe incorrect";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <?php if(isset($msg_error)):?>
        <p><?= $msg_error?></p>
    <?php endif; ?>
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" name="username" id="username">
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Connexion" id="adminConnect">
    </form>
</body>
</html>