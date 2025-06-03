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

function db_getNbInstallations($conn, $year=null, $region=null) {

}

function db_getNbInstallers($conn) {

}

function db_getNbOndulatorBrand($conn) {

}

function db_getNbPanelBrand($conn) {

}

function db_getAllDocuIds($conn, $ondulatorBrand=null, $panelBrand=null, $dep=null) {

}

function db_getDocuShortInfos($conn, $iddoc) {
    // See updateResults() in js
}

function db_getDocuInfos($conn, $iddoc) {
    // All infos
    // See showDetailPage() in recherche.js
}

function db_getAllLocs($conn, $dep=null, $year=null) {
    // Get all locs corresponding to given dep and year
}

?>