<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("database.php");

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
    header('Content-Type: application/json');   // Get requests return JSON
    
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
                $page = $_GET['page'] ?? 1;
                $ids = db_getAllDocuIds($conn, $_GET['marqueOndulateur'], $_GET['marquePanneaux'],$_GET['numDepartement'], $page);
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
                    requestError();
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
        else if ($_GET['type'] == 'select_data') {
            $selectData = db_getSelectData($conn);
            echo json_encode($selectData);
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

else if ($method == "POST") {
    // Add an installation 
    if (isset($_POST['date'], $_POST['insee'], $_POST['latitude'], $_POST['longitude'], $_POST['surface'], $_POST['puissance'], $_POST['nbPanneaux'],
        $_POST['nbOndulateurs'], $_POST['orientation'], $_POST['inclinaison'], $_POST['marqueOnduleur'], $_POST['modeleOnduleur'],
        $_POST['marquePanneaux'], $_POST['modelePanneaux'], $_POST['installateur'], $_POST['prod_pvgis'])) 
    {
        // Define optimum inclination
        if (isset($_POST['inclinaison_opti'])) {
            $incl_opti = intval($_POST['inclinaison_opti']);
        }
        else {
            $incl_opti = null;
        }

        // Define optimum orientation
        if (isset($_POST['orientation_opti'])) {
            $orient_opti = $_POST['orientation_opti'];
        }
        else {
            $orient_opti = null;
        }
        
        $result = db_addInstallation($conn, $_POST['date'], $_POST['insee'], floatval($_POST['latitude']),floatval($_POST['longitude']),
            intval($_POST['surface']), intval($_POST['puissance']), intval($_POST['nbPanneaux']), intval($_POST['nbOndulateurs']),
            intval($_POST['inclinaison']), $_POST['orientation'], $_POST['marqueOnduleur'], $_POST['modeleOnduleur'],
            $_POST['marquePanneaux'], $_POST['modelePanneaux'], $_POST['installateur'], intval($_POST['prod_pvgis']),
            $incl_opti, $orient_opti
        );
        if ($result) {
            http_response_code(201);
        }
        else {
            requestError();
        }
        exit;
    }
    else {
        requestError();
    }
}

else if ($method == 'PUT') {
    if (isset($_GET['id'], $_GET['date'], $_GET['insee'], $_GET['latitude'], $_GET['longitude'], $_GET['surface'], $_GET['puissance'], $_GET['nbPanneaux'],
        $_GET['nbOndulateurs'], $_GET['orientation'], $_GET['inclinaison'], $_GET['marqueOnduleur'], $_GET['modeleOnduleur'],
        $_GET['marquePanneaux'], $_GET['modelePanneaux'], $_GET['installateur'], $_GET['prod_pvgis']))
    {
        // Define optimum inclination
        if (isset($_GET['inclinaison_opti'])) {
            $incl_opti = intval($_GET['inclinaison_opti']);
        }
        else {
            $incl_opti = null;
        }

        // Define optimum orientation
        if (isset($_GET['orientation_opti'])) {
            $orient_opti = $_GET['orientation_opti'];
        }
        else {
            $orient_opti = null;
        }

        $result = db_putInstallation($conn, $_GET['date'], $_GET['insee'], floatval($_GET['latitude']),floatval($_GET['longitude']),
            intval($_GET['surface']), intval($_GET['puissance']), intval($_GET['nbPanneaux']), intval($_GET['nbOndulateurs']),
            intval($_GET['inclinaison']), $_GET['orientation'], $_GET['marqueOnduleur'], $_GET['modeleOnduleur'],
            $_GET['marquePanneaux'], $_GET['modelePanneaux'], $_GET['installateur'], intval($_GET['prod_pvgis']),
            $incl_opti, $orient_opti
        );
        if ($result) {
            http_response_code(200);
        }
        else {
            requestError();
        }
    }
    else {
        requestError();
    }
}

else if ($method == 'DELETE') {
    if (isset($_GET['id'])) {
        db_deleteDoc($conn, $_GET['id']);
        http_response_code(200);
        exit;
    }
    else {
        requestError();
    }
}

requestError();
?>