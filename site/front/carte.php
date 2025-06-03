<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des Installations Photovoltaïques</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Leaflet CSS pour la carte -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <!-- Feuilles de style personnalisées -->
    <link rel="stylesheet" href="../css/commun.css">
    <link rel="stylesheet" href="../css/carte.css">
</head>
<body>
    <!-- Fond animé -->
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
                        <a class="nav-link" href="recherche.php">
                            <i class="bi bi-search"></i> Recherche
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="carte.php">
                            <i class="bi bi-map"></i> Carte
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Titre principal de la page -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="text-center">
                    <h1 class="title-main fade-in">
                        <i class="bi bi-map icon-title"></i>
                        Carte Interactive des Installations Photovoltaïques
                    </h1>
                </div>
            </div>
        </div>

        <!-- Section de recherche (département et année) -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card search-card fade-in">
                    <div class="card-body p-4">
                        <form id="searchForm">
                            <div class="row g-3 align-items-end">
                                <!-- Sélection du département -->
                                <div class="col-md-5">
                                    <label for="departement" class="form-label">
                                        <i class="bi bi-geo-alt icon-custom"></i>Département
                                    </label>
                                    <select class="form-select form-control-custom" id="departement">
                                        <option value="">Sélectionner un département</option>
                                        <!-- Liste des départements -->
                                        <option value="01">01 - Ain</option>
                                        <option value="02">02 - Aisne</option>
                                        <option value="13">13 - Bouches-du-Rhône</option>
                                        <option value="29">29 - Finistère</option>
                                        <option value="30">30 - Gard</option>
                                        <option value="33">33 - Gironde</option>
                                        <option value="34">34 - Hérault</option>
                                        <option value="44">44 - Loire-Atlantique</option>
                                        <option value="59">59 - Nord</option>
                                        <option value="69">69 - Rhône</option>
                                        <option value="75">75 - Paris</option>
                                        <option value="76">76 - Seine-Maritime</option>
                                        <option value="83">83 - Var</option>
                                        <option value="84">84 - Vaucluse</option>
                                        <option value="85">85 - Vendée</option>
                                    </select>
                                </div>
                                <!-- Sélection de l'année d'installation -->
                                <div class="col-md-5">
                                    <label for="annee" class="form-label">
                                        <i class="bi bi-calendar icon-custom"></i>Année d'installation
                                    </label>
                                    <select class="form-select form-control-custom" id="annee">
                                        <option value="">Sélectionner une année</option>
                                        <!-- Liste des années -->
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>
                                        <option value="2017">2017</option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                    </select>
                                </div>
                                <!-- Bouton de recherche -->
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-search w-100">
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

        <!-- Section statistiques (affichée après recherche) -->
        <div class="row mb-4 justify-content-center" id="stats" style="display: none;">
            <div class="col-lg-10">
                <div class="row">
                    <!-- Nombre d'installations -->
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="stats-number" id="nb-installations">0</div>
                            <div class="stats-label">Installations</div>
                        </div>
                    </div>
                    <!-- Puissance totale -->
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="stats-number" id="puissance-totale">0</div>
                            <div class="stats-label">MW Totaux</div>
                        </div>
                    </div>
                    <!-- Puissance moyenne -->
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="stats-number" id="puissance-moyenne">0</div>
                            <div class="stats-label">MW Moyenne</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section carte interactive -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card results-card fade-in">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title text-center mb-0">
                            <i class="bi bi-map icon-custom"></i>Carte des installations
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Affichage du chargement pendant la récupération des données -->
                        <div class="loading text-center" id="loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des données...</p>
                        </div>
                        <!-- Conteneur de la carte Leaflet -->
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="footer-custom py-4">
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
                        <small>GROUPE 9</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="../js/carte.js"></script>
</body>
</html> 