<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Photovoltaïque</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4dd0e1;
            --secondary-color: #26c6da;
            --tertiary-color: #00acc1;
        }

        body {
            background: linear-gradient(135deg, #e8f5ff 0%, #f0e8ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--tertiary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border-radius: 4px;
            margin: 0 5px;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .search-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .search-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            background-color: rgba(255,255,255,0.9);
        }

        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(77, 208, 225, 0.25);
            background-color: white;
        }

        .btn-search {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(77, 208, 225, 0.3);
        }

        .btn-search:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(77, 208, 225, 0.4);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--tertiary-color) 100%);
        }

        .btn-search:active {
            transform: translateY(-1px);
        }

        .results-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border-radius: 20px;
        }

        .result-item {
            background: linear-gradient(135deg, #f0fdff 0%, #e8f9fa 100%);
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .result-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s ease;
        }

        .result-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(77, 208, 225, 0.3);
            border-color: var(--secondary-color);
        }

        .result-item:hover::before {
            left: 100%;
        }

        .result-item.selected {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            transform: scale(0.98);
        }

        .footer-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--tertiary-color) 100%);
            color: white;
            margin-top: 50px;
        }

        .footer-custom a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 0.85rem;
            text-transform: uppercase;
            transition: color 0.3s ease;
        }

        .footer-custom a:hover {
            color: white;
            text-decoration: underline;
        }

        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, #e8f5ff, #f0e8ff, #e8f5ff, #f0e8ff);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
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

        .toast-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
        }

        .loading-spinner {
            display: none;
        }

        .icon-custom {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-lightning-charge"></i>
                PHOTOVOLTAÏQUE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showToast('Accueil')">
                            <i class="bi bi-house"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showToast('Recherche')">
                            <i class="bi bi-search"></i> Recherche
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showToast('Carte')">
                            <i class="bi bi-map"></i> Carte
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Search Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="card search-card fade-in">
                    <div class="card-body p-4">
                        <form id="searchForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="onduleur" class="form-label">
                                        <i class="bi bi-cpu icon-custom"></i>Marque de l'onduleur
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="onduleur" 
                                           placeholder="Ex: SMA, Fronius, Huawei...">
                                </div>
                                <div class="col-md-4">
                                    <label for="panneaux" class="form-label">
                                        <i class="bi bi-grid-3x3-gap icon-custom"></i>Marque des panneaux
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="panneaux" 
                                           placeholder="Ex: SunPower, LG, Jinko...">
                                </div>
                                <div class="col-md-4">
                                    <label for="departement" class="form-label">
                                        <i class="bi bi-geo-alt icon-custom"></i>Département
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="departement" 
                                           placeholder="Ex: 29, 35, 56...">
                                </div>
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

        <!-- Results Section -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card results-card fade-in">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title text-center mb-0">
                            <i class="bi bi-list-ul icon-custom"></i>Résultats de recherche
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div id="resultsContainer">
                            <div class="result-item mb-3" onclick="selectResult(this)">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text me-3 text-primary"></i>
                                    <div>
                                        <strong>Installation #1</strong><br>
                                        <small class="text-muted">date/nb/surface/puissance/localisation + lien</small>
                                    </div>
                                </div>
                            </div>
                            <div class="result-item mb-3" onclick="selectResult(this)">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text me-3 text-primary"></i>
                                    <div>
                                        <strong>Installation #2</strong><br>
                                        <small class="text-muted">date/nb/surface/puissance/localisation + lien</small>
                                    </div>
                                </div>
                            </div>
                            <div class="result-item mb-3" onclick="selectResult(this)">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text me-3 text-primary"></i>
                                    <div>
                                        <strong>Installation #3</strong><br>
                                        <small class="text-muted">date/nb/surface/puissance/localisation + lien</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
                        <a href="#" onclick="showToast('Contact')">
                            <i class="bi bi-envelope me-1"></i>Contact
                        </a>
                        <a href="#" onclick="showToast('Chantier')">
                            <i class="bi bi-tools me-1"></i>Chantier
                        </a>
                        <a href="#" onclick="showToast('Mentions légales')">
                            <i class="bi bi-shield-check me-1"></i>Mentions légales
                        </a>
                        <a href="#" onclick="showToast('Réseau social')">
                            <i class="bi bi-share me-1"></i>Réseau social
                        </a>
                        <a href="#" onclick="showToast('Carte')">
                            <i class="bi bi-map me-1"></i>Carte
                        </a>
                    </div>
                    <div class="text-center">
                        <small>&copy; IES 2024 - Tous droits réservés</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast toast-custom" role="alert">
            <div class="toast-header bg-transparent border-0 text-white">
                <i class="bi bi-info-circle me-2"></i>
                <strong class="me-auto">Information</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Message par défaut
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize tooltips and toasts
        document.addEventListener('DOMContentLoaded', function() {
            // Animation d'entrée
            setTimeout(() => {
                document.querySelectorAll('.fade-in').forEach((el, index) => {
                    el.style.animationDelay = `${index * 0.2}s`;
                });
            }, 100);
        });

        // Search form handler
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });

        function performSearch() {
            const searchBtn = document.querySelector('.btn-search');
            const searchText = searchBtn.querySelector('.search-text');
            const loadingSpinner = searchBtn.querySelector('.loading-spinner');
            
            // Show loading state
            searchText.style.display = 'none';
            loadingSpinner.style.display = 'inline';
            searchBtn.disabled = true;

            const onduleur = document.getElementById('onduleur').value;
            const panneaux = document.getElementById('panneaux').value;
            const departement = document.getElementById('departement').value;

            // Simulate search delay
            setTimeout(() => {
                // Hide loading state
                searchText.style.display = 'inline';
                loadingSpinner.style.display = 'none';
                searchBtn.disabled = false;
                
                // Update results
                updateResults(onduleur, panneaux, departement);
                showToast('Recherche effectuée avec succès !');
            }, 2000);
        }

        function updateResults(onduleur, panneaux, departement) {
            const container = document.getElementById('resultsContainer');
            const mockData = [
                {
                    title: `Installation ${onduleur || 'SMA'} - ${panneaux || 'SunPower'}`,
                    details: `15/03/2024 - 12 panneaux ${panneaux || 'SunPower'} - 45m² - 5.2kW - ${departement || '29'} Finistère`,
                    icon: 'bi-lightning-charge'
                },
                {
                    title: `Installation ${onduleur || 'Fronius'} - ${panneaux || 'LG'}`,
                    details: `22/02/2024 - 8 panneaux ${panneaux || 'LG'} - 32m² - 3.8kW - ${departement || '35'} Ille-et-Vilaine`,
                    icon: 'bi-sun'
                },
                {
                    title: `Installation ${onduleur || 'Huawei'} - ${panneaux || 'Jinko'}`,
                    details: `08/01/2024 - 16 panneaux ${panneaux || 'Jinko'} - 56m² - 6.4kW - ${departement || '56'} Morbihan`,
                    icon: 'bi-battery-charging'
                }
            ];

            container.innerHTML = '';
            
            mockData.forEach((data, index) => {
                const resultItem = document.createElement('div');
                resultItem.className = 'result-item mb-3';
                resultItem.onclick = () => selectResult(resultItem);
                resultItem.style.opacity = '0';
                resultItem.style.transform = 'translateY(20px)';
                
                resultItem.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi ${data.icon} me-3 text-primary fs-4"></i>
                        <div class="flex-grow-1">
                            <strong>${data.title}</strong><br>
                            <small class="text-muted">${data.details} + <a href="#" class="text-decoration-none">lien détaillé</a></small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                `;
                
                container.appendChild(resultItem);
                
                // Animate appearance
                setTimeout(() => {
                    resultItem.style.transition = 'all 0.5s ease';
                    resultItem.style.opacity = '1';
                    resultItem.style.transform = 'translateY(0)';
                }, index * 200);
            });
        }

        function selectResult(element) {
            // Remove previous selection
            document.querySelectorAll('.result-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selection
            element.classList.add('selected');
            
            setTimeout(() => {
                element.classList.remove('selected');
                const title = element.querySelector('strong').textContent;
                showToast(`Sélectionné: ${title}`);
            }, 800);
        }

        function showToast(message) {
            const toastElement = document.getElementById('liveToast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            
            const toast = new bootstrap.Toast(toastElement, {
                delay: 3000
            });
            toast.show();
        }

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>