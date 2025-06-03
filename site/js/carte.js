// Initialisation de la carte Leaflet centrée sur la France
let map = L.map('map').setView([46.603354, 1.888334], 6);
// Groupe de marqueurs pour pouvoir les gérer facilement (effacer/ajouter)
let markersGroup = L.layerGroup().addTo(map);

// Ajout de la couche de tuiles OpenStreetMap avec attribution
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Données simulées des installations photovoltaïques par département et année
const installationsData = {
    // Exemple pour le département 13 (Bouches-du-Rhône)
    '13': {
        '2020': [
            // Chaque objet représente une installation
            { id: 1, nom: 'Installation Marseille Nord', lat: 43.3182, lng: 5.3698, puissance: 2.5, localite: 'Marseille' },
            // ...
        ],
        '2021': [
            // ...
        ]
    },
    // Autres départements...
    // 29, 33, 69, 84
};

// Gestion de la soumission du formulaire de recherche
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche le rechargement de la page
    rechercherInstallations(); // Lance la recherche
});

// Fonction principale de recherche et d'affichage des installations
function rechercherInstallations() {
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

    // Simulation d'un délai de chargement (1 seconde)
    setTimeout(() => {
        // Efface les anciens marqueurs de la carte
        markersGroup.clearLayers();

        // Récupère les installations correspondant aux critères
        const installations = installationsData[departement]?.[annee] || [];

        // Si aucune installation trouvée, affiche un message et arrête
        if (installations.length === 0) {
            showToast('Aucune installation trouvée pour ces critères');
            document.getElementById('loading').style.display = 'none';
            btn.classList.remove('loading');
            return;
        }

        // Calcule les statistiques : nombre, puissance totale et moyenne
        const nbInstallations = installations.length;
        const puissanceTotale = installations.reduce((sum, inst) => sum + inst.puissance, 0);
        const puissanceMoyenne = puissanceTotale / nbInstallations;

        // Affiche les statistiques dans la page
        document.getElementById('nb-installations').textContent = nbInstallations;
        document.getElementById('puissance-totale').textContent = puissanceTotale.toFixed(1);
        document.getElementById('puissance-moyenne').textContent = puissanceMoyenne.toFixed(1);
        document.getElementById('stats').style.display = 'flex';

        // Ajoute un marqueur pour chaque installation sur la carte
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

        // Ajuste la vue de la carte pour englober tous les marqueurs
        if (installations.length > 0) {
            const group = new L.featureGroup(markersGroup.getLayers());
            map.fitBounds(group.getBounds().pad(0.1));
        }

        // Cache le loading et enlève l'animation du bouton
        document.getElementById('loading').style.display = 'none';
        btn.classList.remove('loading');
    }, 1000);
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

// Fonction appelée lors du clic sur "Voir détails" dans une popup
function voirDetails(installationId) {
    showToast(`Redirection vers la page détail de l'installation ID: ${installationId}`);
    // Ici, on pourrait rediriger vers une page de détail réelle
    // window.location.href = `detail.html?id=${installationId}`;
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
