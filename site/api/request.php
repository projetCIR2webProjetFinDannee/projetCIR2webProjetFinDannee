<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("database.php");

function requestError() {
    http_response_code(400);
    exit;
}

function serverError() {
    http_response_code(500);
    exit;
}

$conn = dbConnect();
if (!($conn instanceof PDO)) {
    requestError();
}

// fonction pour récupérer toutes les statistiques
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

// récupération de la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    header('Content-Type: application/json');   // réponse au format JSON
    
    if (isset($_GET['type'])) {
        if ($_GET['type'] == 'stats') {
            if (isset($_GET['year']) && isset($_GET['region'])) {
                try {
                    $stats = getAllStats($conn, $_GET['year'], $_GET['region']);
                    echo json_encode($stats);
                    exit;
                }
                catch (Exception $e) {
                    echo json_encode(array('error'=> $e->getMessage()));
                    serverError();
                }
            }
            else {
                requestError();
            }
        }
        // endpoint pour récupérer les IDs des installations
        else if ($_GET['type'] == 'search') {
            if (isset($_GET['marqueOndulateur']) && isset($_GET['marquePanneaux']) && isset($_GET['numDepartement'])) {
                try {
                    $page = $_GET['page'] ?? 1;
                    $ids = db_getAllDocuIds($conn, $_GET['marqueOndulateur'], $_GET['marquePanneaux'],$_GET['numDepartement'], $page);
                    echo json_encode($ids);
                    exit;
                }
                catch (Exception $e) {
                    echo json_encode(array('error'=> $e->getMessage()));
                    serverError();
                }
            }
            else {
                requestError();
            }
        }
        // endpoint pour récupérer les informations d'une installation
        else if ($_GET['type'] == 'info') {
            if (isset($_GET['id'])) {
                try {
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
                catch (Exception $e) {
                    echo json_encode(array('error'=> $e->getMessage()));
                    serverError();
                }
            }
            else {
                requestError();
            }
        }
        // endpoint pour les menus déroulants
        else if ($_GET['type'] == 'select_data') {
            try {
                $selectData = db_getSelectData($conn);
                echo json_encode($selectData);
                exit;
            }
            catch (Exception $e) {
                echo json_encode(array('error'=> $e->getMessage()));
                serverError();
            }
        }
        // Route pour récupérer les installations par carte
        else if ($_GET['type'] == 'locations') {
            $dep = isset($_GET['departement']) ? $_GET['departement'] : null;
            $year = isset($_GET['annee']) ? $_GET['annee'] : null;
            
            $locations = db_getAllLocs($conn, $dep, $year);
            echo json_encode(['locations' => $locations]);
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

// traitement des requêtes POST
else if ($method == "POST") {
    // Add an installation 
    if (isset($_POST['date'], $_POST['insee'], $_POST['latitude'], $_POST['longitude'], $_POST['surface'], $_POST['puissance'], $_POST['nbPanneaux'],
        $_POST['nbOndulateurs'], $_POST['orientation'], $_POST['inclinaison'], $_POST['marqueOnduleur'], $_POST['modeleOnduleur'],
        $_POST['marquePanneaux'], $_POST['modelePanneaux'], $_POST['installateur'], $_POST['prod_pvgis'])) 
    {
        try {
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
        catch (Exception $e) {
            serverError();
        }
    }
    else {
        requestError();
    }
}

// Traitement des requêtes PUT
else if ($method == 'PUT') {
    if (isset($_GET['id'], $_GET['date'], $_GET['insee'], $_GET['latitude'], $_GET['longitude'], 
              $_GET['surface'], $_GET['puissance'], $_GET['nbPanneaux'], $_GET['nbOndulateurs'], 
              $_GET['orientation'], $_GET['inclinaison'], $_GET['marqueOnduleur'], $_GET['modeleOnduleur'],
              $_GET['marquePanneaux'], $_GET['modelePanneaux'], $_GET['installateur'], $_GET['prod_pvgis'])) 
    {
        try {
            // Definir la pente optimum
            $incl_opti = null;
            if (isset($_GET['inclinaison_opti']) && !empty($_GET['inclinaison_opti'])) {
                $incl_opti = intval($_GET['inclinaison_opti']);
            }

            // Definir l'orientation optimum
            $orient_opti = null;
            if (isset($_GET['orientation_opti']) && !empty($_GET['orientation_opti'])) {
                $orient_opti = $_GET['orientation_opti'];
            }

            $result = db_putInstallation($conn, 
                $_GET['id'],
                $_GET['date'], 
                $_GET['insee'], 
                floatval($_GET['latitude']),
                floatval($_GET['longitude']),
                intval($_GET['surface']), 
                intval($_GET['puissance']), 
                intval($_GET['nbPanneaux']), 
                intval($_GET['nbOndulateurs']),
                intval($_GET['inclinaison']), 
                $_GET['orientation'], 
                $_GET['marqueOnduleur'], 
                $_GET['modeleOnduleur'],
                $_GET['marquePanneaux'], 
                $_GET['modelePanneaux'], 
                $_GET['installateur'], 
                intval($_GET['prod_pvgis']),
                $incl_opti, 
                $orient_opti
            );
            
            if ($result) {
                http_response_code(200);
                echo json_encode(["success" => true, "message" => "Installation modifiée avec succès"]);
                exit;
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de la modification"]);
                serverError();
            }
        }
        catch (Exception $e) {
            serverError();
        }
    } else {
        echo json_encode(["success" => false, "message" => "Paramètres manquants"]);
        requestError();
    }
}

// traitement des requêtes DELETE
else if ($method == 'DELETE') {
    if (isset($_GET['id'])) {
        try {
            db_deleteDoc($conn, $_GET['id']);
            http_response_code(200);
            exit;
        }
        catch (Exception $e) {
            serverError();
        }
    }
    else {
        requestError();
    }
}

requestError();
?>