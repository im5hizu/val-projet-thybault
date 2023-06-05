<?php

$user = 'root';
$pass = "";
$host = 'localhost';
$dbname = "nitrocol.db";
try {   
    $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass);
    $dbh->exec("SET NAMES 'utf8'");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
   
} catch (PDOException $e) {
    $error = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
    die($msg);
}