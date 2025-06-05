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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #28C1B9;
            --secondary-color: #26c6da;
            --tertiary-color: #00acc1;
        }

        body {
            min-height: 100vh;
            background: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
}


        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Navbar personnalisée */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color), var(--tertiary-color));
    color: white;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.8rem;
            color: #ffd700;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 25px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Container de connexion */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.1),
                0 8px 32px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--tertiary-color));
            border-radius: 20px 20px 0 0;
        }

        .login-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
            font-weight: 600;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-title i {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 193, 185, 0.25);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--tertiary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 193, 185, 0.4);
            background: linear-gradient(135deg, #1fa39a 0%, #1eb5c7 50%, #00939e 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-weight: 500;
            text-align: center;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pied de page */
        .footer-custom {
             background: linear-gradient(135deg, var(--primary-color), var(--secondary-color), var(--tertiary-color));
    color: white;
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-custom small {
            display: block;
            margin: 0.2rem 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-box {
                margin: 1rem;
                padding: 2rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }

        /* Animation d'entrée */
        .login-box {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
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
                                    <select class="form-control form-control-custom" id="departement">
                                        <option value="">Chargement...</option>
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
                                        <option value="2004">2004</option>
                                        <option value="2005">2005</option>
                                        <option value="2006">2006</option>
                                        <option value="2007">2007</option>
                                        <option value="2008">2008</option>
                                        <option value="2009">2009</option>
                                        <option value="2010">2010</option>
                                        <option value="2011">2011</option>
                                        <option value="2012">2012</option>
                                        <option value="2013">2013</option>
                                        <option value="2014">2014</option>
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
                    <div class="text-center mt-3">
                    <a href="../back/login.php" class="btn btn-outline-secondary">
                        <i class="bi bi-gear-fill"></i> Connexion
                    </a>
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