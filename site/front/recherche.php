<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Métadonnées de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Photovoltaïque</title>
    <!-- Liens vers Bootstrap CSS et Bootstrap Icons pour le style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Feuilles de style personnalisées -->
    <link rel="stylesheet" href="../css/commun.css">
    <link rel="stylesheet" href="../css/recherche.css">
    <style>
        /* Styles pour assurer que le footer reste en bas de page */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
        .footer-custom {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Fond animé (via CSS) -->
    <div class="animated-bg"></div>
    
    <!-- Barre de navigation principale -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <!-- Logo et nom du site -->
            <a class="navbar-brand" href="accueil.php">
                <i class="bi bi-lightning-charge"></i>
                PHOTOVOLTIS
            </a>
            <!-- Bouton pour menu mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Liens de navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.php">
                            <i class="bi bi-house"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="recherche.php">
                            <i class="bi bi-search"></i> Recherche
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carte.php">
                            <i class="bi bi-map"></i> Carte
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal de la page -->
    <div class="container my-5">
        <!-- Section de recherche -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="card search-card fade-in">
                    <div class="card-body p-4">
                        <!-- Formulaire de recherche -->
                        <form id="searchForm">
                            <div class="row g-3 align-items-end">
                                <!-- Champ pour la marque de l'onduleur -->
                                <div class="col-md-4">
                                    <label for="onduleur" class="form-label">
                                        <i class="bi bi-cpu icon-custom"></i>Marque de l'onduleur
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="onduleur" 
                                           placeholder="Ex: SMA, Fronius, Huawei...">
                                </div>
                                <!-- Champ pour la marque des panneaux -->
                                <div class="col-md-4">
                                    <label for="panneaux" class="form-label">
                                        <i class="bi bi-grid-3x3-gap icon-custom"></i>Marque des panneaux
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="panneaux" 
                                           placeholder="Ex: SunPower, LG, Jinko...">
                                </div>
                                <!-- Champ pour le département -->
                                <div class="col-md-4">
                                    <label for="departement" class="form-label">
                                        <i class="bi bi-geo-alt icon-custom"></i>Département
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="departement" 
                                           placeholder="Ex: 29, 35, 56...">
                                </div>
                                <!-- Bouton de soumission du formulaire -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-search btn-lg">
                                        <span class="search-text">
                                            <i class="bi bi-search me-2"></i>Rechercher
                                        </span>
                                        <span class="loading-spinner">
                                            <span class="spinner-border spinner-border-sm me-2"></span>Recherche...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des résultats de recherche -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card results-card fade-in">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title text-center mb-0">
                            <i class="bi bi-list-ul icon-custom"></i>Résultats de recherche
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Conteneur où les résultats seront affichés dynamiquement -->
                        <div id="resultsContainer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="footer-custom py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
                        <p>
                            Mathis CHARTIER / Mathieu GICQUEL--BOURDEAU / Alexis ROCHON--SANZ
                        <p>
                    </div>
                    <div class="text-center">
                        <small>CIR2 2024/2025</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts JS Bootstrap et script personnalisé pour la recherche -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="../js/recherche.js"></script>
</body>
</html>