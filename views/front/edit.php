<?php
require_once '../../controllers/ReclamationController.php';
require_once '../../models/Reclamation.php';
// Ajouter cette ligne pour les réponses
require_once '../../controllers/ReponseController.php';

$reclamationC = new ReclamationController();
$reclamation = null;
$message = null;
$message_type = null;

// Ajouter ce code pour les réponses
$reponseC = new ReponseController();
$reponses = [];

if (isset($_GET['id'])) {
  $reclamation = $reclamationC->showReclamation($_GET['id']);
  if (!$reclamation) {
      $message = "Réclamation non trouvée.";
      $message_type = "error";
  } else {
      // Récupérer les réponses pour cette réclamation
      $reponses = $reponseC->getReponsesByReclamationId($_GET['id']);
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
  // Validation côté serveur
  $errors = [];
  
  // Vérifier le type d'utilisateur
  if (empty($_POST['type_utilisateur'])) {
    $errors[] = "Le type d'utilisateur est requis";
  }
  
  // Vérifier l'ID utilisateur
  if (empty($_POST['id_utilisateur'])) {
    $errors[] = "L'ID utilisateur est requis";
  } elseif (!is_numeric($_POST['id_utilisateur'])) {
    $errors[] = "L'ID utilisateur doit être un nombre";
  }
  
  // Vérifier le sujet
  if (empty($_POST['sujet'])) {
    $errors[] = "Le sujet est requis";
  }
  
  // Vérifier le message
  if (empty($_POST['message'])) {
    $errors[] = "Le message est requis";
  } elseif (strlen($_POST['message']) < 10) {
    $errors[] = "Le message doit contenir au moins 10 caractères";
  }
  
  // S'il y a des erreurs, afficher un message
  if (!empty($errors)) {
    $message = implode("<br>", $errors);
    $message_type = "error";
  } else {
    try {
      // Conversion explicite en entier pour id_utilisateur
      $id_utilisateur_int = (int)$_POST['id_utilisateur'];
      $id_reclamation = (int)$_POST['id'];
      
      $modif = new Reclamation(
          $id_reclamation,
          $_POST['type_utilisateur'],
          $id_utilisateur_int, // Conversion explicite en entier
          $_POST['sujet'],
          $_POST['message'],
          new DateTime()
      );

      // CORRECTION: Passer les deux paramètres requis
      $reclamationC->updateReclamation($modif, $id_reclamation);
      
      $message = "Réclamation modifiée avec succès.";
      $message_type = "success";
      
      // Récupérer la réclamation mise à jour
      $reclamation = $reclamationC->showReclamation($id_reclamation);
      
      // Redirection après 2 secondes
      header("Refresh: 2; URL=reclamations.php");
    } catch (Exception $e) {
      $message = "Erreur: " . $e->getMessage();
      $message_type = "error";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Hezni - Modifier Réclamation</title>
<meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Gérez vos réclamations.">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/animations.css">
<style>
  /* Styles pour les messages d'erreur */
  .error-field {
    border-color: #f44336 !important;
  }
  
  .error-message {
    color: #f44336;
    font-size: 0.8rem;
    margin-top: -15px;
    margin-bottom: 15px;
  }
  
  /* Styles pour les réponses */
  .response-container {
    background-color: #f3f4f6;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
    border-left: 4px solid #3b82f6;
  }
  
  .response-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
  }
  
  .response-date {
    font-size: 0.875rem;
    color: #6b7280;
  }
  
  .response-content {
    padding: 0.5rem 0;
  }
  
  .no-responses {
    text-align: center;
    padding: 1rem;
    color: #6b7280;
  }
</style>
</head>
<body>
<div class="menu-toggle" id="menuToggle">
  <span></span>
  <span></span>
  <span></span>
</div>

<header class="hero">
  <div class="hero-background"></div>
  <img src="assets/images/logoblanc.png" alt="Hezni" class="logo animate-fadeIn">
  <div class="hero-text animate-slideInTop">
    <span>Modifier Réclamation</span>
  </div>
  <div class="wave-bottom">
    <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
      <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
    </svg>
  </div>
</header>

<div class="content">
  <div class="section-header animate-slideInLeft">
    <h1 class="section-title">Modifier Réclamation</h1>
  </div>

  <div class="form-container animate-slideInLeft">
    <a href="reclamations.php" class="back-button">
      <i class="fas fa-arrow-left"></i> Retour aux réclamations
    </a>
  </div>

  <?php if ($message): ?>
  <div class="form-container animate-slideInLeft">
    <div class="alert alert-<?php echo $message_type; ?>">
      <?php if ($message_type === 'success'): ?>
        <i class="fas fa-check-circle"></i>
      <?php elseif ($message_type === 'warning'): ?>
        <i class="fas fa-exclamation-triangle"></i>
      <?php elseif ($message_type === 'info'): ?>
        <i class="fas fa-info-circle"></i>
      <?php elseif ($message_type === 'error'): ?>
        <i class="fas fa-times-circle"></i>
      <?php endif; ?>
      <?php echo $message; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($reclamation): ?>
  <div class="form-container animate-slideInRight">
    <h2 class="form-title">Modifier la Réclamation #<?php echo $reclamation['id']; ?></h2>
    <form method="POST" id="editForm">
      <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">
      
      <label class="form-label">Type Utilisateur :</label>
      <select name="type_utilisateur" id="type_utilisateur">
        <option value="">-- Choisissez un type d'utilisateur --</option>
        <option value="etudiant" <?php if ($reclamation['type_utilisateur'] === 'etudiant') echo 'selected'; ?>>Etudiant</option>
        <option value="conducteur" <?php if ($reclamation['type_utilisateur'] === 'conducteur') echo 'selected'; ?>>Conducteur</option>
      </select>
      
      <label class="form-label">ID Utilisateur :</label>
      <input type="text" name="id_utilisateur" id="id_utilisateur" class="search-field" placeholder="ID Utilisateur" 
             value="<?php echo $reclamation['id_utilisateur']; ?>"
             oninput="validateNumberInput(this)">
      
      <label class="form-label">Sujet :</label>
      <select name="sujet" id="sujet">
        <option value="">-- Sélectionnez un sujet --</option>
        <?php if ($reclamation['type_utilisateur'] === 'etudiant'): ?>
          <option value="Problème de réservation" <?php if ($reclamation['sujet'] === 'Problème de réservation') echo 'selected'; ?>>Problème de réservation</option>
          <option value="Conducteur non professionnel" <?php if ($reclamation['sujet'] === 'Conducteur non professionnel') echo 'selected'; ?>>Conducteur non professionnel</option>
          <option value="Retard" <?php if ($reclamation['sujet'] === 'Retard') echo 'selected'; ?>>Retard</option>
          <option value="Autre" <?php if ($reclamation['sujet'] === 'Autre') echo 'selected'; ?>>Autre</option>
        <?php elseif ($reclamation['type_utilisateur'] === 'conducteur'): ?>
          <option value="Problème avec un passager" <?php if ($reclamation['sujet'] === 'Problème avec un passager') echo 'selected'; ?>>Problème avec un passager</option>
          <option value="Problème de paiement" <?php if ($reclamation['sujet'] === 'Problème de paiement') echo 'selected'; ?>>Problème de paiement</option>
          <option value="Problème technique" <?php if ($reclamation['sujet'] === 'Problème technique') echo 'selected'; ?>>Problème technique</option>
          <option value="Autre" <?php if ($reclamation['sujet'] === 'Autre') echo 'selected'; ?>>Autre</option>
        <?php endif; ?>
      </select>

      <label class="form-label">Message :</label>
      <textarea name="message" id="message" placeholder="Votre message"><?php echo $reclamation['message']; ?></textarea>

      <div class="row">
        <button type="submit" name="modifier" class="animate-pulse"><i class="fas fa-save"></i> Enregistrer les modifications</button>
        <a href="reclamations.php" class="form-container button" style="background-color: #9e9e9e; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
          <i class="fas fa-times"></i> Annuler
        </a>
      </div>
    </form>
  </div>
  
  <!-- Section des réponses -->
  <div class="form-container animate-slideInRight">
    <h2 class="form-title">Réponses (<?php echo count($reponses); ?>)</h2>
    
    <?php if (!empty($reponses)): ?>
      <?php foreach ($reponses as $reponse): ?>
        <div class="response-container">
          <div class="response-header">
            <div class="response-admin">Admin #<?php echo $reponse['id_admin']; ?></div>
            <div class="response-date"><?php echo date('d/m/Y H:i', strtotime($reponse['date_reponse'])); ?></div>
          </div>
          <div class="response-content">
            <?php echo nl2br(htmlspecialchars($reponse['message'])); ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-responses">
        <i class="fas fa-comment-slash"></i>
        <p>Aucune réponse n'a encore été donnée à cette réclamation.</p>
      </div>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <div class="form-container animate-slideInLeft">
    <div class="alert alert-error">
      <i class="fas fa-times-circle"></i>
      Aucune réclamation trouvée avec cet identifiant.
    </div>
  </div>
  <?php endif; ?>
</div>

<footer class="animate-fadeIn">
  <div class="footer-content">
    <h2 class="footer-title">Contactez-nous</h2>
    
    <div class="footer-links">
      <div class="footer-column">
        <h3>Hezni</h3>
        <ul>
          <li><a href="#">À propos</a></li>
          <li><a href="#">Comment ça marche</a></li>
          <li><a href="#">Sécurité</a></li>
          <li><a href="#">Tarifs</a></li>
        </ul>
      </div>
      
      <div class="footer-column">
        <h3>Assistance</h3>
        <ul>
          <li><a href="#">Centre d'aide</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Confidentialité</a></li>
        </ul>
      </div>
      
      <div class="footer-column">
        <h3>Informations</h3>
        <ul>
          <li><a href="#">Presse</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Carrières</a></li>
          <li><a href="#">Devenir conducteur</a></li>
        </ul>
      </div>
    </div>
    
    <div class="social-links">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
    
    <div class="copyright">
      &copy; 2023 Hezni. Tous droits réservés.
    </div>
  </div>
</footer>

<script src="assets/js/script.js"></script>
<script src="assets/js/animations.js"></script>
<script>
// Mise à jour des options de sujet en fonction du type d'utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const typeUtilisateur = document.getElementById('type_utilisateur');
    const sujet = document.getElementById('sujet');
    
    if (typeUtilisateur && sujet) {
        typeUtilisateur.addEventListener('change', function() {
            // Vider les options actuelles
            sujet.innerHTML = '<option value="">-- Sélectionnez un sujet --</option>';
            
            // Ajouter les nouvelles options en fonction du type d'utilisateur
            if (this.value === 'etudiant') {
                const options = [
                    'Problème de réservation',
                    'Conducteur non professionnel',
                    'Retard',
                    'Autre'
                ];
                
                options.forEach(function(option) {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    sujet.appendChild(optionElement);
                });
            } else if (this.value === 'conducteur') {
                const options = [
                    'Problème avec un passager',
                    'Problème de paiement',
                    'Problème technique',
                    'Autre'
                ];
                
                options.forEach(function(option) {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    sujet.appendChild(optionElement);
                });
            }
        });
    }
    
    // Validation du formulaire
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validation du type d'utilisateur
            const typeUtilisateur = document.getElementById('type_utilisateur');
            if (!typeUtilisateur.value) {
                typeUtilisateur.classList.add('error-field');
                isValid = false;
            } else {
                typeUtilisateur.classList.remove('error-field');
            }
            
            // Validation de l'ID utilisateur
            const idUtilisateur = document.getElementById('id_utilisateur');
            if (!idUtilisateur.value) {
                idUtilisateur.classList.add('error-field');
                isValid = false;
            } else if (!/^\d+$/.test(idUtilisateur.value)) {
                idUtilisateur.classList.add('error-field');
                isValid = false;
            } else {
                idUtilisateur.classList.remove('error-field');
            }
            
            // Validation du sujet
            const sujet = document.getElementById('sujet');
            if (!sujet.value) {
                sujet.classList.add('error-field');
                isValid = false;
            } else {
                sujet.classList.remove('error-field');
            }
            
            // Validation du message
            const message = document.getElementById('message');
            if (!message.value) {
                message.classList.add('error-field');
                isValid = false;
            } else if (message.value.length < 10) {
                message.classList.add('error-field');
                isValid = false;
            } else {
                message.classList.remove('error-field');
            }
            
            if (!isValid) {
                event.preventDefault();
                alert('Veuillez corriger les erreurs dans le formulaire.');
            }
        });
    }
});

// Fonction pour valider les entrées numériques
function validateNumberInput(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>
</body>
</html>