<?php

include_once('constants.php');

// Connect to database.
// Use this function before any other listed in this file
function dbConnect() {
    $dsn = DB_DRIVER.":dbname=".DB_NAME.";host=".DB_SERVER.";port=".DB_PORT;
    try {
        $conn = new PDO($dsn, DB_USER, DB_PASSWORD);
    }
    catch (PDOException $e) {
        echo 'Connection to database failed: ' . $e->getMessage();
        $conn = false;
    }
    return $conn;
}

?>