<?php
require_once('include/connection.php');
require_once('include/functions.php');
ob_start();
session_start();

$compared = sessionCookieCompare();
if ($compared == 1) {

    $username = $_COOKIE['userLoggedIn'];
    $hello = 'Bonjour '.$username;

    $sqlCurrentUser = "SELECT * FROM `users`
                       WHERE `username` = :username";
    $sth = $dbh->prepare($sqlCurrentUser);
    $sth->bindParam('username', $username, PDO::PARAM_STR);
    $sth->execute();
    $user = $sth->fetch(PDO::FETCH_ASSOC);

} else {
    $hello = "Vous n'êtes pas connecté!";
}

$sqlExistingUsers = "SELECT * FROM `users`";
$sth = $dbh->prepare($sqlExistingUsers);
$sth->execute();
$existingUsersArray = $sth->fetchAll(PDO::FETCH_ASSOC);

$sqlExistingProducts = "SELECT * FROM `produits`";
$sth = $dbh->prepare($sqlExistingProducts);
$sth->execute();
$existingProductsArray = $sth->fetchAll(PDO::FETCH_ASSOC);

if($_POST){
    $tabError = verifPost();
    var_dump($_POST);
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nitrocol | Dashboard</title>
    <style>
        table.customTable {
        width: 100%;
        background-color: #FFFFFF;
        border-collapse: collapse;
        border-width: 2px;
        border-color: black;
        border-style: solid;
        color: #000000;
        text-align: center;
        }

        table.customTable td, table.customTable th {
        border-width: 2px;
        border-color: black;
        border-style: solid;
        padding: 5px;
        }

        table.customTable thead {
        background-color: #7EA8F8;
        }
    </style>
</head>
<body>
    <h1><?= $hello?></h1>
    <?php if(isset($username)):?>

        <h3>Votre profil:</h3>
        <table class="customTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Admin</th>
                    <th>Image de profil</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php foreach($user as $key => $value):?>
                    <?php if($key == 'img'):?>
                        <td><img src="<?=$value?>" alt="" style='width: 150px; height: 100px'></td>
                    <?php else: ?>
                        <td><?= $value ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tr>
            </tbody>
        </table>

        <h3>Utilisateurs:</h3>
        <table class="customTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Admin</th>
                    <th>Image de profil</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($existingUsersArray as $existingUsers): ?>
                    <tr>
                        <?php foreach($existingUsers as $key => $value):?>
                            <?php if($key == 'img' && $value !== NULL):?>
                                <td><img src="<?=$value?>" alt="" style='width: 150px; height: 100px'></td>
                            <?php else: ?>
                                <td><?= $value ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>

        <h3>Produits:</h3>
        <table class="customTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Date de création</th>
                    <th>Avis</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($existingProductsArray as $existingProducts):?>
                    <tr>
                        <?php foreach($existingProducts as $key => $value):?>
                            <?php if($key == 'img'):?>
                                <td><img src="<?=$value?>" alt="" style='width: 150px; height: 100px'></td>
                            <?php else: ?>
                                <td><?= $value ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        
        <?php if(isset($tabError[0])):?>
            <p><?= $tabError[1]?>
        <?php endif; ?>
        <h3>Ajouter un utilisateur:</h3>
        <form action="<?= $_SERVER['PHP_SELF']?>" method="post">
        <fieldset style="display: grid; width: fit-content;">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" name="username" id="username">

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password">

            <label for="firstName">Prénom:</label>
            <input type="text" name="firstName" id="firstName">

            <label for="lastName">Nom:</label>
            <input type="text" name="lastName" id="lastName">

            <fieldset>
                <legend>Admin:</legend>
                <label for="admin">Oui</label>
                <input type="radio" name="admin" id="admin" value='Y'>

                <label for="admin">Non</label>
                <input type="radio" name="admin" id="admin" value="N">
            </fieldset>

            <label for="img">Photo de profil:</label>
            <input type="file" name="img">

            <input type="submit" value="Ajouter" style='width: 50%;display:flex; justify-content: center; margin-top: 25px; transform: translateX(50%)'>
        </fieldset>
        </form>



    <?php endif;?>
</body>
</html>