// Initialise les tooltips et lance une animation d'entrée sur les éléments .fade-in
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée avec délai progressif pour chaque élément .fade-in
    setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
        });
    }, 100);
});

// Gestionnaire de soumission du formulaire de recherche
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche le rechargement de la page
    performSearch();    // Lance la recherche personnalisée
});

// Fonction principale de recherche
function performSearch() {
    const searchBtn = document.querySelector('.btn-search');
    const searchText = searchBtn.querySelector('.search-text');
    const loadingSpinner = searchBtn.querySelector('.loading-spinner');
    
    // Affiche l'état de chargement sur le bouton
    searchText.style.display = 'none';
    loadingSpinner.style.display = 'inline';
    searchBtn.disabled = true;

    // Récupère les valeurs des champs de recherche
    const onduleur = document.getElementById('onduleur').value;
    const panneaux = document.getElementById('panneaux').value;
    const departement = document.getElementById('departement').value;

    // Simule un délai de recherche (2 secondes)
    setTimeout(() => {
        // Cache l'état de chargement
        searchText.style.display = 'inline';
        loadingSpinner.style.display = 'none';
        searchBtn.disabled = false;
        
        // Met à jour les résultats affichés
        updateResults(onduleur, panneaux, departement);
    }, 2000);
}

// Met à jour l'affichage des résultats de recherche
function updateResults(onduleur, panneaux, departement) {
    const container = document.getElementById('resultsContainer');
    // Données fictives pour l'exemple
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

    container.innerHTML = ''; // Vide les anciens résultats
    
    // Pour chaque résultat fictif, crée un élément d'affichage animé
    mockData.forEach((data, index) => {
        const resultItem = document.createElement('div');
        resultItem.className = 'result-item mb-3';
        resultItem.onclick = () => selectResult(resultItem); // Sélectionne le résultat au clic
        resultItem.style.opacity = '0';
        resultItem.style.transform = 'translateY(20px)';
        
        // Structure HTML du résultat
        resultItem.innerHTML = `
            <div class="d-flex align-items-center"<a href="#" class="text-decoration-none" onclick="event.stopPropagation(); showDetailPage(${index + 1})"></a>>
                <i class="bi ${data.icon} me-3 text-primary fs-4"></i>
                <div class="flex-grow-1">
                    <strong>${data.title}</strong><br>
                    <small class="text-muted">${data.details}</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        `;
        
        container.appendChild(resultItem);
        
        // Animation d'apparition progressive
        setTimeout(() => {
            resultItem.style.transition = 'all 0.5s ease';
            resultItem.style.opacity = '1';
            resultItem.style.transform = 'translateY(0)';
        }, index * 200);
    });
}

// Gère la sélection visuelle d'un résultat
function selectResult(element) {
    // Retire la sélection précédente
    document.querySelectorAll('.result-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Ajoute la classe de sélection
    element.classList.add('selected');
    
    // Retire la sélection après un court délai
    setTimeout(() => {
        element.classList.remove('selected');
        const title = element.querySelector('strong').textContent;
    }, 800);
}

// Affiche la page de détail d'une installation
function showDetailPage(installationId) {
    // Données détaillées fictives selon l'ID
    const detailData = {
        1: { /* ... données ... */ },
        2: { /* ... données ... */ },
        3: { /* ... données ... */ }
    };

    const data = detailData[installationId];
    if (!data) return;

    // Génère le HTML de la page de détail
    const detailHTML = `
        <div class="container my-5">
            <!-- ... contenu détaillé ... -->
        </div>
    `;

    // Masque le contenu principal et le footer, puis affiche la page de détail
    document.querySelector('.container.my-5').style.display = 'none';
    document.querySelector('.footer-custom').style.display = 'none';
    document.body.insertAdjacentHTML('beforeend', `<div id="detailPage">${detailHTML}</div>`);
    
    // Remonte la page en haut
    window.scrollTo(0, 0);
}

// Cache la page de détail et réaffiche la recherche
function hideDetailPage() {
    const detailPage = document.getElementById('detailPage');
    if (detailPage) {
        detailPage.remove();
    }
    document.querySelector('.container.my-5').style.display = 'block';
    document.querySelector('.footer-custom').style.display = 'block';
}

// Ajoute un scroll fluide pour les ancres internes
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
