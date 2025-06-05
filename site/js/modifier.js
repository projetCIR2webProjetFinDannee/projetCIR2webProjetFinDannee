function fillPage() {
    document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const installationId = urlParams.get('id');

    if (!installationId) {
        alert('Aucun ID spécifié dans l\'URL.');
        return;
    }

    if (installationId) {
        fetch(`../api/request.php?type=info&id=${installationId}`)
            .then(response => response.json())
            .then(data => {
                if (data && !data.error) {
                    // Remplir les champs du formulaire
                    document.querySelector('input[name="date"]').value = data.date || '';
                    document.querySelector('input[name="insee"]').value = data.code_insee || '';
                    document.querySelector('input[name="latitude"]').value = data.latitude || '';
                    document.querySelector('input[name="longitude"]').value = data.longitude || '';
                    document.querySelector('input[name="surface"]').value = data.surface || '';
                    document.querySelector('input[name="puissance"]').value = data.puissance_crete || '';
                    document.querySelector('input[name="nbPanneaux"]').value = data.nb_panneaux || '';
                    document.querySelector('input[name="nbOndulateurs"]').value = data.nb_ondulateurs || '';
                    document.querySelector('input[name="orientation"]').value = data.orientation || '';
                    document.querySelector('input[name="orientation_opti"]').value = data.orientation_optimum || '';
                    document.querySelector('input[name="inclinaison"]').value = data.pente || '';
                    document.querySelector('input[name="inclinaison_opti"]').value = data.pente_optimum || '';
                    document.querySelector('input[name="marqueOnduleur"]').value = data.marque_ondulateur || '';
                    document.querySelector('input[name="modeleOnduleur"]').value = data.modele_ondulateur || '';
                    document.querySelector('input[name="marquePanneaux"]').value = data.marque_panneau || '';
                    document.querySelector('input[name="modelePanneaux"]').value = data.modele_panneau || '';
                    document.querySelector('input[name="installateur"]').value = data.installeur || '';
                    document.querySelector('input[name="pvgis"]').value = data.production_pvgis || '';
                } else {
                    alert('Erreur : installation non trouvée.');
                }
            })
            .catch(err => {
                console.error('Erreur de chargement :', err);
                alert('Impossible de charger les données.');
            });
    }
    });
}

fillPage();

// Récupérer l'ID de l'installation depuis l'URL
function getInstallationId() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

// Gérer la soumission du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // Empêcher la soumission normale du formulaire

        const id = getInstallationId();
        if (!id) {
            alert('ID de l\'installation manquant');
            return;
        }

        // Collecter les données du formulaire
        const formData = new FormData(form);
        
        // Construire l'URL avec tous les paramètres (car votre backend utilise $_GET)
        const params = new URLSearchParams();
        params.append('id', id);
        params.append('date', formData.get('date'));
        params.append('insee', formData.get('insee'));
        params.append('latitude', formData.get('latitude'));
        params.append('longitude', formData.get('longitude'));
        params.append('surface', formData.get('surface'));
        params.append('puissance', formData.get('puissance'));
        params.append('nbPanneaux', formData.get('nbPanneaux'));
        params.append('nbOndulateurs', formData.get('nbOndulateurs'));
        params.append('orientation', formData.get('orientation'));
        params.append('inclinaison', formData.get('inclinaison'));
        params.append('marqueOnduleur', formData.get('marqueOnduleur'));
        params.append('modeleOnduleur', formData.get('modeleOnduleur'));
        params.append('marquePanneaux', formData.get('marquePanneaux'));
        params.append('modelePanneaux', formData.get('modelePanneaux'));
        params.append('installateur', formData.get('installateur'));
        params.append('prod_pvgis', formData.get('pvgis'));

        // Ajouter les paramètres optionnels s'ils sont présents
        if (formData.get('orientation_opti')) {
            params.append('orientation_opti', formData.get('orientation_opti'));
        }
        if (formData.get('inclinaison_opti')) {
            params.append('inclinaison_opti', formData.get('inclinaison_opti'));
        }

        try {
            // Envoyer la requête PUT
            const response = await fetch(`../api/request.php?${params.toString()}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });

            alert('Installation modifiée avec succès !');
            // Rediriger vers la page de recherche
            window.location.href = 'recherche_developeur.php';
        } catch (error) {
            console.error('Erreur de réseau:', error);
            alert('Erreur de réseau lors de la modification');
        }
    });
});