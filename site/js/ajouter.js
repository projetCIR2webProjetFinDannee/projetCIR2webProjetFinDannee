document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Messages d'erreur en français
    const errorMessages = {
        required: 'Ce champ est obligatoire',
        invalidDate: 'Veuillez saisir une date valide',
        invalidNumber: 'Veuillez saisir un nombre valide',
        invalidCoordinate: 'Coordonnée GPS invalide',
        invalidInsee: 'Code INSEE invalide (4 chiffres requis)',
        minValue: 'La valeur doit être supérieure à 0',
        maxLatitude: 'La latitude doit être entre -90 et 90',
        maxLongitude: 'La longitude doit être entre -180 et 180'
    };

    // Fonction de validation des champs requis
    function validateRequiredFields() {
        const requiredFields = [
            'date', 'insee', 'latitude', 'longitude', 'surface', 
            'puissance', 'nbPanneaux', 'nbOndulateurs', 'orientation', 
            'inclinaison', 'marqueOnduleur', 'modeleOnduleur', 
            'marquePanneaux', 'modelePanneaux', 'installateur', 'prod_pvgis'
        ];
        
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field && !field.value.trim()) {
                showFieldError(field, errorMessages.required);
                isValid = false;
            } else if (field) {
                clearFieldError(field);
            }
        });
        
        return isValid;
    }

    // Fonction de validation spécifique par type de champ
    function validateFieldTypes() {
        let isValid = true;
        
        // Validation de la date
        const dateField = form.querySelector('[name="date"]');
        if (dateField.value && !isValidDate(dateField.value)) {
            showFieldError(dateField, errorMessages.invalidDate);
            isValid = false;
        }
        
        // Validation du code INSEE
        const inseeField = form.querySelector('[name="insee"]');
        if (inseeField.value && !isValidInsee(inseeField.value)) {
            showFieldError(inseeField, errorMessages.invalidInsee);
            isValid = false;
        }
        
        // Validation des coordonnées GPS
        const latField = form.querySelector('[name="latitude"]');
        const longField = form.querySelector('[name="longitude"]');
        
        if (latField.value && !isValidLatitude(latField.value)) {
            showFieldError(latField, errorMessages.maxLatitude);
            isValid = false;
        }
        
        if (longField.value && !isValidLongitude(longField.value)) {
            showFieldError(longField, errorMessages.maxLongitude);
            isValid = false;
        }
        
        // Validation des champs numériques positifs
        const numericFields = ['surface', 'puissance', 'nbPanneaux', 'nbOndulateurs', 'prod_pvgis'];
        numericFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field.value && (!isValidPositiveNumber(field.value))) {
                showFieldError(field, errorMessages.minValue);
                isValid = false;
            }
        });
        
        // Validation des inclinaisons (0-90 degrés)
        const inclinaisonFields = ['inclinaison', 'inclinaison_opti'];
        inclinaisonFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field.value && (!isValidInclination(field.value))) {
                showFieldError(field, 'L\'inclinaison doit être entre 0 et 90 degrés');
                isValid = false;
            }
        });
        
        return isValid;
    }

    // Fonctions de validation utilitaires
    function isValidDate(dateString) {
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date) && dateString.match(/^\d{4}-\d{2}-\d{2}$/);
    }

    function isValidInsee(insee) {
        return /^\d{4}$/.test(insee);
    }

    function isValidLatitude(lat) {
        const num = parseFloat(lat);
        return !isNaN(num) && num >= -90 && num <= 90;
    }

    function isValidLongitude(long) {
        const num = parseFloat(long);
        return !isNaN(num) && num >= -180 && num <= 180;
    }

    function isValidPositiveNumber(value) {
        const num = parseFloat(value);
        return !isNaN(num) && num > 0;
    }

    function isValidInclination(value) {
        const num = parseFloat(value);
        return !isNaN(num) && num >= 0 && num <= 90;
    }

    // Fonctions d'affichage des erreurs
    function showFieldError(field, message) {
        clearFieldError(field);
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    // Fonction pour afficher les notifications
    function showNotification(message, type = 'success') {
        // Supprimer les notifications existantes
        const existingNotif = document.querySelector('.notification-custom');
        if (existingNotif) {
            existingNotif.remove();
        }

        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} notification-custom`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;
        
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Fonction de soumission du formulaire
    async function submitForm(event) {
        event.preventDefault();
        
        // Validation complète
        const isRequiredValid = validateRequiredFields();
        const isTypesValid = validateFieldTypes();
        
        if (!isRequiredValid || !isTypesValid) {
            showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');
            return;
        }

        // Désactiver le bouton de soumission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Ajout en cours...';

        try {
            const formData = new FormData(form);
            
            const response = await fetch('request.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                showNotification('Installation ajoutée avec succès !', 'success');
                
                // Réinitialiser le formulaire après succès
                setTimeout(() => {
                    form.reset();
                    // Rediriger vers la page de recherche
                    window.location.href = 'recherche_developeur.php';
                }, 2000);
                
            } else {
                throw new Error(`Erreur serveur: ${response.status}`);
            }
            
        } catch (error) {
            console.error('Erreur lors de l\'ajout:', error);
            showNotification('Erreur lors de l\'ajout de l\'installation. Veuillez réessayer.', 'error');
        } finally {
            // Réactiver le bouton
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Ajouter';
        }
    }

    // Validation en temps réel
    form.addEventListener('input', function(event) {
        const field = event.target;
        
        // Nettoyer les erreurs existantes lors de la saisie
        if (field.classList.contains('is-invalid')) {
            clearFieldError(field);
        }
        
        // Validation spécifique selon le type de champ
        switch(field.name) {
            case 'insee':
                if (field.value && !isValidInsee(field.value)) {
                    showFieldError(field, errorMessages.invalidInsee);
                }
                break;
                
            case 'latitude':
                if (field.value && !isValidLatitude(field.value)) {
                    showFieldError(field, errorMessages.maxLatitude);
                }
                break;
                
            case 'longitude':
                if (field.value && !isValidLongitude(field.value)) {
                    showFieldError(field, errorMessages.maxLongitude);
                }
                break;
                
            case 'inclinaison':
            case 'inclinaison_opti':
                if (field.value && !isValidInclination(field.value)) {
                    showFieldError(field, 'L\'inclinaison doit être entre 0 et 90 degrés');
                }
                break;
        }
    });

    // Gestionnaire de soumission
    form.addEventListener('submit', submitForm);

    // Auto-complétion et suggestions (optionnel)
    function setupAutoComplete() {
        // Suggestions d'orientations communes
        const orientationField = form.querySelector('[name="orientation"]');
        const orientationOptiField = form.querySelector('[name="orientation_opti"]');
        
        const orientations = ['Sud', 'Sud-Est', 'Sud-Ouest', 'Est', 'Ouest', 'Nord'];
        
        [orientationField, orientationOptiField].forEach(field => {
            if (field) {
                field.addEventListener('focus', function() {
                    // Vous pouvez ajouter ici une liste déroulante de suggestions
                });
            }
        });
    }

    // Initialiser les fonctionnalités optionnelles
    setupAutoComplete();

    // Gestion des champs numériques pour éviter les valeurs négatives
    const numericInputs = form.querySelectorAll('input[type="number"], input[name="surface"], input[name="puissance"], input[name="prod_pvgis"]');
    numericInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });
});

// Fonction utilitaire pour formater les coordonnées GPS
function formatGPSCoordinate(input, type) {
    let value = parseFloat(input.value);
    if (!isNaN(value)) {
        if (type === 'latitude') {
            value = Math.max(-90, Math.min(90, value));
        } else if (type === 'longitude') {
            value = Math.max(-180, Math.min(180, value));
        }
        input.value = value.toFixed(6);
    }
}