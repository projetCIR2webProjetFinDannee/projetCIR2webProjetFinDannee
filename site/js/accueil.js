// Script JavaScript pour afficher les statistiques PHOTOVOLTIS avec diagnostic d'erreurs amélioré
document.addEventListener('DOMContentLoaded', function() {
    
    // Configuration de l'API
    const API_URL = '../api/request.php';
    
    // Paramètres par défaut
    const DEFAULT_YEAR = new Date().getFullYear();
    const DEFAULT_REGION = 'all';
    
    // Fonction pour récupérer les données depuis votre API avec diagnostic amélioré
    async function chargerDonnees(year = DEFAULT_YEAR, region = DEFAULT_REGION) {
        try {
            const url = `${API_URL}?type=stats&year=${year}&region=${region}`;
            console.log('🔍 Requête vers:', url);
            
            const response = await fetch(url);
            console.log('📡 Status de la réponse:', response.status, response.statusText);
            
            // Récupérer le texte brut de la réponse
            const text = await response.text();
            console.log('📋 Réponse brute:', text.substring(0, 200) + (text.length > 200 ? '...' : ''));
            
            // Vérifier si la réponse est vide
            if (!text || text.trim() === '') {
                throw new Error('Réponse vide de l\'API');
            }
            
            // Vérifier si c'est une erreur simple
            if (text === 'error') {
                throw new Error('Erreur retournée par l\'API');
            }
            
            // Vérifier si la réponse contient du HTML (erreur du serveur)
            if (text.trim().startsWith('<') || text.includes('<!DOCTYPE')) {
                console.error('🚨 Réponse HTML détectée (erreur serveur):', text);
                throw new Error('Le serveur a retourné une page d\'erreur HTML au lieu de JSON');
            }
            
            // Vérifier si c'est une erreur de connexion à la base de données
            if (text.includes('Connection') || text.includes('MySQL') || text.includes('database')) {
                console.error('🗄️ Erreur de base de données détectée:', text);
                throw new Error('Erreur de connexion à la base de données');
            }
            
            // Tentative de parsing JSON
            try {
                const data = JSON.parse(text);
                console.log('✅ JSON parsé avec succès:', data);
                return data;
            } catch (jsonError) {
                console.error('❌ Erreur de parsing JSON:', jsonError);
                console.error('📄 Contenu qui a causé l\'erreur:', text);
                throw new Error(`Réponse invalide de l'API: ${jsonError.message}`);
            }
            
        } catch (error) {
            console.error('💥 Erreur lors du chargement des données:', error);
            
            // Afficher plus de détails sur l'erreur
            if (error.message.includes('fetch')) {
                console.error('🌐 Problème de réseau ou URL incorrecte');
            }
            
            return null;
        }
    }
    
    // Fonction pour tester la connectivité de l'API
    async function testerAPI() {
        console.log('🧪 Test de connectivité de l\'API...');
        
        try {
            // Test simple sans paramètres
            const response = await fetch(API_URL);
            const text = await response.text();
            
            console.log('🔬 Test de base - Status:', response.status);
            console.log('🔬 Test de base - Réponse:', text.substring(0, 100));
            
            if (response.status === 404) {
                console.error('❌ L\'API n\'existe pas à cette adresse');
                return false;
            }
            
            if (response.status >= 500) {
                console.error('❌ Erreur serveur (500+)');
                return false;
            }
            
            return true;
            
        } catch (error) {
            console.error('❌ Impossible de joindre l\'API:', error);
            return false;
        }
    }
    
    // Fonction pour animer les chiffres
    function animerChiffre(element, valeurFinale, duree = 1500) {
        if (!valeurFinale || isNaN(valeurFinale)) {
            element.textContent = 'N/A';
            element.className += ' text-muted';
            return;
        }
        
        const valeur = parseInt(valeurFinale);
        const increment = valeur / (duree / 16);
        let valeurActuelle = 0;
        
        const timer = setInterval(() => {
            valeurActuelle += increment;
            if (valeurActuelle >= valeur) {
                valeurActuelle = valeur;
                clearInterval(timer);
            }
            element.textContent = Math.floor(valeurActuelle).toLocaleString('fr-FR');
        }, 16);
    }
    
    // Fonction pour afficher un spinner
    function afficherSpinner(element) {
        element.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';
    }
    
    // Fonction pour créer un élément d'affichage simple
    function creerElementAffichage(parent, classe) {
        const span = document.createElement('span');
        span.className = `fw-bold ${classe} ms-2`;
        parent.appendChild(span);
        return span;
    }
    
    // Fonction principale pour remplir les statistiques
    async function afficherStatistiques(year = DEFAULT_YEAR, region = DEFAULT_REGION) {
        console.log('📊 Début du chargement des statistiques...');
        
        const listeItems = document.querySelectorAll('.list-group-item');
        
        if (listeItems.length < 7) {
            console.error('❌ Pas assez d\'éléments dans la liste HTML');
            afficherErreur('Structure HTML incomplète');
            return;
        }
        
        // Créer les éléments d'affichage
        const elements = {
            nb_installs: creerElementAffichage(listeItems[0], 'text-primary'),
            nb_inst_year: creerElementAffichage(listeItems[1], 'text-info'),
            nb_inst_reg: creerElementAffichage(listeItems[2], 'text-success'),
            nb_inst_year_reg: creerElementAffichage(listeItems[3], 'text-warning'),
            nb_installers: creerElementAffichage(listeItems[4], 'text-danger'),
            nb_ond_brands: creerElementAffichage(listeItems[5], 'text-dark'),
            nb_panels_brands: creerElementAffichage(listeItems[6], 'text-secondary')
        };
        
        // Afficher les spinners
        Object.values(elements).forEach(el => {
            afficherSpinner(el);
        });
        
        // Tester l'API d'abord
        const apiOK = await testerAPI();
        if (!apiOK) {
            afficherErreur('API inaccessible');
            return;
        }
        
        // Charger et afficher les données
        const donnees = await chargerDonnees(year, region);
        
        if (!donnees) {
            afficherErreur('Impossible de charger les données');
            return;
        }
        
        console.log('✅ Données reçues:', donnees);
        
        // Remplir chaque statistique
        const mappings = [
            { key: 'nb_installs', element: elements.nb_installs },
            { key: 'nb_inst_year', element: elements.nb_inst_year },
            { key: 'nb_inst_reg', element: elements.nb_inst_reg },
            { key: 'nb_inst_year_reg', element: elements.nb_inst_year_reg },
            { key: 'nb_installers', element: elements.nb_installers },
            { key: 'nb_ond_brands', element: elements.nb_ond_brands },
            { key: 'nb_panels_brands', element: elements.nb_panels_brands }
        ];
        
        mappings.forEach(({ key, element }) => {
            if (donnees[key] !== undefined) {
                element.innerHTML = '';
                animerChiffre(element, donnees[key]);
            } else {
                element.innerHTML = '<span class="text-muted">N/A</span>';
                console.warn(`⚠️ Clé manquante dans les données: ${key}`);
            }
        });
        
        // Ajouter des informations contextuelles
        ajouterInfosContextuelles(year, region);
    }
    
    // Fonction pour ajouter des informations sur l'année et la région
    function ajouterInfosContextuelles(year, region) {
        const container = document.querySelector('.card-header h5');
        if (container && year && region) {
            // Supprimer l'ancienne info si elle existe
            const oldInfo = container.querySelector('small');
            if (oldInfo) oldInfo.remove();
            
            const info = document.createElement('small');
            info.className = 'text-muted d-block mt-1';
            info.textContent = `Données pour ${year} - ${region === 'all' ? 'Toutes régions' : region}`;
            container.appendChild(info);
        }
    }
    
    // Fonction améliorée pour afficher les erreurs
    function afficherErreur(message = 'Erreur inconnue') {
        console.error('🚨 Affichage de l\'erreur:', message);
        
        const listeItems = document.querySelectorAll('.list-group-item');
        listeItems.forEach((item) => {
            const span = item.querySelector('span');
            if (span) {
                span.innerHTML = '<span class="text-danger">Erreur</span>';
            }
        });
        
        // Afficher une alerte détaillée
        const container = document.querySelector('.container') || document.body;
        
        // Supprimer les anciennes alertes
        const oldAlerts = container.querySelectorAll('.alert-api-error');
        oldAlerts.forEach(alert => alert.remove());
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show mt-3 alert-api-error';
        alert.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <strong>Erreur de chargement des statistiques</strong><br>
                    <small>${message}</small>
                </div>
            </div>
            <hr>
            <div class="mb-0">
                <strong>Solutions possibles :</strong>
                <ul class="mb-2 mt-2">
                    <li>Vérifiez que le fichier <code>../api/request.php</code> existe</li>
                    <li>Vérifiez la connexion à la base de données</li>
                    <li>Regardez les logs du serveur pour plus de détails</li>
                    <li>Testez l'API directement dans votre navigateur</li>
                </ul>
                <button onclick="window.location.reload()" class="btn btn-sm btn-outline-danger">
                    Recharger la page
                </button>
                <button onclick="testerAPIDepuisConsole()" class="btn btn-sm btn-outline-info ms-2">
                    Tester l'API
                </button>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        if (container.firstChild) {
            container.insertBefore(alert, container.firstChild);
        } else {
            container.appendChild(alert);
        }
    }
    
    // Fonction pour tester l'API depuis la console
    window.testerAPIDepuisConsole = async function() {
        console.log('🧪 Test manuel de l\'API...');
        await testerAPI();
        const result = await chargerDonnees();
        if (result) {
            console.log('✅ L\'API fonctionne maintenant !');
            window.location.reload();
        }
    };
    
    // Fonction pour rafraîchir avec de nouveaux paramètres
    window.rafraichirStatistiques = async function(year = DEFAULT_YEAR, region = DEFAULT_REGION) {
        console.log(`🔄 Rafraîchissement des statistiques pour ${year} - ${region}...`);
        
        // Nettoyer les anciens éléments
        const spans = document.querySelectorAll('.list-group-item span');
        spans.forEach(span => span.remove());
        
        await afficherStatistiques(year, region);
    };
    
    // Fonction pour créer l'interface de sélection des paramètres
    function creerInterfaceParametres() {
        const container = document.querySelector('.card-header') || document.querySelector('.container');
        if (!container) return;
        
        // Vérifier si l'interface existe déjà
        if (document.querySelector('#interface-parametres')) return;
        
        const interfaceDiv = document.createElement('div');
        interfaceDiv.id = 'interface-parametres';
        interfaceDiv.className = 'row g-3 align-items-end mb-3 p-3 bg-light rounded';
        
        interfaceDiv.innerHTML = `
            <div class="col-md-4">
                <label for="select-annee" class="form-label small fw-bold text-muted">ANNÉE</label>
                <select id="select-annee" class="form-select form-select-sm">
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                    <option value="2016">2016</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    <option value="2013">2013</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="select-region" class="form-label small fw-bold text-muted">RÉGION</label>
                <select id="select-region" class="form-select form-select-sm">
                    <option value="all">🇫🇷 Toutes les régions</option>
                    <option value="84">🏔️ Auvergne-Rhône-Alpes</option>
                    <option value="27">🍷 Bourgogne-Franche-Comté</option>
                    <option value="53">🌊 Bretagne</option>
                    <option value="24">🏰 Centre-Val de Loire</option>
                    <option value="94">🏝️ Corse</option>
                    <option value="44">🍺 Grand Est</option>
                    <option value="32">⚒️ Hauts-de-France</option>
                    <option value="11">🗼 Île-de-France</option>
                    <option value="28">🧀 Normandie</option>
                    <option value="75">🍷 Nouvelle-Aquitaine</option>
                    <option value="76">☀️ Occitanie</option>
                    <option value="52">🏰 Pays de la Loire</option>
                    <option value="93">🌴 Provence-Alpes-Côte d'Azur</option>
                    <option value="1">🏖️ Guadeloupe</option>
                    <option value="2">🌺 Martinique</option>
                    <option value="3">🌿 Guyane</option>
                    <option value="4">🌋 La Réunion</option>
                    <option value="6">🏝️ Mayotte</option>
                </select>
            </div>
            <div class="col-md-3">
                <button id="btn-actualiser" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-arrow-clockwise"></i> Actualiser
                </button>
            </div>
        `;
        
        // Insérer l'interface
        if (container.classList.contains('card-header')) {
            container.appendChild(interfaceDiv);
        } else {
            container.insertBefore(interfaceDiv, container.firstChild);
        }
        
        // Définir les valeurs par défaut
        document.getElementById('select-annee').value = DEFAULT_YEAR;
        document.getElementById('select-region').value = DEFAULT_REGION;
        
        // Ajouter les événements
        document.getElementById('btn-actualiser').addEventListener('click', function() {
            const year = document.getElementById('select-annee').value;
            const region = document.getElementById('select-region').value;
            
            console.log(`🔄 Actualisation demandée: ${year} - ${region}`);
            rafraichirStatistiques(year, region);
        });
        
        // Actualisation automatique lors du changement
        document.getElementById('select-annee').addEventListener('change', function() {
            const year = this.value;
            const region = document.getElementById('select-region').value;
            rafraichirStatistiques(year, region);
        });
        
        document.getElementById('select-region').addEventListener('change', function() {
            const year = document.getElementById('select-annee').value;
            const region = this.value;
            rafraichirStatistiques(year, region);
        });
    }
    
    // Fonction pour changer l'année ou la région
    window.changerParametres = function() {
        const year = prompt('Entrez l\'année (ex: 2024):', DEFAULT_YEAR);
        const region = prompt('Entrez la région (ou "all" pour toutes):', DEFAULT_REGION);
        
        if (year && region) {
            // Mettre à jour l'interface si elle existe
            const selectAnnee = document.getElementById('select-annee');
            const selectRegion = document.getElementById('select-region');
            
            if (selectAnnee) selectAnnee.value = year;
            if (selectRegion) selectRegion.value = region;
            
            rafraichirStatistiques(year, region);
        }
    };
    
    // Initialisation avec gestion d'erreur
    console.log('📊 Initialisation du module statistiques PHOTOVOLTIS...');
    console.log(`⚙️ Configuration: API=${API_URL}, Année=${DEFAULT_YEAR}, Région=${DEFAULT_REGION}`);
    
    // Créer l'interface de sélection
    creerInterfaceParametres();
    
    afficherStatistiques().then(() => {
        console.log('✅ Statistiques chargées avec succès');
        console.log('💡 Interface de sélection créée');
        console.log('💡 Commandes disponibles:');
        console.log('   - rafraichirStatistiques(year, region)');
        console.log('   - changerParametres()');
        console.log('   - testerAPIDepuisConsole()');
    }).catch(error => {
        console.error('❌ Erreur lors de l\'initialisation:', error);
    });
});

// CSS pour l'affichage
const style = document.createElement('style');
style.textContent = `
    .list-group-item {
        border: none !important;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.1) !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .list-group-item:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transition: background-color 0.3s ease;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    .fw-bold {
        font-size: 1.1rem;
    }
    
    .text-primary { color: #0d6efd !important; }
    .text-info { color: #0dcaf0 !important; }
    .text-success { color: #198754 !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
    .text-dark { color: #212529 !important; }
    .text-secondary { color: #6c757d !important; }
    
    .card-header small {
        font-size: 0.8rem;
        font-weight: normal;
    }
    
    .alert-api-error {
        border-left: 4px solid #dc3545;
    }
    
    .alert-api-error code {
        background-color: rgba(220, 53, 69, 0.1);
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 0.9em;
    }
    
    /* Styles pour l'interface de paramètres */
    #interface-parametres {
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    #interface-parametres:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    #interface-parametres .form-label {
        margin-bottom: 0.25rem;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    #interface-parametres .form-select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        font-size: 0.9rem;
    }
    
    #interface-parametres .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    #btn-actualiser {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    #btn-actualiser:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    #btn-actualiser:active {
        transform: translateY(0);
    }
`;
document.head.appendChild(style);