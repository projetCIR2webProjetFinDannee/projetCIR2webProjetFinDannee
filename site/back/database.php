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

function db_getAllDocuIds($conn, $ondulatorBrand, $panelBrand, $dep) {
    $req = "
        SELECT doc.id
        FROM Documentation AS doc
        JOIN Panneau AS p ON doc.id_Panneau=p.id
        JOIN Panneau_Marque AS p_marque ON p.id_Panneau_Marque=p_marque.id
        JOIN Ondulateur AS ond ON doc.id_Ondulateur=ond.id
        JOIN Ondulateur_Marque AS o_marque ON ond.id_Ondulateur_Marque=o_marque.id
        JOIN Commune AS com ON doc.code_insee=com.code_insee";

    // Add optional parameters, where 'all' means no selection
    $wherePlaced = false;
    // Ondulator brand
    if ($ondulatorBrand != 'all') {     
        $req .= " WHERE o_marque.nom LIKE :ond_brand";
        $wherePlaced = true;
    }
    // Pannel brand
    if ($panelBrand != "all") {     
        if ($wherePlaced) {
            $req .= " AND";
        }
        else {
            $req .= " WHERE";
        }
        $req .= " p_marque.nom LIKE :pan_brand";
        $wherePlaced = true;
    }
    // Departement
    if ($dep != "all") {
        if ($wherePlaced) {
            $req .= " AND";
        }
        else {
            $req .= " WHERE";
        }
        $req .= " com.code_dep LIKE :dep";
        $wherePlaced = true;
    }
    // Limit the number of rows
    $req .= " LIMIT 20 OFFSET 0;";

    $stmt = $conn->prepare($req);

    if ($ondulatorBrand != 'all') {
        $searchOndul = "%".$ondulatorBrand."%";
        $stmt->bindParam(':ond_brand', $searchOndul);
    }
    if ($panelBrand != 'all') {
        $searchPanel = "%".$panelBrand."%";
        $stmt->bindParam(':pan_brand', $searchPanel);
    }
    if ($dep != 'all') {
        $searchDep = "%".$dep."%";
        $stmt->bindParam(':dep', $searchDep);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array("results" => array_column($result, 'id'));
}

function db_getDocuInfos($conn, $iddoc) {
    $stmt = $conn->prepare('
        SELECT doc.date, doc.lat AS "latitude", doc.long AS "longitude", doc.nb_panneaux, doc.nb_ondul AS "nb_ondulateurs",
        doc.surface, doc.puiss_crete AS "puissance_crete", doc.pente, doc.pente_optimum, doc.orientation, doc.orientation_optimum,
        doc.production_pvgis, p_marque.nom AS "marque_panneau", p_modele.nom AS "modele_panneau", o_marque.nom AS "marque_ondulateur",
        o_modele.nom AS "modele_ondulateur", inst.nom AS "installeur", com.code_postal, com.nom AS "commune"
        FROM Documentation AS doc
        JOIN Panneau AS p ON doc.id_Panneau=p.id
        JOIN Panneau_Marque AS p_marque ON p.id_Panneau_Marque=p_marque.id
        JOIN Panneau_Modele AS p_modele ON p.id_Panneau_Modele=p_modele.id
        JOIN Ondulateur AS ond ON doc.id_Ondulateur=ond.id
        JOIN Ondulateur_Marque AS o_marque ON ond.id_Ondulateur_Marque=o_marque.id
        JOIN Ondulateur_Modele AS o_modele ON ond.id_Ondulateur_Modele=o_modele.id
        JOIN Installeur AS inst ON doc.id_Installeur=inst.id
        JOIN Commune AS com ON doc.code_insee=com.code_insee
        WHERE doc.id=:iddoc;');

    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function db_getAllLocs($conn, $dep=null, $year=null) {
    // Get all locs corresponding to given dep and year
}

?>