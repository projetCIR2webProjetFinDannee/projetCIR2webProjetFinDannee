<?php
include_once("database.php");

$conn = dbConnect();

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    
}

header("accueil.php");
exit;
?>