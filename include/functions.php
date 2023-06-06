<?php 
require_once('connection.php');

    function sessionCookieCompare(): bool {
        if(isset($_COOKIE['userLoggedIn']) && isset($_SESSION['username'])){
            $cookieValue = $_COOKIE['userLoggedIn'];
            $sessionValue = $_SESSION['username'];
        }else{
            $sessionValue = '1';
            $cookieValue = '2';
            $compared = false;
        }
    
        if($cookieValue === $sessionValue){
            $compared = true;
        }else {
            $compared = false;
        }
    return $compared;
    }

    function verifPost($dbh){
        if(isset($_POST['username'])) {
            $errorMsg = "Veuillez remplir les champ(s) suivant(s):<br>";
            foreach($_POST as $key => $value) {
                ${$key} = $value;

                if(empty($value) && $key !== 'img') {
                    switch($key) {
                        case 'username':
                            $key = 'Nom d\'utilisateur';
                            break;
                        case 'password':
                            $key = 'Mot de passe';
                            break;
                        case 'firstName':
                            $key = 'Prénom';
                            break;
                        case 'lastName':
                            $key = 'Nom';
                            break;
                    }
                    $errorMsg .= $key;
                    $errorMsg .= '<br>';
                    $error = true;
                }
            }

            if(empty($admin)) {
                $errorMsg .= 'Admin<br>';
                $error = true;
            }

            if(empty($_FILES['imgPost']['tmp_name'])) {
                $error = true;
                $errorMsg .= 'Image';
            } else {
                if(!file_exists('img/users/')) {
                    $upload = mkdir('img/users/', 0777);
                    if(!$upload) {
                        $error = true;
                        die("Dossier d'upload non créé");
                        $errorMsg = "Dossier d'upload non créé, veuillez réessayer.<br>";
                    }
                }else {
                    $upload = 'img/users/'.$_FILES['imgPost']['name'];
                    $move = move_uploaded_file($_FILES['imgPost']['tmp_name'], $upload);
                    if(!$move) {
                        $errorMsg = 'Problème de téléchargement';
                    }
                }
            }

            if(empty($error)){
                $sqlNewUserPost = "INSERT INTO `users` (`username`, `password`, `f_name`, `l_name`, `admin`, `img`)
                            VALUES (:username, :password, :f_name, :l_name, :admin, :img)";
                $sth = $dbh->prepare($sqlNewUserPost);

                $sth->bindParam(':username', $username, PDO::PARAM_STR);

                $password = password_hash($password, PASSWORD_DEFAULT);

                $sth->bindParam(':password', $password, PDO::PARAM_STR);
                $sth->bindParam(':f_name', $firstName, PDO::PARAM_STR);
                $sth->bindParam(':l_name', $lastName, PDO::PARAM_STR);
                $sth->bindParam(':admin', $admin, PDO::PARAM_STR);
                $sth->bindParam(':img', $upload, PDO::PARAM_STR);

                $sth->execute();
                header('Location: index.php');
            }else{
                $tabError = [$error, $errorMsg];
                return $tabError;
            }

        }else {

            $errorMsg = "Veuillez remplir les champ(s) suivant(s):<br>";
            foreach($_POST as $key => $value){
                ${$key} = $value;

                if(empty($value) && $key !== 'img' && $key !== 'dateCreation') {
                    switch($key) {
                        case 'title':
                            $key = 'Titre';
                            break;
                        case 'description':
                            $key = 'Description';
                            break;
                        case 'avis':
                            $key = 'Avis';
                            break;
                        case 'price':
                            $key = 'Prix';
                            break;
                    }
                    $errorMsg .= '-';
                    $errorMsg .= $key;
                    $errorMsg .= '<br>';
                    $error = true;
                }
            }

            if(empty($_FILES['imgProduct']['tmp_name'])) {
                $error = true;
                $errorMsg .= '-Image';
            } else {
                if(!file_exists('img/produits/')) {
                    $upload = mkdir('img/produits/', 0777);
                    if(!$upload) {
                        $error = true;
                        die("Dossier d'upload non créé");
                        $errorMsg = "Dossier d'upload non créé, veuillez réessayer.<br>";
                    }
                }else {
                    $upload = 'img/produits/'.$_FILES['imgProduct']['name'];
                    $move = move_uploaded_file($_FILES['imgProduct']['tmp_name'], $upload);
                    if(!$move) {
                        $errorMsg = 'Problème de téléchargement';
                    }
                }
            }

            if(empty($error)){
                $sqlNewProductPost = "INSERT INTO `produits` (titre, description, img, date_creation, avis, price)
                                      VALUES (:titre, :description, :img, :date_creation, :avis, :price)";

                $sth = $dbh->prepare($sqlNewProductPost);

                $sth->bindParam(':titre', $title, PDO::PARAM_STR);
                $sth->bindParam(':description', $description, PDO::PARAM_STR);
                $sth->bindParam(':img', $upload, PDO::PARAM_STR);
                $sth->bindParam(':date_creation', $dateCreation, PDO::PARAM_STR);
                $sth->bindParam(':avis', $avis, PDO::PARAM_STR);
                $sth->bindParam(':price', $price, PDO::PARAM_INT);

                $sth->execute();
                header('Location: index.php');
            }else{
                $tabError = [$error, $errorMsg];
                return $tabError;
            }

        }   
    }