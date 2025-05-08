// Fonction pour mettre à jour les options du sujet en fonction du type d'utilisateur
function updateSujetOptions() {
    var typeUtilisateur = document.querySelector("select[name='type_utilisateur']").value
    var sujetSelect = document.querySelector("select[name='sujet']")
    var currentSujet = sujetSelect.getAttribute("data-current")
  
    // Vider les options existantes
    sujetSelect.innerHTML = ""
  
    // Ajouter des options selon le type utilisateur
    let options
    if (typeUtilisateur === "conducteur") {
      options = ["Retard", "Problème de paiement", "Comportement inapproprié"]
    } else if (typeUtilisateur === "etudiant") {
      options = ["Problème de transport", "Difficulté d'accès aux cours", "Comportement inapproprié"]
    } else {
      options = ["-- Sélectionnez un sujet --"]
    }
  
    // Ajouter chaque option au select
    options.forEach((option) => {
      var opt = document.createElement("option")
      opt.value = option
      opt.textContent = option
      if (option === currentSujet) {
        opt.selected = true
      }
      sujetSelect.appendChild(opt)
    })
  }
  
  // Initialiser les options de sujet au chargement de la page
  document.addEventListener("DOMContentLoaded", () => {
    updateSujetOptions()
  
    // Ajouter un écouteur d'événement pour mettre à jour les options lorsque le type change
    document.querySelector("select[name='type_utilisateur']").addEventListener("change", updateSujetOptions)
  
    // Validation du formulaire
    const form = document.getElementById("editReclamationForm")
    if (form) {
      form.addEventListener("submit", (event) => {
        // Empêcher la soumission par défaut
        event.preventDefault()
  
        // Réinitialiser les messages d'erreur précédents
        clearErrors()
  
        // Valider les champs
        let isValid = true
  
        // Valider le message
        const messageField = document.getElementById("message")
        if (!messageField.value.trim()) {
          displayError(messageField, "Le message est requis")
          isValid = false
        }
  
        // Valider l'ID utilisateur
        const idUtilisateurField = document.getElementById("id_utilisateur")
        if (!idUtilisateurField.value.trim()) {
          displayError(idUtilisateurField, "L'ID utilisateur est requis")
          isValid = false
        }
  
        // Si tout est valide, soumettre le formulaire
        if (isValid) {
          form.submit()
        }
      })
    }
  
    // Fonction pour afficher une erreur sous un champ
    function displayError(field, message) {
      // Ajouter la classe d'erreur au champ
      field.classList.add("error-field")
  
      // Créer un élément pour le message d'erreur
      const errorElement = document.createElement("div")
      errorElement.className = "error-message"
      errorElement.textContent = message
  
      // Insérer le message d'erreur après le champ
      field.parentNode.insertBefore(errorElement, field.nextSibling)
    }
  
    // Fonction pour effacer toutes les erreurs
    function clearErrors() {
      // Supprimer toutes les classes d'erreur
      const errorFields = document.querySelectorAll(".error-field")
      errorFields.forEach((field) => {
        field.classList.remove("error-field")
      })
  
      // Supprimer tous les messages d'erreur
      const errorMessages = document.querySelectorAll(".error-message")
      errorMessages.forEach((message) => {
        message.parentNode.removeChild(message)
      })
    }
  })
  