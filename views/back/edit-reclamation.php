<?php
require_once '../../controllers/ReclamationController.php';
require_once '../../models/Reclamation.php';
require_once '../../controllers/ReponseController.php';

$reclamationC = new ReclamationController();
$reponseC = new ReponseController();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$reclamation = $reclamationC->showReclamation($id);

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $type_utilisateur = $_POST['type_utilisateur'];
    $id_utilisateur = (int)$_POST['id_utilisateur'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];
    
    // Validation des données
    $errors = [];
    
    if (empty($type_utilisateur)) {
        $errors[] = "Le type d'utilisateur est requis.";
    }
    
    if (empty($id_utilisateur)) {
        $errors[] = "L'ID utilisateur est requis.";
    }
    
    if (empty($sujet)) {
        $errors[] = "Le sujet est requis.";
    }
    
    if (empty($message)) {
        $errors[] = "Le message est requis.";
    }
    
    if (empty($errors)) {
        $reclamationObj = new Reclamation(
            $id,
            $type_utilisateur,
            $id_utilisateur,
            $sujet,
            $message,
            new DateTime()
        );
        
        // CORRECTION: Passer les deux paramètres requis
        $reclamationC->updateReclamation($reclamationObj, $id);
        
        // Redirection vers la page des réclamations
        header("Location: reponses.php?success=1");
        exit();
    }
}

// Récupérer les réponses pour cette réclamation
$reponses = $reponseC->getReponsesByReclamationId($id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réclamation - HEZNI Admin</title>
    <link rel="stylesheet" href="../front/assets/css/back.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <!-- Menu latéral -->
        <div class="sidebar">
            <div class="logo-section">
                <img src="../front/assets/images/logo-rouge.png" alt="Logo Hezni">
            </div>
            <ul class="menu">
                <li><a href="#"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="#"><i class="fa-solid fa-route"></i> Trajets</a></li>
                <li><a href="#"><i class="fa-solid fa-ticket-alt"></i> Réservations</a></li>
                <li class="active"><a href="#"><i class="fas fa-exclamation-circle"></i> Réclamations</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav">
                <div class="header-title">
                    <h1>MODIFIER RÉCLAMATION #<?php echo $id; ?></h1>
                </div>
                <div class="user-info">
                    <div class="avatar">AD</div>
                    <span>Administrateur</span>
                </div>
            </header>

            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="message error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($reclamation): ?>
                <div class="form-container">
                    <form method="POST">
                        <div class="form-group">
                            <label for="type_utilisateur">Type Utilisateur:</label>
                            <select name="type_utilisateur" id="type_utilisateur" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="etudiant" <?php echo $reclamation['type_utilisateur'] === 'etudiant' ? 'selected' : ''; ?>>Étudiant</option>
                                <option value="conducteur" <?php echo $reclamation['type_utilisateur'] === 'conducteur' ? 'selected' : ''; ?>>Conducteur</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_utilisateur">ID Utilisateur:</label>
                            <input type="number" name="id_utilisateur" id="id_utilisateur" value="<?php echo $reclamation['id_utilisateur']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujet">Sujet:</label>
                            <input type="text" name="sujet" id="sujet" value="<?php echo $reclamation['sujet']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea name="message" id="message" rows="5" required><?php echo $reclamation['message']; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="modifier" class="btn primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <a href="reponses.php" class="btn secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="message error">
                    <p>Réclamation non trouvée.</p>
                </div>
                <div class="form-actions">
                    <a href="reponses.php" class="btn secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>