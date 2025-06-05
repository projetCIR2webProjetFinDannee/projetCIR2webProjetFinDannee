document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée
    setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
        });
    }, 100);

    // Charger les données des selects au chargement de la page
    loadSelectData();
});

// Initialisation de la carte Leaflet centrée sur la France
let map = L.map('map').setView([46.603354, 1.888334], 6);
// Groupe de marqueurs pour pouvoir les gérer facilement (effacer/ajouter)
let markersGroup = L.layerGroup().addTo(map);

// Ajout de la couche de tuiles OpenStreetMap avec attribution
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Gestion de la soumission du formulaire de recherche
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche le rechargement de la page
    rechercherInstallations(); // Lance la recherche
});

async function loadSelectData() {
    try {
        const response = await fetch('../api/request.php?type=select_data');
        const data = await response.json();


        // Remplir le select des départements
        const departementSelect = document.getElementById('departement');
        departementSelect.innerHTML = '<option value="all">Tous les départements</option>';
        data.departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.code;
            option.textContent = `${dept.code} - ${dept.nom}`;
            departementSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Erreur lors du chargement des données des selects:', error);
        
        // Afficher un message d'erreur dans les selects
        const selects = ['departement'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Erreur de chargement</option>';
        });
    }
}

// Fonction principale de recherche et d'affichage des installations
async function rechercherInstallations() {
    // Récupération des valeurs du formulaire
    const departement = document.getElementById('departement').value;
    const annee = document.getElementById('annee').value;

    // Vérifie que les deux champs sont remplis
    if (!departement || !annee) {
        showToast('Veuillez sélectionner un département et une année');
        return;
    }

    // Animation du bouton de recherche
    const btn = document.querySelector('.btn-search');
    btn.classList.add('loading');

    // Affiche l'animation de chargement et masque les stats
    document.getElementById('loading').style.display = 'block';
    document.getElementById('stats').style.display = 'none';

    try {
        // Efface les anciens marqueurs de la carte
        markersGroup.clearLayers();

        // Récupère directement les installations filtrées via l'API
        const locationsResponse = await fetch(`../back/api.php?type=locations&departement=${departement}&annee=${annee}`);
        
        if (!locationsResponse.ok) {
            throw new Error('Erreur lors de la recherche');
        }
        
        const locationsData = await locationsResponse.json();
        const installations = locationsData.locations || [];

        if (installations.length === 0) {
            showToast('Aucune installation trouvée pour ces critères');
            document.getElementById('loading').style.display = 'none';
            btn.classList.remove('loading');
            return;
        }

        // Filtrer les installations avec des coordonnées valides
        const installationsFiltrees = installations.filter(installation => {
            return installation.latitude && installation.longitude && 
                   !isNaN(installation.latitude) && !isNaN(installation.longitude);
        });

        if (installationsFiltrees.length === 0) {
            showToast('Aucune installation trouvée pour cette année dans ce département');
            document.getElementById('loading').style.display = 'none';
            btn.classList.remove('loading');
            return;
        }

        // Calcule les statistiques
        const nbInstallations = installationsFiltrees.length;
        const puissanceTotale = installationsFiltrees.reduce((sum, inst) => sum + (parseFloat(inst.puissance_crete) || 0), 0);
        const puissanceMoyenne = puissanceTotale / nbInstallations;

        // Affiche les statistiques dans la page
        document.getElementById('nb-installations').textContent = nbInstallations;
        document.getElementById('puissance-totale').textContent = (puissanceTotale / 1000).toFixed(1); // Conversion en MW
        document.getElementById('puissance-moyenne').textContent = (puissanceMoyenne / 1000).toFixed(1); // Conversion en MW
        document.getElementById('stats').style.display = 'flex';

        // Ajoute un marqueur pour chaque installation sur la carte
        installationsFiltrees.forEach(installation => {
            const lat = parseFloat(installation.latitude);
            const lng = parseFloat(installation.longitude);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                const marker = L.marker([lat, lng])
                    .bindPopup(`
                        <div class="custom-popup">
                            <div class="popup-title">Installation ${installation.commune || 'Inconnue'}</div>
                            <div class="popup-info"><strong>Commune:</strong> ${installation.commune || 'Non renseignée'}</div>
                            <div class="popup-info"><strong>Code postal:</strong> ${installation.code_postal || 'Non renseigné'}</div>
                            <div class="popup-info"><strong>Puissance crête:</strong> ${installation.puissance_crete ? (installation.puissance_crete / 1000).toFixed(2) + ' kW' : 'Non renseignée'}</div>
                            <div class="popup-info"><strong>Nombre de panneaux:</strong> ${installation.nb_panneaux || 'Non renseigné'}</div>
                            <div class="popup-info"><strong>Date:</strong> ${installation.date ? new Date(installation.date).toLocaleDateString('fr-FR') : 'Non renseignée'}</div>
                            <div class="popup-info"><strong>Installeur:</strong> ${installation.installeur || 'Non renseigné'}</div>
                            <div class="popup-info"><strong>Marque panneaux:</strong> ${installation.marque_panneau || 'Non renseignée'}</div>
                            <div class="popup-info"><strong>Marque onduleur:</strong> ${installation.marque_ondulateur || 'Non renseignée'}</div>
                        </div>
                    `);
                markersGroup.addLayer(marker);
            }
        });

        // Ajuste la vue de la carte pour englober tous les marqueurs
        if (installationsFiltrees.length > 0 && markersGroup.getLayers().length > 0) {
            const group = new L.featureGroup(markersGroup.getLayers());
            map.fitBounds(group.getBounds().pad(0.1));
        }

        showToast(`${nbInstallations} installation(s) trouvée(s) pour ${annee} dans le département ${departement}`);

    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        showToast('Erreur lors de la récupération des données');
    } finally {
        // Cache le loading et enlève l'animation du bouton
        document.getElementById('loading').style.display = 'none';
        btn.classList.remove('loading');
    }
}

// Affiche un toast (notification temporaire) avec le message passé en paramètre
function showToast(message) {
    // Création du conteneur du toast
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';

    // Création du toast lui-même
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

    // Suppression automatique du toast après 3 secondes
    setTimeout(() => {
        toastContainer.remove();
    }, 3000);
}

// Animation d'entrée pour les éléments du formulaire et des stats à l'ouverture de la page
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