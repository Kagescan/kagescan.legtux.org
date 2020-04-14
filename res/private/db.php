<?php

// Adapt the code with your website
// NEVER publish that file if there are passwords !!
$passBdd = "";

try {
    $bdd = new PDO('mysql:host=localhost;dbname=kagescan;charset=utf8', 'kagescan', $passBdd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e) { die('Erreur : '.$e->getMessage()); }

?>
