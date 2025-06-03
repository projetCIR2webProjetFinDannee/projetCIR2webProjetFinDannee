<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PHOTOVOLTIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- CSS commun -->
    <link rel="stylesheet" href="../css/commun.css">
    <link rel="stylesheet" href="../css/accueil.css">
    
</head>
<body>
    <!-- Animation de fond -->
    <div class="animated-bg"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-lightning-charge"></i>
                PHOTOVOLTIS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="accueil.php">
                            <i class="bi bi-house"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recherche.php">
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

    <!-- Description -->
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Bienvenue sur PHOTOVOLTIS</h2>
        <p class="text-muted text-center">Plateforme d’analyse des installations photovoltaïques en France.</p>
        <div class="text-center my-4">
            <img src="../../images/panneaux-solaires.jpg" alt="Panneaux solaires" class="fullwidth-banner">
        </div>
    </div>

    <!-- Statistiques -->
    <div class="container mb-5">
        <div class="card results-card fade-in">
            <div class="card-header bg-transparent border-0 pt-4">
                <h5 class="card-title text-center mb-0">
                    <i class="bi bi-bar-chart icon-custom"></i>Statistiques globales
                </h5>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Nombre d’enregistrements en base :</li>
                    <li class="list-group-item">Nombre d’installations par années :</li>
                    <li class="list-group-item">Nombre d’installations par région :</li>
                    <li class="list-group-item">Nombre d’installations par années et régions :</li>
                    <li class="list-group-item">Nombre d’installateurs :</li>
                    <li class="list-group-item">Nombre de marques d’onduleurs :</li>
                    <li class="list-group-item">Nombre de marques de panneaux solaires :</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
                        <p>Mathis CHARTIER / Mathieu GICQUEL--BOURDEAU / Alexis ROCHON--SANZ</p>
                    </div>
                    <div class="text-center">
                        <small>CIR2 2024/2025</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toast Script -->
    <script>
        function showToast(message) {
            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            document.getElementById('toastMessage').textContent = message;
            toast.show();
        }
    </script>
</body>
</html>
