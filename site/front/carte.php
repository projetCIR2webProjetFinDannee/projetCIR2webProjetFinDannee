<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des Installations Photovoltaïques</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <link rel="stylesheet" href="../css/commun.css">
    <link rel="stylesheet" href="../css/carte.css">
</head>
<body>
    <div class="animated-bg"></div>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="accueil.php">
                <i class="bi bi-lightning-charge"></i>
                PHOTOVOLTIS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
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
        <!-- Title Section -->
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

        <!-- Search Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card search-card fade-in">
                    <div class="card-body p-4">
                        <form id="searchForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label for="departement" class="form-label">
                                        <i class="bi bi-geo-alt icon-custom"></i>Département
                                    </label>
                                    <select class="form-select form-control-custom" id="departement">
                                        <option value="">Sélectionner un département</option>
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
                                <div class="col-md-5">
                                    <label for="annee" class="form-label">
                                        <i class="bi bi-calendar icon-custom"></i>Année d'installation
                                    </label>
                                    <select class="form-select form-control-custom" id="annee">
                                        <option value="">Sélectionner une année</option>
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

        <!-- Statistiques -->
        <div class="row mb-4 justify-content-center" id="stats" style="display: none;">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="stats-number" id="nb-installations">0</div>
                            <div class="stats-label">Installations</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="stats-number" id="puissance-totale">0</div>
                            <div class="stats-label">MW Totaux</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="stats-number" id="puissance-moyenne">0</div>
                            <div class="stats-label">MW Moyenne</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card results-card fade-in">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title text-center mb-0">
                            <i class="bi bi-map icon-custom"></i>Carte des installations
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Loading -->
                        <div class="loading text-center" id="loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des données...</p>
                        </div>
                        <div id="map"></div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script>
        // Initialisation de la carte
        let map = L.map('map').setView([46.603354, 1.888334], 6);
        let markersGroup = L.layerGroup().addTo(map);

        // Ajout de la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Données simulées d'installations photovoltaïques
        const installationsData = {
            '13': { // Bouches-du-Rhône
                '2020': [
                    { id: 1, nom: 'Installation Marseille Nord', lat: 43.3182, lng: 5.3698, puissance: 2.5, localite: 'Marseille' },
                    { id: 2, nom: 'Parc Solaire Aix', lat: 43.5297, lng: 5.4474, puissance: 8.2, localite: 'Aix-en-Provence' },
                    { id: 3, nom: 'Centre Arles', lat: 43.6768, lng: 4.6309, puissance: 3.7, localite: 'Arles' }
                ],
                '2021': [
                    { id: 4, nom: 'Solaire Aubagne', lat: 43.2942, lng: 5.5714, puissance: 4.1, localite: 'Aubagne' },
                    { id: 5, nom: 'Installation Vitrolles', lat: 43.4497, lng: 5.2474, puissance: 6.3, localite: 'Vitrolles' }
                ]
            },
            '29': { // Finistère
                '2020': [
                    { id: 6, nom: 'Parc Brest Océan', lat: 48.3905, lng: -4.4860, puissance: 5.8, localite: 'Brest' },
                    { id: 7, nom: 'Installation Quimper', lat: 47.9960, lng: -4.1024, puissance: 3.2, localite: 'Quimper' }
                ],
                '2022': [
                    { id: 8, nom: 'Solaire Morlaix', lat: 48.5782, lng: -3.8282, puissance: 4.7, localite: 'Morlaix' },
                    { id: 9, nom: 'Centre Concarneau', lat: 47.8722, lng: -3.9178, puissance: 2.9, localite: 'Concarneau' }
                ]
            },
            '33': { // Gironde
                '2019': [
                    { id: 10, nom: 'Parc Bordeaux Métropole', lat: 44.8378, lng: -0.5792, puissance: 12.5, localite: 'Bordeaux' },
                    { id: 11, nom: 'Installation Arcachon', lat: 44.6534, lng: -1.1655, puissance: 7.3, localite: 'Arcachon' }
                ],
                '2021': [
                    { id: 12, nom: 'Solaire Libourne', lat: 44.9147, lng: -0.2405, puissance: 5.1, localite: 'Libourne' },
                    { id: 13, nom: 'Centre Mérignac', lat: 44.8404, lng: -0.6539, puissance: 8.9, localite: 'Mérignac' }
                ]
            },
            '69': { // Rhône
                '2020': [
                    { id: 14, nom: 'Installation Lyon Part-Dieu', lat: 45.7640, lng: 4.8357, puissance: 6.4, localite: 'Lyon' },
                    { id: 15, nom: 'Parc Villeurbanne', lat: 45.7665, lng: 4.8795, puissance: 4.8, localite: 'Villeurbanne' }
                ],
                '2023': [
                    { id: 16, nom: 'Solaire Vaulx-en-Velin', lat: 45.7862, lng: 4.9200, puissance: 9.2, localite: 'Vaulx-en-Velin' }
                ]
            },
            '84': { // Vaucluse
                '2021': [
                    { id: 17, nom: 'Parc Avignon Sud', lat: 43.9493, lng: 4.8059, puissance: 15.7, localite: 'Avignon' },
                    { id: 18, nom: 'Installation Orange', lat: 44.1365, lng: 4.8089, puissance: 11.3, localite: 'Orange' }
                ],
                '2022': [
                    { id: 19, nom: 'Solaire Carpentras', lat: 44.0550, lng: 5.0481, puissance: 7.8, localite: 'Carpentras' }
                ]
            }
        };

        // Gestion du formulaire
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            rechercherInstallations();
        });

        function rechercherInstallations() {
            const departement = document.getElementById('departement').value;
            const annee = document.getElementById('annee').value;

            if (!departement || !annee) {
                showToast('Veuillez sélectionner un département et une année');
                return;
            }

            // Animation du bouton
            const btn = document.querySelector('.btn-search');
            btn.classList.add('loading');

            // Affichage du loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('stats').style.display = 'none';

            // Simulation d'un délai de chargement
            setTimeout(() => {
                // Effacer les anciens marqueurs
                markersGroup.clearLayers();

                // Récupérer les données
                const installations = installationsData[departement]?.[annee] || [];

                if (installations.length === 0) {
                    showToast('Aucune installation trouvée pour ces critères');
                    document.getElementById('loading').style.display = 'none';
                    btn.classList.remove('loading');
                    return;
                }

                // Calculer les statistiques
                const nbInstallations = installations.length;
                const puissanceTotale = installations.reduce((sum, inst) => sum + inst.puissance, 0);
                const puissanceMoyenne = puissanceTotale / nbInstallations;

                // Afficher les statistiques
                document.getElementById('nb-installations').textContent = nbInstallations;
                document.getElementById('puissance-totale').textContent = puissanceTotale.toFixed(1);
                document.getElementById('puissance-moyenne').textContent = puissanceMoyenne.toFixed(1);
                document.getElementById('stats').style.display = 'flex';

                // Ajouter les marqueurs sur la carte
                installations.forEach(installation => {
                    const marker = L.marker([installation.lat, installation.lng])
                        .bindPopup(`
                            <div class="custom-popup">
                                <div class="popup-title">${installation.nom}</div>
                                <div class="popup-info"><strong>Localité:</strong> ${installation.localite}</div>
                                <div class="popup-info"><strong>Puissance:</strong> ${installation.puissance} MW</div>
                                <div class="popup-info"><strong>Année:</strong> ${annee}</div>
                                <a href="#" class="popup-link" onclick="voirDetails(${installation.id})">Voir détails</a>
                            </div>
                        `);
                    markersGroup.addLayer(marker);
                });

                // Ajuster la vue de la carte
                if (installations.length > 0) {
                    const group = new L.featureGroup(markersGroup.getLayers());
                    map.fitBounds(group.getBounds().pad(0.1));
                }

                document.getElementById('loading').style.display = 'none';
                btn.classList.remove('loading');
            }, 1000);
        }

        function showToast(message) {
            // Créer un toast simple
            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.style.background = 'var(--primary-color)';
            toast.style.color = 'white';
            toast.innerHTML = `
                <div class="toast-body">
                    <i class="bi bi-info-circle me-2"></i>${message}
                </div>
            `;
            
            toastContainer.appendChild(toast);
            document.body.appendChild(toastContainer);
            
            setTimeout(() => {
                toastContainer.remove();
            }, 3000);
        }

        function voirDetails(installationId) {
            showToast(`Redirection vers la page détail de l'installation ID: ${installationId}`);
            // Ici vous pourriez rediriger vers une page détail
            // window.location.href = `detail.html?id=${installationId}`;
        }

        // Animation d'entrée pour les éléments
        window.addEventListener('load', function() {
            const elements = document.querySelectorAll('.form-container, .stats-card');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = 'all 0.6s ease';
                    
                    setTimeout(() => {
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 200);
            });
        });
    </script>
</body>
</html> 