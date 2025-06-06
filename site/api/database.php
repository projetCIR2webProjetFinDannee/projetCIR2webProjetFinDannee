<?php

include_once('constants.php');

// Connection a la base de données
// Utilisez cette fonction avant toute autre fonction listée dans ce fichier
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

function getSQLRandom($driver) {
    switch($driver) {
        case 'sqlite':
            $randomFunc = 'RANDOM()';
            break;
        case 'mysql':
            $randomFunc = 'RAND()';
            break;
        case 'pgsql':
            $randomFunc = 'RANDOM()';
            break;
        case 'sqlsrv':
        case 'dblib':
            $randomFunc = 'NEWID()';
            break;
        default:
            $randomFunc = 'RANDOM()';       // Cas par défaut
    }
    return $randomFunc;
}

// Donne le nombre d'installations
function db_getNbInstallations($conn) {
    $stmt = $conn->prepare('SELECT count(id) AS "count" FROM Installation;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

// Donne le nombre d'installations par année
function db_getNbInstallationsPerYear($conn, $year) {
    $year_start = $year.'-01-01';
    $year_end = ($year+1).'-01-01';
    $stmt = $conn->prepare('
        SELECT count(i.id) AS "count" FROM Installation AS i
        JOIN Documentation AS d ON i.iddoc=d.id
        WHERE date >= :year_start AND date < :year_end;
    ');
    $stmt->bindParam(':year_start', $year_start);
    $stmt->bindParam(':year_end', $year_end);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

// Donne le nombre d'installations par région
function db_getNbInstallationsPerRegion($conn, $region) {
    $stmt = $conn->prepare(
        'SELECT count(i.id) AS "count" FROM Installation AS i
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

// Donne le nombre d'installations par année et région
function db_getNbInstallationsPerYearRegion($conn, $year, $region) {
    $year_start = $year.'-01-01';
    $year_end = ($year+1).'-01-01';
    $stmt = $conn->prepare(
        'SELECT count(i.id) AS "count" FROM Installation AS i
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

// Donne le nombre d'installateurs
function db_getNbInstallers($conn) {
    $stmt = $conn->prepare('SELECT count(id) AS "count" FROM Installeur;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

// Donne le nombre de marques d'onduleurs et de panneaux
function db_getNbOndulatorBrand($conn) {
    $stmt = $conn->prepare('SELECT count(id) AS "count" FROM Ondulateur_Marque;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

// Donne le nombre de marques de panneaux
function db_getNbPanelBrand($conn) {
    $stmt = $conn->prepare('SELECT count(id) AS "count" FROM Panneau_Marque;');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

// Donne toute la documentation
function db_getAllDocuIds($conn, $ondulatorBrand, $panelBrand, $dep, $page=1): array {
    $req = "
        SELECT doc.id
        FROM Documentation AS doc
        JOIN Panneau AS p ON doc.id_Panneau=p.id
        JOIN Panneau_Marque AS p_marque ON p.id_Panneau_Marque=p_marque.id
        JOIN Ondulateur AS ond ON doc.id_Ondulateur=ond.id
        JOIN Ondulateur_Marque AS o_marque ON ond.id_Ondulateur_Marque=o_marque.id
        JOIN Commune AS com ON doc.code_insee=com.code_insee";

    // Ajouter les paramètres optionnels, où 'all' signifie aucune sélection
    $wherePlaced = false;
    // Marque d'onduleur
    if ($ondulatorBrand != 'all') {     
        $req .= " WHERE o_marque.nom LIKE :ond_brand";
        $wherePlaced = true;
    }
    // Marque de panneau
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
    // Limiter le nombre de lignes
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

// Donne les informations d'une installation
function db_getDocuInfos($conn, $iddoc) {
    $stmt = $conn->prepare('
        SELECT doc.date, doc.latitude AS "latitude", doc.longitude AS "longitude", doc.nb_panneaux, doc.nb_ondul AS "nb_ondulateurs",
        doc.surface, doc.puiss_crete AS "puissance_crete", doc.pente, doc.pente_optimum, doc.orientation, doc.orientation_optimum,
        doc.production_pvgis, p_marque.nom AS "marque_panneau", p_modele.nom AS "modele_panneau", o_marque.nom AS "marque_ondulateur",
        o_modele.nom AS "modele_ondulateur", inst.nom AS "installeur", com.code_postal, com.nom AS "commune", doc.code_insee
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

/**
 * Récupère toutes les installations avec leurs informations
 * Optionnellement filtre par département et année
 * Limite le nombre de résultats à 100 pour éviter une surcharge
 */
function db_getAllLocs($conn, $dep=null, $year=null) {
    $req = "
        SELECT doc.id, doc.latitude AS latitude, doc.longitude AS longitude, doc.date,
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
    $randomFunc = getSQLRandom(DB_DRIVER);
    $stmt = $conn->prepare("SELECT nom FROM Ondulateur_Marque ORDER BY {$randomFunc} LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_column($result, 'nom');
}

/**
 * Récupère les 20 premières marques de panneaux
 */
function db_getPanelBrands($conn, $limit = 20) {
    $randomFunc = getSQLRandom(DB_DRIVER);
    $stmt = $conn->prepare("SELECT nom FROM Panneau_Marque ORDER BY {$randomFunc} LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_column($result, 'nom');
}

/**
 * Récupère 20 départements au hasard
 */
function db_getRandomDepartments($conn, $limit = 20) {
    $randomFunc = getSQLRandom(DB_DRIVER);
    $stmt = $conn->prepare("SELECT code, nom FROM Departement ORDER BY {$randomFunc} LIMIT :limit");
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

// Retourne true si la commune avec le code INSEE donné existe, false sinon
function db_CommuneExists($conn, $insee): bool {
    $stmt = $conn->prepare('SELECT count(code_insee) AS "count" FROM Commune WHERE code_insee=:insee;');
    $stmt->bindParam(':insee', $insee);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

// Retourne true si la documentation avec l'identifiant donné existe, false sinon
function db_DocExists($conn, $iddoc): bool {
    $stmt = $conn->prepare('SELECT count(id) AS "count" FROM Documentation WHERE id=:iddoc');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

// Fonction générique pour obtenir l'id d'une ligne où le nom correspond dans la table
// Compatible avec les tables ayant les attributs 'id' et 'nom'
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

// Fonction générique pour ajouter un nom dans la table donnée
// Compatible avec les tables ayant les attributs 'id' et 'nom'
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

// Fonction générique pour obtenir l'id où les ids correspondent
// Compatible avec les tables ayant un attribut 'id' et deux attributs pour lier à d'autres tables
// Table, link1 et link2 ne doivent JAMAIS provenir du client
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

// Fonction générique pour ajouter un lien entre deux tables
// Compatible avec les tables ayant un attribut 'id' et deux attributs pour lier à d'autres tables
// Table, link1 et link2 ne doivent JAMAIS provenir du client
function db_addLink($conn, $table, $link1, $link2, $id1, $id2) {
    $result_id = db_getIdLinks($conn, $table, $link1, $link2, $id1, $id2);
    if ($result_id === false) {
        //ajouter la ligne dans la table
        $stmt = $conn->prepare("INSERT INTO ".$table." (".$link1.", ".$link2.") VALUES (:id1, :id2);");
        $stmt->bindParam(':id1', $id1);
        $stmt->bindParam(':id2', $id2);
        $stmt->execute();
        $result_id = db_getIdLinks($conn, $table, $link1, $link2, $id1, $id2);
    }
    return $result_id;
}

// Fonction générique pour ajouter un ondulateur
// Compatible avec les tables ayant un attribut 'id' et deux attributs pour lier à d'autres tables
// Table, link1 et link2 ne doivent JAMAIS provenir du client
function db_addOndulator($conn, $brand, $modele) {
    // Ajouter la marque si nécessaire
    $brand_id = db_addName($conn, "Ondulateur_Marque", $brand);

    // ajouter le modele si nécessaire
    $modele_id = db_addName($conn, "Ondulateur_Modele", $modele);

    // ajouter l'ondulateur si nécessaire
    $ondul_id = db_addLink($conn, "Ondulateur", "id_Ondulateur_Modele", "id_Ondulateur_Marque", $modele_id, $brand_id);
    return $ondul_id;
}

// Fonction générique pour ajouter un panneau
// Compatible avec les tables ayant un attribut 'id' et deux attributs pour lier à d'autres tables
// Table, link1 et link2 ne doivent JAMAIS provenir du client
function db_addPanel($conn, $brand, $modele) {
    // ajouter la marque si nécessaire
    $brand_id = db_addName($conn, "Panneau_Marque", $brand);

    // ajouter le modele si nécessaire
    $modele_id = db_addName($conn, "Panneau_Modele", $modele);

    // ajouter le panneau si nécessaire
    $panel_id = db_addLink($conn, "Panneau", "id_Panneau_Modele", "id_Panneau_Marque", $modele_id, $brand_id);
    return $panel_id;
}

// Fonction pour ajouter une installation dans la base de données
// Retourne true si l'installation a été ajoutée, false sinon
function db_addInstallation($conn, $date, $insee, $lat, $long, $surface, $puiss, $nbPanels, $nbOnduls, $incl, $orient, $brandOndul, $modeleOndul, $brandPanel, $modelePanel, $installer, $pvgis, $incl_opti=null, $orient_opti=null): bool {
    if (!db_CommuneExists($conn, $insee)) {
        return false;   // ne peut pas ajouter l'installation, la commune n'existe pas
    }
    $panel_id = db_addPanel($conn, $brandPanel, $modelePanel);
    $ondul_id = db_addOndulator($conn, $brandOndul, $modeleOndul);
    $installer_id = db_addName($conn, "Installeur", $installer);

    $req = "INSERT INTO Documentation (date, latitude, longitude, nb_panneaux, nb_ondul, puiss_crete, surface, pente, pente_optimum, orientation, orientation_optimum, production_pvgis, code_insee, id_Panneau, id_Ondulateur, id_Installeur)
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

    // ajouter l'installation dans la table Installation
    $iddoc = $conn->lastInsertId();
    $stmt = $conn->prepare('INSERT INTO Installation (iddoc) VALUES (:iddoc);');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();

    return true;
}

// Supprime une installation et sa documentation
function db_deleteDoc($conn, $iddoc) {
    $stmt = $conn->prepare('DELETE FROM Installation WHERE iddoc=:iddoc;');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();

    $stmt = $conn->prepare('DELETE FROM Documentation WHERE id=:iddoc');
    $stmt->bindParam(':iddoc', $iddoc);
    $stmt->execute();
}

// Met à jour une installation existante
// Retourne true si l'installation a été mise à jour, false sinon
function db_putInstallation($conn, $iddoc, $date, $insee, $lat, $long, $surface, $puiss, $nbPanels, $nbOnduls, $incl, $orient, $brandOndul, $modeleOndul, $brandPanel, $modelePanel, $installer, $pvgis, $incl_opti=null, $orient_opti=null): bool {
    try {
        if (!db_DocExists($conn, $iddoc)) {
            error_log("Document n'existe pas: " . $iddoc);
            return false;   // ne peut pas mettre à jour, la documentation n'existe pas
        }
        if (!db_CommuneExists($conn, $insee)) {
            error_log("Commune n'existe pas: " . $insee);
            return false;   // ne peut pas mettre à jour, la commune n'existe pas
        }
        
        $panel_id = db_addPanel($conn, $brandPanel, $modelePanel);
        $ondul_id = db_addOndulator($conn, $brandOndul, $modeleOndul);
        $installer_id = db_addName($conn, "Installeur", $installer);

        // Préparer la requête de mise à jour
        $req = "UPDATE Documentation SET 
            date=:date,
            latitude=:lat,
            longitude=:long,
            nb_panneaux=:nb_panneaux,
            nb_ondul=:nb_ondul,
            puiss_crete=:puiss_crete,
            surface=:surface,
            pente=:pente,
            orientation=:orient,
            production_pvgis=:prod_pvgis,
            code_insee=:insee,
            id_Panneau=:id_pan,
            id_Ondulateur=:id_ondul,
            id_Installeur=:id_inst";
        
        if ($incl_opti !== null) {
            $req .= ", pente_optimum=:pente_opti";
        }
        if ($orient_opti !== null) {
            $req .= ", orientation_optimum=:orient_opti";
        }

        $req .= " WHERE id=:id";

        // Préparer et exécuter la requête
        $stmt = $conn->prepare($req);
        
        // Bind des paramètres obligatoires
        $stmt->bindParam(':id', $iddoc, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':lat', $lat);
        $stmt->bindParam(':long', $long);
        $stmt->bindParam(':surface', $surface, PDO::PARAM_INT);
        $stmt->bindParam(':puiss_crete', $puiss, PDO::PARAM_INT);
        $stmt->bindParam(':nb_panneaux', $nbPanels, PDO::PARAM_INT);
        $stmt->bindParam(':nb_ondul', $nbOnduls, PDO::PARAM_INT);
        $stmt->bindParam(':pente', $incl, PDO::PARAM_INT);
        $stmt->bindParam(':orient', $orient);
        $stmt->bindParam(':prod_pvgis', $pvgis, PDO::PARAM_INT);
        $stmt->bindParam(':insee', $insee);
        $stmt->bindParam(':id_pan', $panel_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_ondul', $ondul_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_inst', $installer_id, PDO::PARAM_INT);

        // Bind des paramètres optionnels
        if ($incl_opti !== null) {
            $stmt->bindParam(':pente_opti', $incl_opti, PDO::PARAM_INT);
        }
        if ($orient_opti !== null) {
            $stmt->bindParam(':orient_opti', $orient_opti);
        }

        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Erreur SQL: " . print_r($stmt->errorInfo(), true));
            return false;
        }

        return true;
        
    } catch (PDOException $e) {
        error_log("Erreur PDO dans db_putInstallation: " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("Erreur générale dans db_putInstallation: " . $e->getMessage());
        return false;
    }
}

?>