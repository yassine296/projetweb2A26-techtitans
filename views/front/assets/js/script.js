// Fonction pour changer les options du sujet en fonction du type d'utilisateur
function updateSujetOptions() {
  var typeUtilisateur = document.querySelector("select[name='type_utilisateur']").value;
  var sujetSelect = document.querySelector("select[name='sujet']");
  var currentSujet = sujetSelect.getAttribute('data-current-value') || '';
  
  // Vider les options existantes
  sujetSelect.innerHTML = '';
  
  // Ajouter des options selon le type utilisateur
  if (typeUtilisateur === 'conducteur') {
      var options = [
          "Retard",
          "Problème de paiement",
          "Comportement inapproprié"
      ];
  } else if (typeUtilisateur === 'etudiant') {
      var options = [
          "Problème de ponctualité du conducteur",
          " attitude désagréable du conducteur",
          "Comportement inapproprié "
      ];
  } else {
      var options = ["-- Sélectionnez un sujet --"];
  }
  
  // Ajouter chaque option au select
  options.forEach(function(option) {
      var opt = document.createElement('option');
      opt.value = option;
      opt.textContent = option;
      if (option === currentSujet) {
          opt.selected = true;
      }
      sujetSelect.appendChild(opt);
  });
}

// Fonction pour réinitialiser le formulaire
function resetForm() {
  document.getElementById('reclamationForm').reset();
  updateSujetOptions();
}

// Fonction pour valider que seuls des chiffres sont entrés
function validateNumberInput(input) {
  input.value = input.value.replace(/[^0-9]/g, '');
}

// Animation du menu toggle en X
document.addEventListener('DOMContentLoaded', function() {
  const menuToggle = document.getElementById('menuToggle');
  if (menuToggle) {
      menuToggle.addEventListener('click', function() {
          this.classList.toggle('active');
      });
  }
  
  // Initialiser les options de sujet au chargement
  updateSujetOptions();
  
  // S'assurer que le champ ID est vide au chargement initial (page reclamations.php)
  const idUtilisateurSelect = document.getElementById('id_utilisateur_selectionne');
  if (idUtilisateurSelect) {
      if (!window.location.search && !document.referrer.includes(window.location.pathname)) {
          idUtilisateurSelect.value = '';
      }
      
      // Ajouter la validation pour les champs numériques
      idUtilisateurSelect.addEventListener('input', function() {
          validateNumberInput(this);
      });
  }
  
  // Validation pour le champ ID Utilisateur dans le formulaire
  const idUtilisateurInput = document.querySelector('input[name="id_utilisateur"]');
  if (idUtilisateurInput) {
      idUtilisateurInput.addEventListener('input', function() {
          validateNumberInput(this);
      });
  }
  
  // Stocker la valeur actuelle du sujet pour la page d'édition
  const sujetSelect = document.querySelector('select[name="sujet"]');
  if (sujetSelect && sujetSelect.getAttribute('data-current-value')) {
      updateSujetOptions();
  }
});
