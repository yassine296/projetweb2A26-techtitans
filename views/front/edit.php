<?php
require_once '../../controllers/ReclamationController.php';
require_once '../../models/Reclamation.php';

$reclamationC = new ReclamationController();
$reclamation = null;
$message = null;
$message_type = null;

if (isset($_GET['id'])) {
    $reclamation = $reclamationC->showReclamation($_GET['id']);
    if (!$reclamation) {
        $message = "Réclamation non trouvée.";
        $message_type = "error";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $modif = new Reclamation(
        (int)$_POST['id'],
        $_POST['type_utilisateur'],
        (int)$_POST['id_utilisateur'],
        $_POST['sujet'],
        $_POST['message'],
        new DateTime() // Ajouter la date actuelle
    );

    $reclamationC->updateReclamation($modif, $_POST['id']);
    $message = "Réclamation modifiée avec succès.";
    $message_type = "success";
    
    // Redirection après 2 secondes
    header("Refresh: 2; URL=reclamations.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Modifier Réclamation</title>
  <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Gérez vos réclamations.">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body>
  <div class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <header class="hero">
    <div class="hero-background"></div>
    <img src="assets/img/logo-blanc.png" alt="Hezni" class="logo animate-fadeIn">
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
      <form method="POST">
        <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">
        
        <label class="form-label">Type Utilisateur :</label>
        <select name="type_utilisateur" required onchange="updateSujetOptions()">
          <option value="">-- Choisissez un type d'utilisateur --</option>
          <option value="etudiant" <?php if ($reclamation['type_utilisateur'] === 'etudiant') echo 'selected'; ?>>Etudiant</option>
          <option value="conducteur" <?php if ($reclamation['type_utilisateur'] === 'conducteur') echo 'selected'; ?>>Conducteur</option>
        </select>
        
        <label class="form-label">ID Utilisateur :</label>
        <input type="text" name="id_utilisateur" class="search-field" placeholder="ID Utilisateur" 
               value="<?php echo $reclamation['id_utilisateur']; ?>" required
               oninput="validateNumberInput(this)">
        
        <label class="form-label">Sujet :</label>
        <select name="sujet" required data-current-value="<?php echo $reclamation['sujet']; ?>">
          <!-- Les options seront remplies dynamiquement par JavaScript -->
        </select>

        <label class="form-label">Message :</label>
        <textarea name="message" placeholder="Votre message" required><?php echo $reclamation['message']; ?></textarea>

        <div class="row">
          <button type="submit" name="modifier" class="animate-pulse"><i class="fas fa-save"></i> Enregistrer les modifications</button>
          <a href="reclamations.php" class="form-container button" style="background-color: #9e9e9e; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-times"></i> Annuler
          </a>
        </div>
      </form>
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
</body>
</html>
