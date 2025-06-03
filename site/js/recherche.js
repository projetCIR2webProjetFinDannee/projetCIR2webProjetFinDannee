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
    }, 800);
}

function showDetailPage(installationId) {
            // Simulation de données détaillées d'installation
            const detailData = {
                1: {
                    id: "INST-2024-001",
                    date: "15/03/2024",
                    longitude: -4.0962,
                    latitude: 48.0000,
                    adresse: "29000 Quimper",
                    surface: "45 m²",
                    puissance: "5.2 kW",
                    nbPanneaux: 12,
                    nbOndulateurs: 1,
                    marqueOnduleur: "SMA",
                    modeleOnduleur: "Sunny Boy 5.0",
                    marquePanneaux: "SunPower",
                    modelePanneaux: "SPR-X22-370",
                    orientation: "Sud",
                    inclinaison: "30°",
                    installateur: "Solar Tech Bretagne",
                    productionAnnuelle: "calculable",
                    economieAnnuelle: "calculable",
                    co2Evite: "claculable"
                },
                2: {
                    id: "INST-2024-002", 
                    date: "22/02/2024",
                    longitude: -1.6750,
                    latitude: 48.1173,
                    adresse: "35000 Rennes",
                    surface: "32 m²",
                    puissance: "3.8 kW",
                    nbPanneaux: 8,
                    nbOndulateurs: 1,
                    marqueOnduleur: "Fronius",
                    modeleOnduleur: "Primo 4.0-1",
                    marquePanneaux: "LG",
                    modelePanneaux: "LG475N2W-A6",
                    orientation: "Sud-Est",
                    inclinaison: "35°",
                    installateur: "Eco Solar 35",
                    productionAnnuelle: "calculable",
                    economieAnnuelle: "calculable",
                    co2Evite: "claculable"
                },
                3: {
                    id: "INST-2024-003",
                    date: "08/01/2024", 
                    longitude: -3.3662,
                    latitude: 47.7500,
                    adresse: "56100 Lorient",
                    surface: "56 m²",
                    puissance: "6.4 kW",
                    nbPanneaux: 16,
                    nbOndulateurs: 1,
                    marqueOnduleur: "Huawei",
                    modeleOnduleur: "SUN2000-6KTL-M1",
                    marquePanneaux: "Jinko",
                    modelePanneaux: "JKM400M-54HL4-V",
                    orientation: "Sud-Ouest",
                    inclinaison: "28°",
                    installateur: "Morbihan Energie Verte",
                    productionAnnuelle: "calculable",
                    economieAnnuelle: "calculable",
                    co2Evite: "claculable"
                }
            };

            const data = detailData[installationId];
            if (!data) return;

            // Créer la page de détail
            const detailHTML = `
                <div class="container my-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <!-- Header avec bouton retour -->
                            <div class="d-flex align-items-center mb-4">
                                <button class="tn btn-primary btn-search btn-lg" onclick="hideDetailPage()">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    <small>
                                    Retour à la recherche
                                    </small>
                                </button>
                                <h2 class="mb-0">
                                    Détail de l'installation
                                </h2>
                            </div>

                            <!-- Carte principale d'information -->
                            <div class="card search-card mb-4">
                                <div class="card-header bg-transparent border-0 pt-4">
                                    <h4 class="text-center mb-0">Installation ${data.id}</h4>
                                    <p class="text-center text-muted mb-0">Installée le ${data.date}</p>
                                </div>
                                <div class="card-body p-4">
                                    
                                    <!-- Informations générales -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h5><i class="bi bi-geo-alt icon-custom"></i>Localisation</h5>
                                            <p class="mb-3">${data.latitude}  ${data.longitude} ,${data.adresse}</p>
                                            
                                            <h5><i class="bi bi-rulers icon-custom"></i>Caractéristiques</h5>
                                            <ul class="list-unstyled">
                                                <li><strong>Surface:</strong> ${data.surface}</li>
                                                <li><strong>Puissance totale:</strong> ${data.puissance}</li>
                                                <li><strong>Nombre de panneaux:</strong> ${data.nbPanneaux}</li>
                                                <li><strong>Nombre d'ondulateurs:</strong> ${data.nbOndulateurs}</li>
                                                <li><strong>Orientation:</strong> ${data.orientation}</li>
                                                <li><strong>Inclinaison:</strong> ${data.inclinaison}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5><i class="bi bi-cpu icon-custom"></i>Équipements</h5>
                                            <div class="bg-light p-3 rounded mb-3">
                                                <h6>Onduleur</h6>
                                                <p class="mb-1"><strong>Marque:</strong> ${data.marqueOnduleur}</p>
                                                <p class="mb-0"><strong>Modèle:</strong> ${data.modeleOnduleur}</p>
                                            </div>
                                            <div class="bg-light p-3 rounded">
                                                <h6>Panneaux photovoltaïques</h6>
                                                <p class="mb-1"><strong>Marque:</strong> ${data.marquePanneaux}</p>
                                                <p class="mb-1"><strong>Modèle:</strong> ${data.modelePanneaux}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Performances et économies -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded bg-success bg-opacity-10">
                                                <i class="bi bi-lightning-charge fs-1 text-success"></i>
                                                <h5 class="text-success">${data.productionAnnuelle}</h5>
                                                <p class="mb-0">Production annuelle estimée</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded bg-warning bg-opacity-10">
                                                <i class="bi bi-currency-euro fs-1 text-warning"></i>
                                                <h5 class="text-warning">${data.economieAnnuelle}</h5>
                                                <p class="mb-0">Économies annuelles</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded bg-info bg-opacity-10">
                                                <i class="bi bi-tree fs-1 text-info"></i>
                                                <h5 class="text-info">${data.co2Evite}</h5>
                                                <p class="mb-0">CO₂ évité par an</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informations commerciales -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5><i class="bi bi-building icon-custom"></i>Installateur</h5>
                                            <p class="mb-3">${data.installateur}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="text-center">
                                <button class="btn btn-primary me-3" onclick="generatePDF(detailData[${installationId}])">
                                    <i class="bi bi-download me-2"></i>Télécharger le rapport PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Masquer le contenu principal et afficher les détails
            document.querySelector('.container.my-5').style.display = 'none';
            document.querySelector('.footer-custom').style.display = 'none';
            document.body.insertAdjacentHTML('beforeend', `<div id="detailPage">${detailHTML}</div>`);
            
            // Scroll vers le haut
            window.scrollTo(0, 0);
        }

        function hideDetailPage() {
            const detailPage = document.getElementById('detailPage');
            if (detailPage) {
                detailPage.remove();
            }
            document.querySelector('.container.my-5').style.display = 'block';
            document.querySelector('.footer-custom').style.display = 'block';
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

async function downloadPDF(id) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const data = {
        1: { titre: "Installation INST-2024-001", details: "12 panneaux SunPower - 5.2 kW à Quimper" },
        2: { titre: "Installation INST-2024-002", details: "8 panneaux LG - 3.8 kW à Rennes" },
        3: { titre: "Installation INST-2024-003", details: "16 panneaux Jinko - 6.4 kW à Lorient" }
    };

    const info = data[id];
    if (!info) {
        alert("Données manquantes pour générer le PDF.");
        return;
    }

    doc.setFontSize(16);
    doc.text(info.titre, 20, 20);
    doc.setFontSize(12);
    doc.text(info.details, 20, 40);

    doc.save(`Rapport_${info.titre.replace(/\s+/g, "_")}.pdf`);
}

let detailData = {};

async function generatePDF(data) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Titre
    doc.setFontSize(18);
    doc.text(`Rapport d'installation - ${data.id}`, 20, 20);

    // Informations principales
    doc.setFontSize(12);
    doc.text(`Date d'installation : ${data.date}`, 20, 30);
    doc.text(`Adresse : ${data.adresse}`, 20, 40);
    doc.text(`Coordonnées GPS : ${data.latitude}, ${data.longitude}`, 20, 50);
    
    // Caractéristiques techniques
    doc.autoTable({
        startY: 60,
        head: [['Catégorie', 'Valeur']],
        body: [
            ['Surface', data.surface],
            ['Puissance totale', data.puissance],
            ['Nombre de panneaux', data.nbPanneaux],
            ['Nombre d’ondulateurs', data.nbOndulateurs],
            ['Orientation', data.orientation],
            ['Inclinaison', data.inclinaison],
            ['Marque Onduleur', data.marqueOnduleur],
            ['Modèle Onduleur', data.modeleOnduleur],
            ['Marque Panneaux', data.marquePanneaux],
            ['Modèle Panneaux', data.modelePanneaux],
        ],
    });

    // Performances estimées
    doc.autoTable({
        startY: doc.lastAutoTable.finalY + 10,
        head: [['Indicateur', 'Valeur']],
        body: [
            ['Production annuelle estimée', data.productionAnnuelle],
            ['Économies annuelles', data.economieAnnuelle],
            ['CO₂ évité par an', data.co2Evite],
        ],
    });

    // Installateur
    doc.text(`Installateur : ${data.installateur}`, 20, doc.lastAutoTable.finalY + 20);

    // Sauvegarde du fichier
    doc.save(`rapport-${data.id}.pdf`);
}
