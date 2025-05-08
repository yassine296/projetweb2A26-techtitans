<?php
include '../../controllers/ReclamationController.php';
include '../../controllers/ReponseController.php';

// Classe de détection de gros mots intégrée directement dans le fichier
class BadWordsDetector {
    private $badWords = [];
    private $replacementChar = '*';
    
    /**
     * Constructeur avec liste de gros mots par défaut
     * @param array $customBadWords Liste personnalisée de gros mots (optionnel)
     */
    public function __construct(array $customBadWords = []) {
        // Liste de base de gros mots en français
        $defaultBadWords = [
            'merde', 'putain', 'connard', 'salope', 'pute', 'enculé', 'con', 'bite', 
            'couille', 'foutre', 'cul', 'bordel', 'bâtard', 'salaud', 'enfoiré', 
            'nique', 'niquer', 'pd', 'pédé', 'tapette', 'gouine', 'négro', 'bougnoule'
        ];
        
        // Fusionner avec les mots personnalisés
        $this->badWords = array_merge($defaultBadWords, $customBadWords);
    }
    
    /**
     * Vérifie si un texte contient des gros mots
     * @param string $text Le texte à vérifier
     * @return bool True si des gros mots sont détectés, sinon False
     */
    public function containsBadWords(string $text): bool {
        $text = mb_strtolower($text, 'UTF-8');
        
        foreach ($this->badWords as $word) {
            // Recherche le mot entier avec des limites de mot
            if (preg_match('/\\b' . preg_quote($word, '/') . '\\b/ui', $text)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Récupère tous les gros mots trouvés dans un texte
     * @param string $text Le texte à analyser
     * @return array Liste des gros mots trouvés
     */
    public function findBadWords(string $text): array {
        $text = mb_strtolower($text, 'UTF-8');
        $foundWords = [];
        
        foreach ($this->badWords as $word) {
            if (preg_match('/\\b' . preg_quote($word, '/') . '\\b/ui', $text)) {
                $foundWords[] = $word;
            }
        }
        
        return $foundWords;
    }
    
    /**
     * Censure les gros mots dans un texte
     * @param string $text Le texte à censurer
     * @return string Le texte censuré
     */
    public function censorText(string $text): string {
        foreach ($this->badWords as $word) {
            $replacement = str_repeat($this->replacementChar, mb_strlen($word));
            $text = preg_replace('/\\b' . preg_quote($word, '/') . '\\b/ui', $replacement, $text);
        }
        
        return $text;
    }
    
    /**
     * Définir le caractère de remplacement pour la censure
     * @param string $char Le caractère à utiliser
     */
    public function setReplacementChar(string $char): void {
        $this->replacementChar = $char;
    }
    
    /**
     * Ajouter des gros mots à la liste existante
     * @param array $words Liste de mots à ajouter
     */
    public function addBadWords(array $words): void {
        $this->badWords = array_merge($this->badWords, $words);
    }
}

// Initialisation du détecteur de gros mots
$badWordsDetector = new BadWordsDetector();

$reclamationC = new ReclamationController();
$reponseC = new ReponseController();

// Initialisation
$list = [];
$id_utilisateur = null;
$message = null;
$message_type = null;
$user_exists = true;
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Variable pour vérifier s'il y a des réponses
$hasResponses = false;
$totalResponses = 0;

// Gérer l'affichage des réclamations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['afficher'])) {
    $id_utilisateur = $_POST['id_utilisateur_selectionne'];
    $list = $reclamationC->getReclamationsByUserId((int)$id_utilisateur);
    
    // Vérifier si l'utilisateur existe
    if (empty($list)) {
        $message = "Aucune réclamation trouvée pour l'utilisateur avec ID: " . $id_utilisateur;
        $message_type = "warning";
        $user_exists = false;
    } else {
        // Vérifier s'il y a des réponses pour les réclamations de cet utilisateur
        foreach ($list as $reclamation) {
            $reponses = $reponseC->getReponsesByReclamationId($reclamation['id']);
            if (!empty($reponses)) {
                $hasResponses = true;
                $totalResponses += count($reponses);
            }
        }
    }
} else if (isset($_GET['id_utilisateur'])) {
    $id_utilisateur = $_GET['id_utilisateur'];
    $list = $reclamationC->getReclamationsByUserId((int)$id_utilisateur);
    
    // Vérifier si l'utilisateur existe
    if (empty($list)) {
        $message = "Aucune réclamation trouvée pour l'utilisateur avec ID: " . $id_utilisateur;
        $message_type = "warning";
        $user_exists = false;
    } else {
        // Vérifier s'il y a des réponses pour les réclamations de cet utilisateur
        foreach ($list as $reclamation) {
            $reponses = $reponseC->getReponsesByReclamationId($reclamation['id']);
            if (!empty($reponses)) {
                $hasResponses = true;
                $totalResponses += count($reponses);
            }
        }
    }
}

// Gérer l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
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
    
    // Vérifier les gros mots dans le message
    if (!empty($_POST['message']) && $badWordsDetector->containsBadWords($_POST['message'])) {
        $badWordsList = $badWordsDetector->findBadWords($_POST['message']);
        $errors[] = "Votre message contient des termes inappropriés. Veuillez utiliser un langage respectueux.";
        
        // Option 1: Refuser complètement le message avec des gros mots
        // Option 2: Censurer automatiquement le message (décommentez la ligne ci-dessous)
        $_POST['message'] = $badWordsDetector->censorText($_POST['message']);
    }
    
    // S'il y a des erreurs, afficher un message
    if (!empty($errors)) {
        $message = implode("<br>", $errors);
        $message_type = "error";
    } else {
        // Créer et ajouter la réclamation
        $reclamation = new Reclamation(
            null,
            $_POST['type_utilisateur'],
            (int)$_POST['id_utilisateur'],
            $_POST['sujet'],
            $_POST['message'],
            null // Pas besoin de date, le contrôleur utilise NOW()
        );
        
        $reclamationC->addReclamation($reclamation);
        $id_utilisateur = $_POST['id_utilisateur'];
        $list = $reclamationC->getReclamationsByUserId((int)$id_utilisateur);
        $message = "Réclamation ajoutée avec succès.";
        $message_type = "success";
        
        // Réinitialiser le formulaire après ajout
        $_POST['type_utilisateur'] = '';
        $_POST['sujet'] = '';
        $_POST['message'] = '';
    }
}

// Gérer la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $reclamationC->deleteReclamation((int)$_POST['id']);
    $id_utilisateur = $_POST['id_utilisateur'];
    $list = $reclamationC->getReclamationsByUserId((int)$id_utilisateur);
    $message = "Réclamation supprimée avec succès.";
    $message_type = "success";
}

// Fonction pour vérifier si une réclamation a des réponses
function hasResponses($reclamationId, $reponseC) {
    $reponses = $reponseC->getReponsesByReclamationId($reclamationId);
    return !empty($reponses);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Hezni - Réclamations</title>
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
            display: none;
        }
        
        /* Styles pour les badges de réponse */
        .response-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-left: 0.5rem;
        }
        
        .has-response {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .no-response {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* Styles pour les filtres */
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
        }
        
        .filter-options {
            display: flex;
            gap: 0.5rem;
        }
        
        .filter-option {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-option.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .filter-option:hover:not(.active) {
            background-color: #e5e7eb;
        }
        
        /* Styles pour l'affichage des réponses */
        .reponses-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            border-left: 4px solid #3498db;
        }
        
        .reponse-item {
            background-color: white;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .reponse-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9em;
            color: #555;
        }
        
        .reponse-admin {
            font-weight: bold;
        }
        
        .reponse-date {
            color: #777;
        }
        
        .toggle-reponses {
            cursor: pointer;
            color: #3498db;
            font-weight: bold;
            margin-top: 5px;
            display: inline-block;
        }
        
        .no-reponses {
            text-align: center;
            padding: 15px;
            color: #666;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 5px;
            font-size: 0.9rem;
        }
        
        .action-btn.primary {
            background-color: #3498db;
        }
        
        .action-btn.edit {
            background-color: #f39c12;
        }
        
        .action-btn.delete {
            background-color: #e74c3c;
        }
        
        /* Style pour les messages censurés */
        .censored-text {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
        }
        
        /* Style pour la notification moderne */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .notification-bell {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        
        .notification-icon {
            width: 50px;
            height: 50px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        .notification-icon i {
            font-size: 22px;
            color: #e74c3c;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
        }
        
        .notification-popup {
            position: absolute;
            top: 60px;
            right: 0;
            width: 300px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            padding: 15px;
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .notification-popup.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .notification-header h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        
        .notification-content {
            margin-bottom: 15px;
        }
        
        .notification-message {
            background-color: #f8f9fa;
            border-left: 3px solid #e74c3c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }
        
        .notification-actions {
            display: flex;
            justify-content: space-between;
        }
        
        .notification-btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }
        
        .notification-btn.primary {
            background-color: #e74c3c;
            color: white;
            flex-grow: 1;
            margin-right: 10px;
        }
        
        .notification-btn.secondary {
            background-color: #f1f1f1;
            color: #333;
            flex-grow: 1;
        }
        
        .notification-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* Style pour l'info utilisateur */
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .user-info h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }
        
        .user-info-badge {
            display: flex;
            align-items: center;
            margin-left: 15px;
            padding: 5px 12px;
            background-color: #e8f4fd;
            border-radius: 20px;
            color: #3498db;
            font-size: 14px;
        }
        
        .user-info-badge i {
            margin-right: 5px;
            font-size: 16px;
        }
        
        /* Améliorations générales */
        .form-container {
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .form-container:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .form-title {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .form-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #e74c3c;
            border-radius: 3px;
        }
        
        input[type="text"], select, textarea {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus, select:focus, textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        button[type="submit"], button[type="button"] {
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        button[type="submit"]:hover, button[type="button"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .table-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }
        
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        th, td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Notification moderne -->
    <?php if ($hasResponses): ?>
    <div class="notification-container">
        <div class="notification-bell" id="notificationBell">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-count"><?php echo $totalResponses; ?></div>
            
            <div class="notification-popup" id="notificationPopup">
                <div class="notification-header">
                    <h3>Nouvelles réponses</h3>
                    <i class="fas fa-times" id="closeNotification" style="cursor: pointer;"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-message">
                        Vous avez <strong><?php echo $totalResponses; ?></strong> nouvelle(s) réponse(s) à vos réclamations.
                    </div>
                </div>
                <div class="notification-actions">
                    <a href="#reclamationsTable" class="notification-btn primary" onclick="showResponses()">
                        Voir les réponses
                    </a>
                    <a href="#" class="notification-btn secondary" id="dismissNotification">
                        Ignorer
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
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
            <h1 class="section-title">Votre opinion est essentielle pour nous. N'hésitez pas à nous faire part de toute insatisfaction ou suggestion : chaque message est pris en compte avec sérieux et bienveillance.</h1>
            
            <div class="image-row" style="text-align:center; margin: 20px 0;">
                <img src="assets/images/rec.jpg" alt="Réclamation" style="max-width: 300px; margin-right: 20px;">
            </div>
        </div>

        <?php if ($id_utilisateur): ?>
        <div class="user-info animate-fadeIn">
            <h2>Utilisateur #<?php echo $id_utilisateur; ?></h2>
            <?php if ($hasResponses): ?>
            <div class="user-info-badge">
                <i class="fas fa-comment-dots"></i>
                <?php echo $totalResponses; ?> réponse(s) à vos réclamations
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

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
            <form method="POST" class="search-form" id="searchForm" onsubmit="return validateSearchForm()">
                <label class="form-label">ID Utilisateur :</label>
                <div class="row">
                    <input type="text" name="id_utilisateur_selectionne" id="id_utilisateur_selectionne" 
                        class="search-field" placeholder="Entrez l'ID utilisateur (chiffres uniquement)" 
                        value="<?php echo $id_utilisateur; ?>" 
                        oninput="validateNumberInput(this)">
                    <div class="error-message" id="id_utilisateur_selectionne_error">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Veuillez entrer un ID utilisateur valide.
                    </div>
                    <button type="submit" name="afficher"><i class="fas fa-search"></i> Afficher Réclamations</button>
                </div>
            </form>
        </div>

        <!-- Formulaire Ajout/Modification -->
        <div class="form-container animate-slideInLeft">
            <h2 class="form-title">Ajouter une Réclamation</h2>
            <form method="POST" id="reclamationForm" onsubmit="return validateReclamationForm()">
                <label class="form-label">Type Utilisateur :</label>
                <select name="type_utilisateur" id="type_utilisateur" onchange="updateSujetOptions()">
                    <option value="">-- Choisissez un type d'utilisateur --</option>
                    <option value="etudiant">Etudiant</option>
                    <option value="conducteur">Conducteur</option>
                </select>
                <div class="error-message" id="type_utilisateur_error">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Veuillez sélectionner un type d'utilisateur.
                </div>
                
                <label class="form-label">ID Utilisateur :</label>
                <input type="text" name="id_utilisateur" id="id_utilisateur" placeholder="ID Utilisateur" value="<?php echo $id_utilisateur; ?>"
                        oninput="validateNumberInput(this)">
                <div class="error-message" id="id_utilisateur_error">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Veuillez entrer un ID utilisateur valide.
                </div>
                
                <label class="form-label">Sujet :</label>
                <select name="sujet" id="sujet">
                    <option value="">-- Sélectionnez un sujet --</option>
                </select>
                <div class="error-message" id="sujet_error">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Veuillez sélectionner un sujet.
                </div>

                <label class="form-label">Message :</label>
                <textarea name="message" id="message" placeholder="Votre message"></textarea>
                <div class="error-message" id="message_error">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Veuillez entrer un message d'au moins 10 caractères.
                </div>
                
                <!-- Message d'avertissement pour les gros mots -->
                <div class="error-message" id="badWordsWarning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Attention: Votre message contient des termes inappropriés. Veuillez utiliser un langage respectueux.
                </div>

                <div class="row">
                    <button type="submit" name="ajouter"><i class="fas fa-plus"></i> Ajouter</button>
                    <button type="button" onclick="resetForm()"><i class="fas fa-redo"></i> Réinitialiser</button>
                </div>
            </form>
        </div>

        <!-- Tableau des réclamations -->
        <?php if (!empty($list)): ?>
            
            
            <div class="table-container animate-slideInBottom" id="reclamationsTable">
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
                            <?php 
                            $hasReclamationResponses = hasResponses($reclamation['id'], $reponseC); 
                            $reponses = $reponseC->getReponsesByReclamationId($reclamation['id']);
                            ?>
                            <tr>
                                <td><?php echo $reclamation['id']; ?></td>
                                <td><?php echo $reclamation['type_utilisateur']; ?></td>
                                <td><?php echo $reclamation['id_utilisateur']; ?></td>
                                <td><?php echo $reclamation['sujet']; ?></td>
                                <td><?php echo substr($reclamation['message'], 0, 50) . (strlen($reclamation['message']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo isset($reclamation['date_creation']) ? $reclamation['date_creation'] : (isset($reclamation['date_reclamation']) ? $reclamation['date_reclamation'] : ''); ?></td>
                                <td>
                                    <?php if (isset($reponseC) && method_exists($reponseC, 'getReponsesByReclamationId')): ?>
                                    <span class="toggle-reponses" onclick="toggleReponses(<?php echo $reclamation['id']; ?>)">
                                        <i class="fas fa-eye"></i> Voir réponses
                                        <?php if ($hasReclamationResponses): ?>
                                            <span class="response-badge has-response"><?php echo count($reponses); ?></span>
                                        <?php else: ?>
                                            <span class="response-badge no-response">0</span>
                                        <?php endif; ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <a href="edit.php?id=<?php echo $reclamation['id']; ?>" class="action-btn edit">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">
                                        <input type="hidden" name="id_utilisateur" value="<?php echo $reclamation['id_utilisateur']; ?>">
                                        <button type="submit" name="supprimer" class="action-btn delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php if (isset($reponseC) && method_exists($reponseC, 'getReponsesByReclamationId')): ?>
                            <tr id="reponses-<?php echo $reclamation['id']; ?>" style="display:none;">
                                <td colspan="8">
                                    <div class="reponses-container">
                                        <h4>Réponses à cette réclamation</h4>
                                        <?php if (!empty($reponses)): ?>
                                            <?php foreach ($reponses as $reponse): ?>
                                                <div class="reponse-item">
                                                    <div class="reponse-header">
                                                        <span class="reponse-admin">Administrateur</span>
                                                        <span class="reponse-date"><?php echo date('d/m/Y H:i', strtotime($reponse['date_reponse'])); ?></span>
                                                    </div>
                                                    <p><?php echo nl2br(htmlspecialchars($reponse['message'])); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="no-reponses">
                                                <i class="fas fa-comment-slash fa-2x"></i>
                                                <p>Aucune réponse pour cette réclamation</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
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
    <script>
        // Fonction pour valider que l'input ne contient que des chiffres
        function validateNumberInput(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
        
        // Fonction pour réinitialiser le formulaire
        function resetForm() {
            document.getElementById('reclamationForm').reset();
            
            // Masquer tous les messages d'erreur
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(function(errorMsg) {
                errorMsg.style.display = 'none';
            });
            
            // Supprimer les classes d'erreur des champs
            const formInputs = document.querySelectorAll('#reclamationForm input, #reclamationForm select, #reclamationForm textarea');
            formInputs.forEach(function(input) {
                input.classList.remove('error-field');
            });
        }
        
        // Fonction pour mettre à jour les options de sujet
        function updateSujetOptions() {
            const type = document.querySelector('[name="type_utilisateur"]').value;
            const sujetSelect = document.querySelector('[name="sujet"]');
            
            // Vider les options actuelles
            sujetSelect.innerHTML = '<option value="">-- Sélectionnez un sujet --</option>';
            
            // Ajouter les options en fonction du type d'utilisateur
            if (type === 'etudiant') {
                sujetSelect.innerHTML += `
                    <option value="Problème de réservation">Problème de réservation</option>
                    <option value="Conducteur inapproprié">Conducteur inapproprié</option>
                    <option value="Paiement">Paiement</option>
                    <option value="Autre">Autre</option>
                `;
            } else if (type === 'conducteur') {
                sujetSelect.innerHTML += `
                    <option value="Problème de paiement">Problème de paiement</option>
                    <option value="Passager inapproprié">Passager inapproprié</option>
                    <option value="Annulation">Annulation</option>
                    <option value="Autre">Autre</option>
                `;
            }
        }
        
        // Fonction pour afficher/masquer les réponses
        function toggleReponses(reclamationId) {
            const row = document.getElementById(`reponses-${reclamationId}`);
            if (row && row.style.display === 'none') {
                row.style.display = 'table-row';
            } else if (row) {
                row.style.display = 'none';
            }
        }
        
        // Fonction pour afficher toutes les réponses
        function showResponses() {
            // Trouver la première réclamation avec des réponses
            const toggleButtons = document.querySelectorAll('.toggle-reponses');
            for (let i = 0; i < toggleButtons.length; i++) {
                if (toggleButtons[i].querySelector('.has-response')) {
                    toggleButtons[i].click();
                    break;
                }
            }
        }
        
        // Fonction pour confirmer la suppression
        function confirmDelete() {
            return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');
        }
        
        // Fonction pour valider le formulaire de recherche
        function validateSearchForm() {
            const idUtilisateur = document.getElementById('id_utilisateur_selectionne');
            const idUtilisateurError = document.getElementById('id_utilisateur_selectionne_error');
            let isValid = true;
            
            // Vérifier l'ID utilisateur
            if (!idUtilisateur.value.trim()) {
                idUtilisateur.classList.add('error-field');
                idUtilisateurError.style.display = 'block';
                isValid = false;
            } else {
                idUtilisateur.classList.remove('error-field');
                idUtilisateurError.style.display = 'none';
            }
            
            return isValid;
        }
        
        // Fonction pour valider le formulaire de réclamation
        function validateReclamationForm() {
            const typeUtilisateur = document.getElementById('type_utilisateur');
            const idUtilisateur = document.getElementById('id_utilisateur');
            const sujet = document.getElementById('sujet');
            const message = document.getElementById('message');
            
            const typeUtilisateurError = document.getElementById('type_utilisateur_error');
            const idUtilisateurError = document.getElementById('id_utilisateur_error');
            const sujetError = document.getElementById('sujet_error');
            const messageError = document.getElementById('message_error');
            
            let isValid = true;
            
            // Vérifier le type d'utilisateur
            if (!typeUtilisateur.value) {
                typeUtilisateur.classList.add('error-field');
                typeUtilisateurError.style.display = 'block';
                isValid = false;
            } else {
                typeUtilisateur.classList.remove('error-field');
                typeUtilisateurError.style.display = 'none';
            }
            
            // Vérifier l'ID utilisateur
            if (!idUtilisateur.value.trim()) {
                idUtilisateur.classList.add('error-field');
                idUtilisateurError.style.display = 'block';
                isValid = false;
            } else {
                idUtilisateur.classList.remove('error-field');
                idUtilisateurError.style.display = 'none';
            }
            
            // Vérifier le sujet
            if (!sujet.value) {
                sujet.classList.add('error-field');
                sujetError.style.display = 'block';
                isValid = false;
            } else {
                sujet.classList.remove('error-field');
                sujetError.style.display = 'none';
            }
            
            // Vérifier le message
            if (!message.value.trim()) {
                message.classList.add('error-field');
                messageError.textContent = "Veuillez entrer un message.";
                messageError.style.display = 'block';
                isValid = false;
            } else if (message.value.trim().length < 10) {
                message.classList.add('error-field');
                messageError.textContent = "Le message doit contenir au moins 10 caractères.";
                messageError.style.display = 'block';
                isValid = false;
            } else {
                message.classList.remove('error-field');
                messageError.style.display = 'none';
            }
            
            return isValid;
        }
        
        // Gestion des notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBell = document.getElementById('notificationBell');
            const notificationPopup = document.getElementById('notificationPopup');
            const closeNotification = document.getElementById('closeNotification');
            const dismissNotification = document.getElementById('dismissNotification');
            
            // Afficher la popup de notification après 1 seconde
            if (notificationBell) {
                setTimeout(function() {
                    notificationPopup.classList.add('show');
                }, 1000);
                
                // Gérer le clic sur la cloche
                notificationBell.addEventListener('click', function(e) {
                    if (e.target !== closeNotification && e.target !== dismissNotification) {
                        notificationPopup.classList.toggle('show');
                    }
                });
                
                // Fermer la notification
                if (closeNotification) {
                    closeNotification.addEventListener('click', function() {
                        notificationPopup.classList.remove('show');
                    });
                }
                
                // Ignorer la notification
                if (dismissNotification) {
                    dismissNotification.addEventListener('click', function(e) {
                        e.preventDefault();
                        notificationPopup.classList.remove('show');
                    });
                }
                
                // Fermer la popup si on clique ailleurs
                document.addEventListener('click', function(e) {
                    if (notificationPopup.classList.contains('show') && 
                        !notificationBell.contains(e.target) && 
                        !notificationPopup.contains(e.target)) {
                        notificationPopup.classList.remove('show');
                    }
                });
            }
            
            // Fonction pour vérifier les gros mots en temps réel
            const messageTextarea = document.querySelector('textarea[name="message"]');
            const badWordsWarning = document.getElementById('badWordsWarning');
            
            // Liste de gros mots pour la vérification côté client
            const badWords = [
                'merde', 'putain', 'connard', 'salope', 'pute', 'enculé', 'con', 'bite', 
                'couille', 'foutre', 'cul', 'bordel', 'bâtard', 'salaud', 'enfoiré', 
                'nique', 'niquer', 'pd', 'pédé', 'tapette', 'gouine', 'négro', 'bougnoule'
            ];
            
            if (messageTextarea && badWordsWarning) {
                messageTextarea.addEventListener('input', function() {
                    const text = this.value.toLowerCase();
                    let containsBadWords = false;
                    
                    for (const word of badWords) {
                        const regex = new RegExp('\\b' + word + '\\b', 'i');
                        if (regex.test(text)) {
                            containsBadWords = true;
                            break;
                        }
                    }
                    
                    if (containsBadWords) {
                        badWordsWarning.style.display = 'block';
                    } else {
                        badWordsWarning.style.display = 'none';
                    }
                });
            }
            
            // Initialiser le type d'utilisateur si l'ID est déjà rempli
            if (document.getElementById('id_utilisateur').value) {
                updateSujetOptions();
            }
        });
    </script>
</body>
</html>