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

function db_getNbInstallations($conn) {
    $stmt = $conn->prepare('SELECT count(id) FROM Installation;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function db_getNbInstallationsPerYear($conn, $year) {
    $year_start = $year.'-01-01';
    $year_end = ($year+1).'-01-01';
    $stmt = $conn->prepare('
        SELECT count(i.id) FROM Installation AS i
        JOIN Documentation AS d ON i.iddoc=d.id
        WHERE date >= :year_start AND date < :year_end;
    ');
    $stmt->bindParam(':year_start', $year_start);
    $stmt->bindParam(':year_end', $year_end);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function db_getNbInstallationsPerRegion($conn, $region) {
    $stmt = $conn->prepare(
        'SELECT count(i.id) FROM Installation AS i
        JOIN Documentation AS d ON i.iddoc=d.id
        JOIN Commune AS c ON d.code_insee=c.code_insee
        JOIN Departement AS dep ON c.code_dep=dep.code
        WHERE dep.code_Region=:region;'
    );
    $stmt->bindParam(':region', $region);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function db_getNbInstallationsPerYearRegion($conn, $year, $region) {
    $year_start = $year.'-01-01';
    $year_end = ($year+1).'-01-01';
    $stmt = $conn->prepare(
        'SELECT count(i.id) FROM Installation AS i
        JOIN Documentation AS d ON i.iddoc=d.id
        JOIN Commune AS c ON d.code_insee=c.code_insee
        JOIN Departement AS dep ON c.code_dep=dep.code
        WHERE dep.code_Region=:region
        AND d.date >= :year_start
        AND d.date < :year_end;'
    );
    $stmt->bindParam(':year_start', $year_start);
    $stmt->bindParam(':year_end', $year_end);
    $stmt->bindParam(':region', $region);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function db_getNbInstallers($conn) {
    $stmt = $conn->prepare('SELECT count(id) FROM Installeur;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function db_getNbOndulatorBrand($conn) {
    $stmt = $conn->prepare('SELECT count(id) FROM Ondulateur_Marque;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function db_getNbPanelBrand($conn) {
    $stmt = $conn->prepare('SELECT count(id) FROM Panneau_Marque;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
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