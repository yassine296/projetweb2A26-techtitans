// validation.js

document.addEventListener('DOMContentLoaded', function() {
    const formReclamation = document.getElementById('reclamationForm');
    const searchForm = document.getElementById('searchForm');
    const searchError = document.getElementById('search-error');
    const reclamationError = document.getElementById('reclamation-error');

    // Validation formulaire recherche
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            const idUtilisateur = document.getElementById('id_utilisateur_selectionne').value.trim();
            searchError.textContent = ''; // Reset message

            if (idUtilisateur === '') {
                searchError.textContent = 'Veuillez saisir un ID utilisateur.';
                event.preventDefault();
                return;
            }

            if (!/^\d+$/.test(idUtilisateur)) {
                searchError.textContent = 'L\'ID utilisateur doit contenir uniquement des chiffres.';
                event.preventDefault();
                return;
            }
        });
    }

    // Validation formulaire d'ajout réclamation
    if (formReclamation) {
        formReclamation.addEventListener('submit', function(event) {
            const typeUtilisateur = formReclamation.querySelector('select[name="type_utilisateur"]').value.trim();
            const idUtilisateur = formReclamation.querySelector('input[name="id_utilisateur"]').value.trim();
            const sujet = formReclamation.querySelector('select[name="sujet"]').value.trim();
            const message = formReclamation.querySelector('textarea[name="message"]').value.trim();
            reclamationError.textContent = ''; // Reset message

            let errors = [];

            if (typeUtilisateur === '') {
                errors.push('Veuillez sélectionner un type d\'utilisateur.');
            }

            if (idUtilisateur === '') {
                errors.push('Veuillez saisir un ID utilisateur.');
            } else if (!/^\d+$/.test(idUtilisateur)) {
                errors.push('L\'ID utilisateur doit contenir uniquement des chiffres.');
            }

            if (sujet === '') {
                errors.push('Veuillez sélectionner un sujet.');
            }

            if (message === '') {
                errors.push('Veuillez écrire votre message.');
            }

            if (errors.length > 0) {
                reclamationError.innerHTML = errors.join('<br>');
                event.preventDefault();
            }
        });
    }
});
