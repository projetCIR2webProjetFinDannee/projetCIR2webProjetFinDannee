function fillPage() {
    document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const installationId = urlParams.get('id');

    if (!installationId) {
        alert('Aucun ID spécifié dans l\'URL.');
        return;
    }

    if (installationId) {
        fetch(`../back/request.php?type=info&id=${installationId}`)
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