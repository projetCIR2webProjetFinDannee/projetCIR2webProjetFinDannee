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
        const response = await fetch('../back/request.php?type=select_data');
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
    
    // montrer l'état de chargement
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
    fetch(`../back/request.php?type=search&marqueOndulateur=${onduleur || 'all'}&marquePanneaux=${panneaux || 'all'}&numDepartement=${departement || 'all'}`)
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
                        // Correction de l'URL pour récupérer les détails
                        const res = await fetch(`../back/request.php?type=info&id=${id}`);
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
                <div class="d-flex align-items-center">
                    <i class="bi ${data.icon} me-3 text-primary fs-4"></i>
                    <div class="flex-grow-1">
                        <strong>${data.title}</strong><br>
                        <small class="text-muted">${data.details}</small>
                    </div>
                    <a href="modifier_dev.php?id=${data.id}">
                    <button class="btn btn-primary btn-lg me-2" onclick="modifierInstallation(this)">
                        <small>Modifier</small>
                    </button>
                    </a>
                    <button class="btn btn-danger btn-lg me-2" onclick="event.stopPropagation(); supprimerInstallation(${data.id})" style="background-color: #dc3545; border-color: #dc3545; color: white;">
                        <small>Supprimer</small>
                    </button>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            `;
            
            container.appendChild(resultItem);

                // annimation d'entrée
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
    // enlever la sélection de tous les éléments
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

// Fonction pour gérer la modification
function modifierInstallation(button) {
    console.log('Modifier installation');
    // Empêcher la propagation pour éviter de déclencher selectResult
    event.stopPropagation();
    // Votre code de modification ici
}

// Fonction pour gérer la suppression
function supprimerInstallation(id) {
    console.log('Supprimer installation');
    // Empêcher la propagation pour éviter de déclencher selectResult
    event.stopPropagation();

    if (confirm('Êtes-vous sûr de vouloir supprimer cette installation ?')) {
        fetch(`../back/request.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                // Animation de disparition
                const resultItem = Array.from(document.querySelectorAll('.result-item')).find(item => {
                    // On cherche l'élément qui contient le bouton cliqué
                    const btn = item.querySelector('button.btn-danger');
                    return btn && btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(`supprimerInstallation(${id})`);
                });
                if (resultItem) {
                    resultItem.style.transition = 'opacity 0.5s, transform 0.5s';
                    resultItem.style.opacity = '0';
                    resultItem.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        resultItem.remove();
                    }, 500);
                }
                console.log('Installation supprimée avec succès');
            } else {
                console.error('Erreur lors de la suppression de l\'installation');
                alert('Erreur lors de la suppression de l\'installation.');
            }
        })
        .catch(error => {
            console.error('Erreur de réseau lors de la suppression:', error);
            alert('Erreur de réseau lors de la suppression de l\'installation.');
        });
    }
}