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

function db_getAllDocuIds($conn, $ondulatorBrand, $panelBrand, $dep, $page): array {
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
    $req .= " LIMIT 100 OFFSET ".(($page-1)*20).";";

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
    $req = "
        SELECT doc.id, doc.lat AS latitude, doc.long AS longitude, doc.date,
               doc.puiss_crete AS puissance_crete, doc.nb_panneaux,
               com.nom AS commune, com.code_postal,
               inst.nom AS installeur,
               p_marque.nom AS marque_panneau,
               o_marque.nom AS marque_ondulateur
        FROM Documentation AS doc
        JOIN Commune AS com ON doc.code_insee = com.code_insee
        JOIN Installeur AS inst ON doc.id_Installeur = inst.id
        JOIN Panneau AS p ON doc.id_Panneau = p.id
        JOIN Panneau_Marque AS p_marque ON p.id_Panneau_Marque = p_marque.id
        JOIN Ondulateur AS ond ON doc.id_Ondulateur = ond.id
        JOIN Ondulateur_Marque AS o_marque ON ond.id_Ondulateur_Marque = o_marque.id
    ";

    $wherePlaced = false;

    // Filtre par département
    if ($dep !== null && $dep !== 'all') {
        $req .= " WHERE com.code_dep = :dep";
        $wherePlaced = true;
    }

    // Filtre par année
    if ($year !== null && $year !== 'all') {
        $year_start = $year . '-01-01';
        $year_end = ($year + 1) . '-01-01';

        if ($wherePlaced) {
            $req .= " AND";
        } else {
            $req .= " WHERE";
        }
        $req .= " doc.date >= :year_start AND doc.date < :year_end";
        $wherePlaced = true;
    }

    // Limiter les résultats pour éviter une surcharge
    $req .= " LIMIT 100";

    $stmt = $conn->prepare($req);

    if ($dep !== null && $dep !== 'all') {
        $stmt->bindParam(':dep', $dep);
    }

    if ($year !== null && $year !== 'all') {
        $stmt->bindParam(':year_start', $year_start);
        $stmt->bindParam(':year_end', $year_end);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Récupère les 20 premières marques d'onduleurs
 */
function db_getOndulatorBrands($conn, $limit = 20) {
    $stmt = $conn->prepare('SELECT nom FROM Ondulateur_Marque ORDER BY RANDOM() LIMIT :limit');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_column($result, 'nom');
}

/**
 * Récupère les 20 premières marques de panneaux
 */
function db_getPanelBrands($conn, $limit = 20) {
    $stmt = $conn->prepare('SELECT nom FROM Panneau_Marque ORDER BY RANDOM() LIMIT :limit');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_column($result, 'nom');
}

/**
 * Récupère 20 départements au hasard
 */
function db_getRandomDepartments($conn, $limit = 20) {
    $stmt = $conn->prepare('SELECT code, nom FROM Departement ORDER BY RANDOM() LIMIT :limit');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Récupère les données pour tous les selects
 */
function db_getSelectData($conn) {
    return array(
        'ondulateur_brands' => db_getOndulatorBrands($conn),
        'panel_brands' => db_getPanelBrands($conn),
        'departments' => db_getRandomDepartments($conn)
    );
}

function db_CommuneExists($conn, $insee): bool {
    $stmt = $conn->prepare('SELECT count(code_insee) FROM Commune WHERE code_insee=:insee;');
    $stmt->bindParam(':insee', $insee);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

// Return true if documentation with the given id exists, false otherwise
function db_DocExists($conn, $iddoc): bool {
    $stmt = $conn->prepare('SELECT count(id) FROM Documentation WHERE id=:iddoc');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

// Generic function to get the id of a row where name corresponds to the table
// Compatible with tables that have attributes 'id' and 'nom'
function db_getId($conn, $table, $name) {
    $stmt = $conn->prepare("SELECT id FROM ".$table." WHERE nom=:name;");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        return false;
    }
    else {
        return intval($result['id']);
    }
}

// Generic function to add a name to the given table
// Compatible with tables that have attributes 'id' and 'nom'
function db_addName($conn, $table, $name) {
    $id = db_getId($conn, $table, $name);
    if ($id === false) {
        // Add line to table
        $stmt = $conn->prepare("INSERT INTO ".$table." (nom) VALUES (:name);");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $id = db_getId($conn, $table, $name);
    }
    return $id;
}

// Generic function to get id where ids match
// Compatible with tables that have an attribute 'id' two attributes to link to other tables
// Table, link1 and link2 should NEVER come from client
function db_getIdLinks($conn, $table, $link1, $link2, $id1, $id2) {
    $stmt = $conn->prepare("SELECT id FROM ".$table." WHERE ".$link1."=:id1 AND ".$link2."=:id2");
    $stmt->bindParam(':id1', $id1);
    $stmt->bindParam(':id2', $id2);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        return false;
    }
    else {
        return intval($result['id']);
    }
}

function db_addLink($conn, $table, $link1, $link2, $id1, $id2) {
    $result_id = db_getIdLinks($conn, $table, $link1, $link2, $id1, $id2);
    if ($result_id === false) {
        // Add line to table
        $stmt = $conn->prepare("INSERT INTO ".$table." (".$link1.", ".$link2.") VALUES (:id1, :id2);");
        $stmt->bindParam(':id1', $id1);
        $stmt->bindParam(':id2', $id2);
        $stmt->execute();
        $result_id = db_getIdLinks($conn, $table, $link1, $link2, $id1, $id2);
    }
    return $result_id;
}

function db_addOndulator($conn, $brand, $modele) {
    // Add brand if necessary
    $brand_id = db_addName($conn, "Ondulateur_Marque", $brand);

    // Add modele if necessary
    $modele_id = db_addName($conn, "Ondulateur_Modele", $modele);

    // Add ondulator if necessary
    $ondul_id = db_addLink($conn, "Ondulateur", "id_Ondulateur_Modele", "id_Ondulateur_Marque", $modele_id, $brand_id);
    return $ondul_id;
}

function db_addPanel($conn, $brand, $modele) {
    // Add brand if necessary
    $brand_id = db_addName($conn, "Panneau_Marque", $brand);

    // Add modele if necessary
    $modele_id = db_addName($conn, "Panneau_Modele", $modele);

    // Add ondulator if necessary
    $panel_id = db_addLink($conn, "Panneau", "id_Panneau_Modele", "id_Panneau_Marque", $modele_id, $brand_id);
    return $panel_id;
}

function db_addInstallation($conn, $date, $insee, $lat, $long, $surface, $puiss, $nbPanels, $nbOnduls, $incl, $orient, $brandOndul, $modeleOndul, $brandPanel, $modelePanel, $installer, $pvgis, $incl_opti=null, $orient_opti=null): bool {
    if (!db_CommuneExists($conn, $insee)) {
        return false;   // Cannot insert installation
    }
    $panel_id = db_addPanel($conn, $brandPanel, $modelePanel);
    $ondul_id = db_addOndulator($conn, $brandOndul, $modeleOndul);
    $installer_id = db_addName($conn, "Installeur", $installer);

    $req = "INSERT INTO Documentation (date, lat, long, nb_panneaux, nb_ondul, puiss_crete, surface, pente, pente_optimum, orientation, orientation_optimum, production_pvgis, code_insee, id_Panneau, id_Ondulateur, id_Installeur)
            VALUES (:date, :lat, :long, :nb_panneaux, :nb_ondul, :puiss_crete, :surface, :pente, :pente_opti, :orient, :orient_opti, :prod_pvgis, :insee, :id_pan, :id_ondul, :id_inst);";
    $stmt = $conn->prepare($req);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':long', $long);
    $stmt->bindParam(':surface', $surface);
    $stmt->bindParam(':puiss_crete', $puiss);
    $stmt->bindParam(':nb_panneaux', $nbPanels);
    $stmt->bindParam(':nb_ondul', $nbOnduls);
    $stmt->bindParam(':pente', $incl);
    $stmt->bindParam(':pente_opti', $incl_opti, PDO::PARAM_NULL);
    $stmt->bindParam(':orient', $orient);
    $stmt->bindParam(':orient_opti', $orient_opti, PDO::PARAM_NULL);
    $stmt->bindParam(':prod_pvgis', $pvgis);
    $stmt->bindParam(':insee', $insee);
    $stmt->bindParam(':id_pan', $panel_id);
    $stmt->bindParam(':id_ondul', $ondul_id);
    $stmt->bindParam(':id_inst', $installer_id);
    $stmt->execute();

    // Add installation
    $iddoc = $conn->lastInsertId();
    $stmt = $conn->prepare('INSERT INTO Installation (iddoc) VALUES (:iddoc);');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();

    return true;
}

function db_deleteDoc($conn, $iddoc) {
    $stmt = $conn->prepare('DELETE FROM Installation WHERE iddoc=:iddoc;');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();

    $stmt = $conn->prepare('DELETE FROM Documentation WHERE id=:iddoc');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();
}

function db_putInstallation($conn, $iddoc, $date, $insee, $lat, $long, $surface, $puiss, $nbPanels, $nbOnduls, $incl, $orient, $brandOndul, $modeleOndul, $brandPanel, $modelePanel, $installer, $pvgis, $incl_opti=null, $orient_opti=null): bool {
    if (!db_DocExists($conn, $iddoc)) {
        return false;   // Cannot find installation to change
    }
    if (!db_CommuneExists($conn, $insee)) {
        return false;   // Cannot change position
    }
    $panel_id = db_addPanel($conn, $brandPanel, $modelePanel);
    $ondul_id = db_addOndulator($conn, $brandOndul, $modeleOndul);
    $installer_id = db_addName($conn, "Installeur", $installer);

    // Build request
    $req = "
    UPDATE Documentation SET
    date=:date
    lat=:lat
    long=:long
    nb_panneaux=:nb_panneaux
    nb_ondul=:nb_ondul
    puiss_crete=:puiss_crete
    surface=:surface
    pente=:pente
    orientation=:orient
    production_pvgis=:prod_pvgis
    code_insee=:insee
    id_Panneau=:id_pan
    id_Ondulateur=:id_ondul
    id_Installeur=:id_inst";
    if ($incl_opti !== null) {
        $req .= " pente_optimum=:pente_opti";
    }
    if ($orient_opti !== null) {
        $req .= " orientation_optimum=:orient_opti";
    }

    $req .= " WHERE id=:id;";
    
    // Bind parameters and execute request
    $stmt = $conn->prepare($req);
    $stmt->bindParam(':id', $iddoc);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':long', $long);
    $stmt->bindParam(':surface', $surface);
    $stmt->bindParam(':puiss_crete', $puiss);
    $stmt->bindParam(':nb_panneaux', $nbPanels);
    $stmt->bindParam(':nb_ondul', $nbOnduls);
    $stmt->bindParam(':pente', $incl);
    $stmt->bindParam(':orient', $orient);
    $stmt->bindParam(':prod_pvgis', $pvgis);
    $stmt->bindParam(':insee', $insee);
    $stmt->bindParam(':id_pan', $panel_id);
    $stmt->bindParam(':id_ondul', $ondul_id);
    $stmt->bindParam(':id_inst', $installer_id);

    if ($incl_opti !== null) {
        $stmt->bindParam(':pente_opti', $incl_opti, PDO::PARAM_NULL);
    }
    if ($orient_opti !== null) {
        $stmt->bindParam(':orient_opti', $orient_opti, PDO::PARAM_NULL);
    }
    
    $stmt->execute();

    return true;
}

?>