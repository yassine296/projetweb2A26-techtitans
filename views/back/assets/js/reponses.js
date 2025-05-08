// Fonction pour éditer une réponse
function editReponse(id, message) {
    document.getElementById("id_reponse").value = id
    document.getElementById("reponse_message").value = message
    document.getElementById("reponse-form-title").innerHTML = '<i class="fas fa-edit"></i> Modifier la réponse'
    document.getElementById("submit-btn").name = "modifier_reponse"
    document.getElementById("submit-btn").innerHTML = '<i class="fas fa-save"></i> Mettre à jour'
  
    // Scroll to form
    document.getElementById("reponseForm").scrollIntoView({ behavior: "smooth" })
  }
  
  // Fonction pour réinitialiser le formulaire
  function resetForm() {
    document.getElementById("id_reponse").value = ""
    document.getElementById("reponse_message").value = ""
  
    // Vérifier s'il y a déjà des réponses
    const responsesCount = document.querySelectorAll(".reponse-item").length
  
    if (responsesCount > 0) {
      document.getElementById("reponse-form-title").innerHTML = '<i class="fas fa-plus"></i> Ajouter une autre réponse'
    } else {
      document.getElementById("reponse-form-title").innerHTML = '<i class="fas fa-reply"></i> Répondre à la réclamation'
    }
  
    document.getElementById("submit-btn").name = "ajouter_reponse"
    document.getElementById("submit-btn").innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer la réponse'
  }
  
  document.addEventListener("DOMContentLoaded", () => {
    // Récupérer le formulaire de réponse
    const reponseForm = document.getElementById("reponseForm")
  
    if (reponseForm) {
      reponseForm.addEventListener("submit", (event) => {
        // Empêcher la soumission par défaut
        event.preventDefault()
  
        // Réinitialiser les messages d'erreur précédents
        clearErrors()
  
        // Valider les champs
        let isValid = true
  
        // Valider le message de réponse
        const messageField = document.getElementById("reponse_message")
        if (!messageField.value.trim()) {
          displayError(messageField, "Le message de réponse est requis")
          isValid = false
        } else if (messageField.value.trim().length < 10) {
          displayError(messageField, "Le message doit contenir au moins 10 caractères")
          isValid = false
        }
  
        // Si tout est valide, soumettre le formulaire
        if (isValid) {
          reponseForm.submit()
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
  
    // Validation en temps réel pour le champ message
    const messageField = document.getElementById("reponse_message")
    if (messageField) {
      messageField.addEventListener("input", function () {
        // Supprimer l'erreur si elle existe
        if (this.classList.contains("error-field")) {
          this.classList.remove("error-field")
          const nextSibling = this.nextSibling
          if (nextSibling && nextSibling.className === "error-message") {
            nextSibling.parentNode.removeChild(nextSibling)
          }
        }
  
        // Vérifier la longueur minimale
        if (this.value.trim().length > 0 && this.value.trim().length < 10) {
          // Afficher un indicateur de longueur
          let existingCounter = document.getElementById("message-counter")
          if (!existingCounter) {
            const counter = document.createElement("div")
            counter.id = "message-counter"
            counter.className = "character-counter"
            this.parentNode.insertBefore(counter, this.nextSibling)
            existingCounter = counter
          }
          existingCounter.textContent = `${this.value.trim().length}/10 caractères minimum`
          existingCounter.style.color = this.value.trim().length < 10 ? "#f44336" : "#4CAF50"
        } else {
          // Supprimer le compteur si le message est vide ou assez long
          const counter = document.getElementById("message-counter")
          if (counter) {
            counter.parentNode.removeChild(counter)
          }
        }
      })
    }
  })
  