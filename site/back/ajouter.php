<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification Installation</title>
     <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/commun.css">
    <link rel="stylesheet" href="../css/recherche.css">
</head>
<body>
    <div class="animated-bg"></div>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="../front/accueil.php">
                <i class="bi bi-lightning-charge"></i>
                PHOTOVOLTIS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="d-flex align-items-center mb-4">
            <a href="recherche_developeur.php">
            <button class="btn btn-primary btn-search btn-lg">
                 <i class="bi bi-arrow-left me-2"></i>
                    <small>
                        Retour à la recherche
                    </small>
            </button>
            </a>
        </div>
        <h2 class="text-center mb-4">Ajouter une installation</h2>
        <form method="post" action="traitement_modification.php">
            <div class="row g-3">
                <!-- Identifiant (lecture seule) -->
                <div class="col-md-6">
                    <label class="form-label">Identifiant</label>
                    <input type="text" class="form-control" name="id" value="" readonly>
                </div>

                <!-- Date -->
                <div class="col-md-6">
                    <label class="form-label">Date d'installation</label>
                    <input type="date" class="form-control" name="date" value="">
                </div>

                <!-- Adresse -->
                <div class="col-md-6">
                    <label class="form-label">Code insee</label>
                    <input type="text" class="form-control" name="adresse" value="">
                </div>

                <!-- Coordonnées GPS -->
                <div class="col-md-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" name="latitude" value="">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" name="longitude" value="">
                </div>

                <!-- Surface -->
                <div class="col-md-4">
                    <label class="form-label">Surface</label>
                    <input type="text" class="form-control" name="surface" value="">
                </div>

                <!-- Puissance -->
                <div class="col-md-4">
                    <label class="form-label">Puissance totale</label>
                    <input type="text" class="form-control" name="puissance" value="">
                </div>

                <!-- Nombre de panneaux -->
                <div class="col-md-4">
                    <label class="form-label">Nombre de panneaux</label>
                    <input type="number" class="form-control" name="nbPanneaux" value="">
                </div>

                <!-- Nombre d'onduleurs -->
                <div class="col-md-4">
                    <label class="form-label">Nombre d'onduleurs</label>
                    <input type="number" class="form-control" name="nbOndulateurs" value="">
                </div>

                <!-- Orientation -->
                <div class="col-md-4">
                    <label class="form-label">Orientation</label>
                    <input type="text" class="form-control" name="orientation" value="">
                </div>

                 <div class="col-md-4">
                    <label class="form-label">Orientation optimale</label>
                    <input type="text" class="form-control" name="orientation" value="">
                </div>

                <!-- Inclinaison -->
                <div class="col-md-4">
                    <label class="form-label">Inclinaison</label>
                    <input type="text" class="form-control" name="inclinaison" value="">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Inclinaison optimale </label>
                    <input type="text" class="form-control" name="inclinaison" value="">
                </div>

                <!-- Marque onduleur -->
                <div class="col-md-6">
                    <label class="form-label">Marque Onduleur</label>
                    <input type="text" class="form-control" name="marqueOnduleur" value="">
                </div>

                <!-- Modèle onduleur -->
                <div class="col-md-6">
                    <label class="form-label">Modèle Onduleur</label>
                    <input type="text" class="form-control" name="modeleOnduleur" value="">
                </div>

                <!-- Marque panneaux -->
                <div class="col-md-6">
                    <label class="form-label">Marque Panneaux</label>
                    <input type="text" class="form-control" name="marquePanneaux" value="">
                </div>

                <!-- Modèle panneaux -->
                <div class="col-md-6">
                    <label class="form-label">Modèle Panneaux</label>
                    <input type="text" class="form-control" name="modelePanneaux" value="">
                </div>

                <!-- Installateur -->
                <div class="col-md-6">
                    <label class="form-label">Installateur</label>
                    <input type="text" class="form-control" name="installateur" value="">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Production pugis</label>
                    <input type="text" class="form-control" name="installateur" value="">
                </div>

            <!-- Bouton de soumission -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-search btn-lg">Ajouter</button>
            </div>
        </form>
    </div>
</body>
</html>
