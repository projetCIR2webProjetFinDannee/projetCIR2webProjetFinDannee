<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PHOTOVOLTIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar-custom {
            background-color: #26c6da;
        }
        .stats-box {
            border: 3px solid #26c6da;
            border-radius: 15px;
            padding: 20px;
            background-color: white;
        }
        .footer {
            background-color: #26c6da;
            color: white;
            padding: 10px 20px;
        }
        .site-logo {
            height: 50px;
            margin-right: 10px;
        }
        .top-link {
            position: absolute;
            top: 10px;
            left: 10px;
        }
    </style>
</head>
<body>

<!-- Lien retour haut de page -->
<a href="#" class="top-link text-decoration-none text-primary"><i class="bi bi-house-door-fill"></i> Accueil</a>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="logo.png" alt="Logo" class="site-logo"> <!-- Remplacez par le vrai logo -->
            <span class="fw-bold text-white">PHOTOVOLTIS</span>
        </div>
        <div class="d-flex gap-4">
            <a class="nav-link text-white" href="#">ACCUEIL</a>
            <a class="nav-link text-white" href="#">RECHERCHE</a>
            <a class="nav-link text-white" href="#">CARTE</a>
        </div>
    </div>
</nav>

<!-- Description -->
<div class="container mt-3">
    <p class="text-muted">Description du site</p>
    <img src="panneaux.jpg" alt="Panneaux solaires" class="img-fluid rounded"> <!-- Remplacez par l’image réelle -->
</div>

<!-- Statistiques -->
<div class="container mt-4">
    <div class="stats-box">
        <ul>
            <li><strong>NOMBRE D’ENREGISTREMENT EN BASE</strong></li>
            <li>NOMBRE D’INSTALLATIONS PAR ANNÉES</li>
            <li>NOMBRE D’INSTALLATION PAR RÉGION</li>
            <li><strong>NOMBRE D’INSTALLATIONS PAR ANNÉES ET PAR RÉGIONS</strong></li>
            <li>NOMBRE D’INSTALLATEURS</li>
            <li>NOMBRE DE MARQUES D’ONDULEURS</li>
            <li>NOMBRE DE MARQUES DE PANNEAUX SOLAIRES</li>
        </ul>
    </div>
</div>

<!-- Footer -->
<div class="footer d-flex justify-content-between align-items-center mt-4">
    <div>
        <span>
            Mathis CHARTIER / Mathieu GICQUEL–BOURDEAU / Alexis ROCHON–SANZ
        </span>
    </div>
    <div>
        CIR2 2024/2025
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
