// Script JavaScript pour afficher les statistiques PHOTOVOLTIS depuis la base de données
document.addEventListener('DOMContentLoaded', function() {
    
    // Configuration de l'API
    const API_BASE_URL = '../back/';
    
    // Fonction pour faire des requêtes AJAX
    async function fetchData(endpoint) {
        try {
            const response = await fetch(API_BASE_URL + endpoint);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Erreur lors de la récupération des données:', error);
            return null;
        }
    }
    
    // Fonction pour animer les chiffres
    function animerChiffre(element, valeurFinale, duree = 2000) {
        if (!valeurFinale || isNaN(valeurFinale)) {
            element.textContent = 'N/A';
            return;
        }
        
        const valeurInitiale = 0;
        const increment = valeurFinale / (duree / 16);
        let valeurActuelle = valeurInitiale;
        
        const timer = setInterval(() => {
            valeurActuelle += increment;
            if (valeurActuelle >= valeurFinale) {
                valeurActuelle = valeurFinale;
                clearInterval(timer);
            }
            element.textContent = Math.floor(valeurActuelle).toLocaleString('fr-FR');
        }, 16);
    }
    
    // Fonction pour créer un spinner de chargement
    function creerSpinner() {
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border spinner-border-sm text-primary';
        spinner.setAttribute('role', 'status');
        spinner.innerHTML = '<span class="visually-hidden">Chargement...</span>';
        return spinner;
    }
    
    // Fonction pour afficher les statistiques principales
    async function afficherStatistiquesPrincipales() {
        const listeItems = document.querySelectorAll('.list-group-item');
        
        if (listeItems.length < 7) return;
        
        // 1. Nombre total d'enregistrements
        const spanTotal = document.createElement('span');
        spanTotal.className = 'fw-bold text-primary ms-2';
        spanTotal.appendChild(creerSpinner());
        listeItems[0].appendChild(spanTotal);
        
        const totalData = await fetchData('stats?action=total');
        if (totalData && totalData.total) {
            spanTotal.innerHTML = '';
            animerChiffre(spanTotal, parseInt(totalData.total));
        }
        
        // 2. Installations par années
        const divAnnees = document.createElement('div');
        divAnnees.className = 'mt-2';
        divAnnees.innerHTML = `
            <button class="btn btn-sm btn-outline-primary" onclick="toggleStatistique('annees')">
                <i class="bi bi-calendar"></i> Voir par années
            </button>
            <div id="stat-annees" class="mt-2 d-none">
                <div class="spinner-border spinner-border-sm" role="status"></div>
            </div>
        `;
        listeItems[1].appendChild(divAnnees);
        
        // 3. Installations par région
        const divRegions = document.createElement('div');
        divRegions.className = 'mt-2';
        divRegions.innerHTML = `
            <button class="btn btn-sm btn-outline-success" onclick="toggleStatistique('regions')">
                <i class="bi bi-geo-alt"></i> Voir par régions
            </button>
            <div id="stat-regions" class="mt-2 d-none">
                <div class="spinner-border spinner-border-sm" role="status"></div>
            </div>
        `;
        listeItems[2].appendChild(divRegions);
        
        // 4. Matrice années/régions
        const spanMatrice = document.createElement('span');
        spanMatrice.className = 'fw-bold text-info ms-2';
        spanMatrice.appendChild(creerSpinner());
        listeItems[3].appendChild(spanMatrice);
        
        const matriceData = await fetchData('stats?action=matrice');
        if (matriceData && matriceData.count) {
            spanMatrice.innerHTML = '';
            animerChiffre(spanMatrice, parseInt(matriceData.count));
        }
        
        // 5. Nombre d'installateurs
        const spanInstallateurs = document.createElement('span');
        spanInstallateurs.className = 'fw-bold text-warning ms-2';
        spanInstallateurs.appendChild(creerSpinner());
        listeItems[4].appendChild(spanInstallateurs);
        
        const installateurData = await fetchData('stats?action=installateurs');
        if (installateurData && installateurData.count) {
            spanInstallateurs.innerHTML = '';
            animerChiffre(spanInstallateurs, parseInt(installateurData.count));
        }
        
        // 6. Marques d'onduleurs
        const spanOnduleurs = document.createElement('span');
        spanOnduleurs.className = 'fw-bold text-danger ms-2';
        spanOnduleurs.appendChild(creerSpinner());
        listeItems[5].appendChild(spanOnduleurs);
        
        const onduleurData = await fetchData('stats?action=onduleurs');
        if (onduleurData && onduleurData.count) {
            spanOnduleurs.innerHTML = '';
            animerChiffre(spanOnduleurs, parseInt(onduleurData.count));
        }
        
        // 7. Marques de panneaux
        const spanPanneaux = document.createElement('span');
        spanPanneaux.className = 'fw-bold text-success ms-2';
        spanPanneaux.appendChild(creerSpinner());
        listeItems[6].appendChild(spanPanneaux);
        
        const panneauData = await fetchData('stats?action=panneaux');
        if (panneauData && panneauData.count) {
            spanPanneaux.innerHTML = '';
            animerChiffre(spanPanneaux, parseInt(panneauData.count));
        }
    }
    
    // Fonction pour charger les données détaillées
    async function chargerDonnees(type, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Afficher spinner
        container.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        
        const data = await fetchData(`statistiques.php?action=${type}`);
        
        if (!data || !data.results) {
            container.innerHTML = '<p class="text-muted small">Aucune donnée disponible</p>';
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-sm">';
        
        if (type === 'annees') {
            html += '<thead><tr><th>Année</th><th>Installations</th></tr></thead><tbody>';
            data.results.forEach(row => {
                html += `<tr><td>${row.annee}</td><td class="fw-bold">${parseInt(row.count).toLocaleString('fr-FR')}</td></tr>`;
            });
        } else if (type === 'regions') {
            html += '<thead><tr><th>Région</th><th>Installations</th></tr></thead><tbody>';
            data.results.forEach(row => {
                html += `<tr><td>${row.region}</td><td class="fw-bold">${parseInt(row.count).toLocaleString('fr-FR')}</td></tr>`;
            });
        }
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }
    
    // Fonction pour basculer l'affichage des statistiques détaillées
    window.toggleStatistique = async function(type) {
        const containerId = `stat-${type}`;
        const container = document.getElementById(containerId);
        
        if (!container) return;
        
        if (container.classList.contains('d-none')) {
            container.classList.remove('d-none');
            // Charger les données si pas encore fait
            if (container.innerHTML.includes('spinner-border')) {
                await chargerDonnees(type, containerId);
            }
        } else {
            container.classList.add('d-none');
        }
    };
    
    // Fonction pour gérer les erreurs
    function gererErreur(message) {
        console.error('Erreur PHOTOVOLTIS:', message);
        
        // Afficher un message d'erreur discret
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
        alertDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i>
            Certaines statistiques n'ont pas pu être chargées. Vérifiez votre connexion.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
        }
    }
    
    // Fonction d'initialisation
    async function initialiser() {
        try {
            console.log('🌞 PHOTOVOLTIS - Chargement des statistiques...');
            await afficherStatistiquesPrincipales();
            console.log('📊 Statistiques chargées avec succès');
        } catch (error) {
            gererErreur('Erreur lors du chargement des statistiques');
        }
    }
    
    // Démarrer le chargement
    initialiser();
    
    // Fonction utilitaire pour rafraîchir les données
    window.rafraichirStatistiques = function() {
        location.reload();
    };
});

// CSS pour améliorer l'affichage
const style = document.createElement('style');
style.textContent = `
    .list-group-item {
        border: none !important;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.1) !important;
    }
    
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    #stat-annees, #stat-regions {
        max-height: 300px;
        overflow-y: auto;
    }
`;
document.head.appendChild(style);