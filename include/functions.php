<?php 
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

    function verifPost(): array {
        if(isset($_POST['username'])){
            $errorMsg = "Veuillez remplir les champ(s) suivant(s):<br>";
            foreach($_POST as $key => $value){
                ${$key} = $value;
                
                if(empty($value) && $key !== 'img'){
                    switch($key){
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

            if(empty($admin)){
                $errorMsg .= 'Admin<br>';
                $error = true;
            }


            if(isset($_FILES)){
                var_dump($_FILES);
                if(empty($_FILES['img'])){
                    $error = true;
                    $errorMsg .= 'Image<br>';    
                }
            }   
        }else {

        }

            $tabError = [$error, $errorMsg];


            return $tabError;
    }