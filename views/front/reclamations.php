<?php
include '../../controllers/ReclamationController.php';
$reclamationC = new ReclamationController();

// Initialisation
$list = [];
$id_utilisateur = null;
$message = null;
$message_type = null;
$user_exists = true;

// Réinitialiser les valeurs de session si c'est un chargement initial (pas de POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Vider toutes les valeurs
    $_POST = array();
    $id_utilisateur = null;
}

// Gérer l'affichage des réclamations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['afficher'])) {
    $id_utilisateur = $_POST['id_utilisateur_selectionne'];
    $list = $reclamationC->getReclamationsByUserId($id_utilisateur);
    
    // Vérifier si l'utilisateur existe
    if (empty($list)) {
        $message = "Aucune réclamation trouvée pour l'utilisateur avec ID: " . $id_utilisateur;
        $message_type = "warning";
        $user_exists = false;
    }
}

// Gérer l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $reclamation = new Reclamation(
        null,
        $_POST['type_utilisateur'],
        $_POST['id_utilisateur'],
        $_POST['sujet'],
        $_POST['message'],
        new DateTime()
    );
    $reclamationC->addReclamation($reclamation);
    $id_utilisateur = $_POST['id_utilisateur'];
    $list = $reclamationC->getReclamationsByUserId($id_utilisateur);
    $message = "Réclamation ajoutée avec succès.";
    $message_type = "success";
    
    // Réinitialiser le formulaire après ajout
    $_POST['type_utilisateur'] = '';
    $_POST['sujet'] = '';
    $_POST['message'] = '';
}

// Gérer la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $reclamationC->deleteReclamation($_POST['id']);
    $id_utilisateur = $_POST['id_utilisateur'];
    $list = $reclamationC->getReclamationsByUserId($id_utilisateur);
    $message = "Réclamation supprimée avec succès.";
    $message_type = "success";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Réclamations</title>
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
    <img src="assets/images/logoblanc.png" alt="Hezni" class="logo animate-fadeIn">
    <div class="hero-text animate-slideInTop">
      <span>Traitement Des Réclamations</span>
    </div>
    <div class="wave-bottom">
      <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
      </svg>
    </div>
  </header>

  <div class="content">
    <div class="section-header">
      <h1 class="section-title">Votre opinion est essentielle pour nous. N’hésitez pas à nous faire part de toute insatisfaction ou suggestion : chaque message est pris en compte avec sérieux et bienveillance..</h1>
      
      <div class="image-row" style="text-align:center; margin: 20px 0;">
  <img src="assets/images/rec.jpg" alt="Réclamation" style="max-width: 300px; margin-right: 20px;">
  
</div>

    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['afficher']) && !$user_exists): ?>
    <div class="form-container">
      <div class="user-not-found">
        <i class="fas fa-exclamation-triangle"></i>
        Aucune réclamation trouvée pour l'utilisateur avec ID: <?php echo $id_utilisateur; ?>
      </div>
    </div>
    <?php elseif ($message): ?>
    <div class="form-container">
    
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

    <!-- Sélection utilisateur -->
    <div class="form-container animate-slideInLeft">
      <h2 class="form-title">Rechercher des réclamations</h2>
      <form method="POST" class="search-form">
        <label class="form-label">ID Utilisateur :</label>
        <div class="row">
          <input type="text" name="id_utilisateur_selectionne" id="id_utilisateur_selectionne" 
            class="search-field" placeholder="Entrez l'ID utilisateur (chiffres uniquement)" 
            value="" required pattern="[0-9]+" 
            oninput="validateNumberInput(this)">
          <button type="submit" name="afficher"><i class="fas fa-search"></i> Afficher Réclamations</button>
        </div>
      </form>
    </div>

    <!-- Formulaire Ajout/Modification -->
    <div class="form-container animate-slideInLeft">
    
      <h2 class="form-title">Ajouter une Réclamation</h2>
      <form method="POST" id="reclamationForm">
        <label class="form-label">Type Utilisateur :</label>
        <select name="type_utilisateur" required onchange="updateSujetOptions()">
          <option value="">-- Choisissez un type d'utilisateur --</option>
          <option value="etudiant">Etudiant</option>
          <option value="conducteur">Conducteur</option>
        </select>
        
        <label class="form-label">ID Utilisateur :</label>
        <input type="text" name="id_utilisateur" placeholder="ID Utilisateur" value="" required
               oninput="validateNumberInput(this)">
        
        <label class="form-label">Sujet :</label>
        <select name="sujet" required>
          <option value="">-- Sélectionnez un sujet --</option>
        </select>

        <label class="form-label">Message :</label>
        <textarea name="message" placeholder="Votre message" required></textarea>

        <div class="row">
          <button type="submit" name="ajouter"><i class="fas fa-plus"></i> Ajouter</button>
          <button type="button" onclick="resetForm()"><i class="fas fa-redo"></i> Réinitialiser</button>
        </div>
      </form>
    </div>
    <div class="image-row" style="text-align:center; margin-bottom: 20px;">
  
</div>


    <!-- Tableau des réclamations -->
    <?php if (!empty($list)): ?>
      <div class="table-container animate-slideInBottom">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Type Utilisateur</th>
              <th>ID Utilisateur</th>
              <th>Sujet</th>
              <th>Message</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($list as $reclamation): ?>
              <tr>
                <td><?php echo $reclamation['id']; ?></td>
                <td><?php echo $reclamation['type_utilisateur']; ?></td>
                <td><?php echo $reclamation['id_utilisateur']; ?></td>
                <td><?php echo $reclamation['sujet']; ?></td>
                <td><?php echo $reclamation['message']; ?></td>
                <td><?php echo $reclamation['date_reclamation']; ?></td>
                <td>
                  <!-- Modifier - Redirection vers edit.php -->
                  <a href="edit.php?id=<?php echo $reclamation['id']; ?>" class="action-btn">
                    <i class="fas fa-edit"></i> Modifier
                  </a>

                  <!-- Supprimer -->
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">
                    <input type="hidden" name="id_utilisateur" value="<?php echo $reclamation['id_utilisateur']; ?>">
                    <button type="submit" name="supprimer" class="action-btn delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')"><i class="fas fa-trash"></i> Supprimer</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
