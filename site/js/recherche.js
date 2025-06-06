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

// Fonction pour charger les données des selects
async function loadSelectData() {
    try {
        const response = await fetch('../api/request.php?type=select_data');
        const data = await response.json();
        
        // Remplir le select des marques d'onduleurs
        const onduleurSelect = document.getElementById('onduleur');
        onduleurSelect.innerHTML = '<option value="all">Toutes les marques</option>';
        data.ondulateur_brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            onduleurSelect.appendChild(option);
        });

        // Remplir le select des marques de panneaux
        const panneauxSelect = document.getElementById('panneaux');
        panneauxSelect.innerHTML = '<option value="all">Toutes les marques</option>';
        data.panel_brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            panneauxSelect.appendChild(option);
        });

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
        const selects = ['onduleur', 'panneaux', 'departement'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Erreur de chargement</option>';
        });
    }
}

// changement de l'animation d'entrée pour les résultats
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

    // cache le chargement
    searchText.style.display = 'inline';
    loadingSpinner.style.display = 'none';
    searchBtn.disabled = false;
        
    updateResults(onduleur, panneaux, departement);
}

function updateResults(onduleur, panneaux, departement) {
    const container = document.getElementById('resultsContainer');

    // Correction de l'URL pour correspondre au backend PHP
    fetch(`../api/request.php?type=search&marqueOndulateur=${onduleur || 'all'}&marquePanneaux=${panneaux || 'all'}&numDepartement=${departement || 'all'}`)
        .then(response => response.json())
        .then(async data => {
            container.innerHTML = '';

            // Le backend retourne un objet avec une propriété 'results' qui contient les IDs
            const idList = data.results || [];

            if (!Array.isArray(idList) || idList.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">Aucun résultat trouvé.</div>';
                return;
            }

            // Récupérer les infos pour chaque id
            const dataList = await Promise.all(
                idList.map(async (id) => {
                    try {
                        const res = await fetch(`../api/request.php?type=info&id=${id}`);
                        const data = await res.json();
                        return {
                            id: id,
                            title: `Installation ${data.commune}`,
                            details: `${data.commune} (${data.code_postal}) - ${data.marque_panneau}`,
                            fullData: data
                        };
                    } catch (e) {
                        console.error('Erreur lors du chargement des détails pour l\'ID:', id, e);
                        return { 
                            id: id, 
                            title: `Installation ${id}`, 
                            details: "Erreur de chargement",
                            fullData: null
                        };
                    }
                })
            );

            dataList.forEach((data, index) => {
                const resultItem = document.createElement('div');
                resultItem.className = 'result-item mb-3';
                resultItem.onclick = () => selectResult(resultItem);
                resultItem.style.opacity = '0';
                resultItem.style.transform = 'translateY(20px)';

                resultItem.innerHTML = `
                    <div class="d-flex align-items-center" <a href="#" class="text-decoration-none" onclick="event.stopPropagation(); showDetailPage(${data.id})"></a>>
                        <i class="bi ${data.icon || 'bi-lightning-charge'} me-3 text-primary fs-4"></i>
                        <div class="flex-grow-1">
                            <strong>${data.title}</strong><br>
                            <small class="text-muted">${data.details}</small>
                            <span class="small">
                                ${data.fullData && data.fullData.date ? new Date(data.fullData.date).toLocaleDateString('fr-FR') : ''}
                                ${data.fullData && data.fullData.nb_panneaux ? ' | ' + data.fullData.nb_panneaux + ' panneaux' : ''}
                                ${data.fullData && data.fullData.surface ? ' | ' + data.fullData.surface + ' m²' : ''}
                                ${data.fullData && data.fullData.puissance_crete ? ' | ' + data.fullData.puissance_crete + ' kW' : ''}
                                ${data.fullData && data.fullData.commune ? ' | ' + data.fullData.commune : ''}
                            </span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                `;

                container.appendChild(resultItem);

                // animation d'entrée
                setTimeout(() => {
                    resultItem.style.transition = 'all 0.5s ease';
                    resultItem.style.opacity = '1';
                    resultItem.style.transform = 'translateY(0)';
                }, index * 200);
            });
        })
        .catch(error => {
            container.innerHTML = '<div class="alert alert-danger">Erreur lors de la récupération des résultats.</div>';
            console.error('Erreur lors de la recherche:', error);
        });
}

function selectResult(element) {
    // enlever la sélection des autres éléments
    document.querySelectorAll('.result-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // ajouter la classe 'selected' à l'élément cliqué
    element.classList.add('selected');
    
    setTimeout(() => {
        element.classList.remove('selected');
        const title = element.querySelector('strong').textContent;
    }, 800);
}

// Variable globale pour stocker les données détaillées
let detailData = {};

async function showDetailPage(installationId) {
    // Masquer le contenu principal
    document.querySelector('.container.my-5').style.display = 'none';
    document.querySelector('.footer-custom').style.display = 'none';

    // Supprimer une éventuelle page de détail précédente
    const oldDetail = document.getElementById('detailPage');
    if (oldDetail) oldDetail.remove();

    try {
        // Récupérer les données détaillées depuis le serveur
        const response = await fetch(`../api/request.php?type=info&id=${installationId}`);
        if (!response.ok) throw new Error('Erreur lors de la récupération des détails');
        const data = await response.json();
        if (!data) throw new Error('Données non trouvées');

        // Stocker pour le PDF
        detailData[installationId] = data;

        // Formater les données pour l'affichage
        const formattedData = {
            id: installationId,
            date: data.date || 'Non spécifiée',
            latitude: data.latitude || 'N/A',
            longitude: data.longitude || 'N/A',
            adresse: `${data.commune || 'Commune inconnue'} (${data.code_postal || 'N/A'})`,
            surface: data.surface ? `${data.surface} m²` : 'Non spécifiée',
            puissance: data.puissance_crete ? `${data.puissance_crete} kW` : 'Non spécifiée',
            nbPanneaux: data.nb_panneaux || 'N/A',
            nbOndulateurs: data.nb_ondulateurs || 'N/A',
            orientation: data.orientation ? `${data.orientation}°` : 'Non spécifiée',
            inclinaison: data.pente ? `${data.pente}°` : 'Non spécifiée',
            marqueOnduleur: data.marque_ondulateur || 'Non spécifiée',
            modeleOnduleur: data.modele_ondulateur || 'Non spécifié',
            marquePanneaux: data.marque_panneau || 'Non spécifiée',
            modelePanneaux: data.modele_panneau || 'Non spécifié',
            productionAnnuelle: data.production_pvgis ? `${data.production_pvgis} kWh` : 'Non calculée',
            economieAnnuelle: data.production_pvgis ? `${data.production_pvgis * 21} €` : 'Non calculée', 
            co2Evite: data.production_pvgis ? `${data.production_pvgis * 56} grammes` : 'Non calculée', 
            installateur: data.installeur || 'Non spécifié'
        };

        // Générer le HTML de la page de détail
        const detailHTML = `
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="d-flex align-items-center mb-4">
                            <button class="btn btn-primary btn-search btn-lg" onclick="hideDetailPage()">
                                <i class="bi bi-arrow-left me-2"></i>
                                <small>Retour à la recherche</small>
                            </button>
                            <h2 class="mb-0 ms-3">Détail de l'installation</h2>
                        </div>
                        <div class="card search-card mb-4">
                            <div class="card-header bg-transparent border-0 pt-4">
                                <h4 class="text-center mb-0">Installation ${data.commune}</h4>
                                <p class="text-center text-muted mb-0">Installée le ${formattedData.date}</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5><i class="bi bi-geo-alt icon-custom"></i>Localisation</h5>
                                        <p class="mb-3">${formattedData.latitude}, ${formattedData.longitude}<br>${formattedData.adresse}</p>
                                        <h5><i class="bi bi-rulers icon-custom"></i>Caractéristiques</h5>
                                        <ul class="list-unstyled">
                                            <li><strong>Surface:</strong> ${formattedData.surface}</li>
                                            <li><strong>Puissance crête:</strong> ${formattedData.puissance}</li>
                                            <li><strong>Nombre de panneaux:</strong> ${formattedData.nbPanneaux}</li>
                                            <li><strong>Nombre d'ondulateurs:</strong> ${formattedData.nbOndulateurs}</li>
                                            <li><strong>Orientation:</strong> ${formattedData.orientation}</li>
                                            <li><strong>Inclinaison:</strong> ${formattedData.inclinaison}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5><i class="bi bi-cpu icon-custom"></i>Équipements</h5>
                                        <div class="bg-light p-3 rounded mb-3">
                                            <h6>Onduleur</h6>
                                            <p class="mb-1"><strong>Marque:</strong> ${formattedData.marqueOnduleur}</p>
                                            <p class="mb-0"><strong>Modèle:</strong> ${formattedData.modeleOnduleur}</p>
                                        </div>
                                        <div class="bg-light p-3 rounded">
                                            <h6>Panneaux photovoltaïques</h6>
                                            <p class="mb-1"><strong>Marque:</strong> ${formattedData.marquePanneaux}</p>
                                            <p class="mb-1"><strong>Modèle:</strong> ${formattedData.modelePanneaux}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="text-center p-3 border rounded bg-success bg-opacity-10">
                                            <i class="bi bi-lightning-charge fs-1 text-success"></i>
                                            <h5 class="text-success">${formattedData.productionAnnuelle}</h5>
                                            <p class="mb-0">Production annuelle estimée</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 border rounded bg-warning bg-opacity-10">
                                            <i class="bi bi-currency-euro fs-1 text-warning"></i>
                                            <h5 class="text-warning">${formattedData.economieAnnuelle}</h5>
                                            <p class="mb-0">Économies annuelles</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 border rounded bg-info bg-opacity-10">
                                            <i class="bi bi-tree fs-1 text-info"></i>
                                            <h5 class="text-info">${formattedData.co2Evite}</h5>
                                            <p class="mb-0">CO₂ évité par an</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="bi bi-building icon-custom"></i>Installateur</h5>
                                        <p class="mb-3">${formattedData.installateur}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary me-3" onclick="generatePDF(${installationId})">
                                <i class="bi bi-download me-2"></i>Télécharger le rapport PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Afficher la page de détail
        document.body.insertAdjacentHTML('beforeend', `<div id="detailPage">${detailHTML}</div>`);
        window.scrollTo(0, 0);
    } catch (error) {
        alert("Impossible de charger les détails de l'installation.");
        document.querySelector('.container.my-5').style.display = 'block';
        document.querySelector('.footer-custom').style.display = 'block';
        console.error('Erreur lors du chargement des détails:', error);
    }
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

async function generatePDF(installationId) {
    // Vérifier si jsPDF est disponible
    if (typeof window.jspdf === 'undefined') {
        alert('La bibliothèque jsPDF n\'est pas chargée. Veuillez vérifier que le script est inclus dans votre page.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Récupérer les données stockées
    const data = detailData[installationId];
    if (!data) {
        alert("Données manquantes pour générer le PDF.");
        return;
    }

    // Titre
    doc.setFontSize(18);
    doc.text(`Rapport d'installation - ${installationId}`, 20, 20);

    // Informations principales
    doc.setFontSize(12);
    doc.text(`Date d'installation : ${data.date || 'Non spécifiée'}`, 20, 35);
    doc.text(`Commune : ${data.commune || 'Non spécifiée'} (${data.code_postal || 'N/A'})`, 20, 45);
    doc.text(`Coordonnées GPS : ${data.latitude || 'N/A'}, ${data.longitude || 'N/A'}`, 20, 55);
    
    // Vérifier si autoTable est disponible
    if (typeof doc.autoTable === 'function') {
        // Caractéristiques techniques
        doc.autoTable({
            startY: 65,
            head: [['Caractéristique', 'Valeur']],
            body: [
                ['Surface', data.surface ? `${data.surface} m²` : 'Non spécifiée'],
                ['Puissance crête', data.puissance_crete ? `${data.puissance_crete} kW` : 'Non spécifiée'],
                ['Nombre de panneaux', data.nb_panneaux || 'N/A'],
                ['Nombre d\'ondulateurs', data.nb_ondulateurs || 'N/A'],
                ['Orientation', data.orientation ? `${data.orientation}°` : 'Non spécifiée'],
                ['Inclinaison', data.pente ? `${data.pente}°` : 'Non spécifiée'],
            ],
        });

        // Équipements
        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 10,
            head: [['Équipement', 'Marque', 'Modèle']],
            body: [
                ['Onduleur', data.marque_ondulateur || 'N/A', data.modele_ondulateur || 'N/A'],
                ['Panneaux', data.marque_panneau || 'N/A', data.modele_panneau || 'N/A'],
            ],
        });

        // Production
        if (data.production_pvgis) {
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 10,
                head: [['Performance', 'Valeur']],
                body: [
                    ['Production annuelle estimée (PVGIS)', `${data.production_pvgis} kWh`],
                ],
            });
        }

        // Installateur
        if (data.installeur) {
            doc.text(`Installateur : ${data.installeur}`, 20, doc.lastAutoTable.finalY + 20);
        }
    } else {
        // Version simple sans tableau si autoTable n'est pas disponible
        let yPos = 65;
        doc.text('=== CARACTÉRISTIQUES TECHNIQUES ===', 20, yPos);
        yPos += 10;
        
        const specs = [
            `Surface: ${data.surface ? data.surface + ' m²' : 'Non spécifiée'}`,
            `Puissance crête: ${data.puissance_crete ? data.puissance_crete + ' kW' : 'Non spécifiée'}`,
            `Nombre de panneaux: ${data.nb_panneaux || 'N/A'}`,
            `Nombre d'ondulateurs: ${data.nb_ondulateurs || 'N/A'}`,
            `Orientation: ${data.orientation ? data.orientation + '°' : 'Non spécifiée'}`,
            `Inclinaison: ${data.pente ? data.pente + '°' : 'Non spécifiée'}`
        ];
        
        specs.forEach(spec => {
            doc.text(spec, 20, yPos);
            yPos += 8;
        });
        
        yPos += 10;
        doc.text('=== ÉQUIPEMENTS ===', 20, yPos);
        yPos += 10;
        doc.text(`Onduleur: ${data.marque_ondulateur || 'N/A'} - ${data.modele_ondulateur || 'N/A'}`, 20, yPos);
        yPos += 8;
        doc.text(`Panneaux: ${data.marque_panneau || 'N/A'} - ${data.modele_panneau || 'N/A'}`, 20, yPos);
        
        if (data.production_pvgis) {
            yPos += 18;
            doc.text('=== PRODUCTION ===', 20, yPos);
            yPos += 10;
            doc.text(`Production annuelle estimée: ${data.production_pvgis} kWh`, 20, yPos);
        }
        
        if (data.installeur) {
            yPos += 18;
            doc.text(`Installateur: ${data.installeur}`, 20, yPos);
        }
    }

    // Sauvegarde du fichier
    doc.save(`rapport-installation-${installationId}.pdf`);
}