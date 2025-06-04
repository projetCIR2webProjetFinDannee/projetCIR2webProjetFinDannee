<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("database.php");

function requestError($msg="") {
    echo "error";
    exit;
}

$conn = dbConnect();

function getAllStats($conn, $year, $region) {
    $stats = array(
        'nb_installs' => db_getNbInstallations($conn),
        'nb_inst_year' => db_getNbInstallationsPerYear($conn, $year),
        'nb_inst_reg' => db_getNbInstallationsPerRegion($conn, $region),
        'nb_inst_year_reg' => db_getNbInstallationsPerYearRegion($conn, $year, $region),
        'nb_installers' => db_getNbInstallers($conn),
        'nb_ond_brands' => db_getNbOndulatorBrand($conn),
        'nb_panels_brands' => db_getNbPanelBrand($conn)
    );

    return $stats;
}

// Answer to requests
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    if (isset($_GET['type'])) {
        if ($_GET['type'] == 'stats') {
            if (isset($_GET['year']) && isset($_GET['region'])) {
                $stats = getAllStats($conn, $_GET['year'], $_GET['region']);
                echo json_encode($stats);
                exit;
            }
            else {
                requestError();
            }
        }
    }
    else {
        requestError();
    }
}

requestError();
?>