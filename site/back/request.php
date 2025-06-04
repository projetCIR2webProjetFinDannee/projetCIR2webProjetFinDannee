<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("database.php");

header('Content-Type: application/json');

function requestError($msg="") {
    http_response_code(400);
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
        else if ($_GET['type'] == 'search') {
            if (isset($_GET['marqueOndulateur']) && isset($_GET['marquePanneaux']) && isset($_GET['numDepartement'])) {
                $ids = db_getAllDocuIds($conn, $_GET['marqueOndulateur'], $_GET['marquePanneaux'],$_GET['numDepartement']);
                echo json_encode($ids);
                exit;
            }
            else {
                requestError();
            }
        }
        else if ($_GET['type'] == 'info') {
            if (isset($_GET['id'])) {
                $install = db_getDocuInfos($conn, $_GET['id']);
                if ($install === false) {
                    http_response_code(400);
                }
                else {
                    http_response_code(200);
                    echo json_encode($install);
                }
                exit;
            }
            else {
                requestError();
            }
        }
        else {
            requestError();
        }
    }
    else {
        requestError();
    }
}
else if ($method == "POST") {
    if (isset($_POST['date'], $_POST['insee'], $_POST['latitude'], $_POST['longitude'], $_POST['surface'], $_POST['puissance'], $_POST['nbPanneaux'],
        $_POST['nbOndulateurs'], $_POST['orientation'], $_POST['inclinaison'], $_POST['marqueOnduleur'], $_POST['modeleOnduleur'],
        $_POST['marquePanneaux'], $_POST['modelePanneaux'], $_POST['installateur'], $_POST['prod_pvgis'])) 
    {
        
        echo $_POST['date'];
    }
}

requestError();
?>