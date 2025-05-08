<?php
require_once '../../config.php';
require_once '../../controllers/ReclamationController.php';
require_once '../../controllers/ReponseController.php';
require_once '../../models/Reponse.php';

$reclamationC = new ReclamationController();
$reponseC = new ReponseController();

// Paramètres
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$id_reclamation = isset($_GET['id']) ? (int)$_GET['id'] : null;
$id_admin = 1; // À remplacer par l'ID admin connecté

// Récupérer le nombre de notifications non lues
$unreadNotificationsCount = $reclamationC->getUnreadCount();
$unreadNotifications = $reclamationC->getUnreadReclamations();

// Marquer toutes les notifications comme lues si demandé
if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
    $reclamationC->markAllAsRead();
    header("Location: reponses.php");
    exit;
}

// Marquer la réclamation comme lue si on consulte une réclamation spécifique
if ($id_reclamation) {
    $reclamationC->markAsRead($id_reclamation);
}

// Recherche par ID utilisateur
$id_utilisateur_search = null;
$user_reclamations = [];
$hasNewReclamations = false;
$newReclamationsCount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_user'])) {
    $id_utilisateur_search = (int)$_POST['id_utilisateur'];
    $user_reclamations = $reclamationC->getReclamationsByUserId($id_utilisateur_search);
    
    // Vérifier s'il y a de nouvelles réclamations (non lues)
    foreach ($user_reclamations as $rec) {
        if (isset($rec['lu']) && $rec['lu'] == 0) {
            $hasNewReclamations = true;
            $newReclamationsCount++;
        }
    }
}

// Récupérer les données
$reclamation = $id_reclamation ? $reclamationC->showReclamation($id_reclamation) : null;
$reponses = $id_reclamation ? $reponseC->getReponsesByReclamationId($id_reclamation) : [];
$reclamations = $reponseC->getReclamationsWithReponses();

// Filtrer les réclamations
if ($filter !== 'all') {
    $reclamations = array_filter($reclamations, function($rec) use ($filter) {
        return ($filter === 'with_response' && $rec['has_response'] > 0) || 
               ($filter === 'without_response' && $rec['has_response'] == 0);
    });
}

// Trier les réclamations
usort($reclamations, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'id_asc': return $a['id'] - $b['id'];
        case 'id_desc': return $b['id'] - $a['id'];
        case 'date_asc': return strtotime($a['date_reclamation']) - strtotime($b['date_reclamation']);
        case 'date_desc': return strtotime($b['date_reclamation']) - strtotime($a['date_reclamation']);
        case 'user_asc': return $a['id_utilisateur'] - $b['id_utilisateur'];
        case 'user_desc': return $b['id_utilisateur'] - $a['id_utilisateur'];
        default: return 0;
    }
});

// Gestion des formulaires
$message = null;
$message_type = null;

// Ajouter une réponse
if (isset($_POST['ajouter_reponse'])) {
    $errors = [];
    if (empty($_POST['message'])) {
        $errors[] = "Le message est requis";
    } elseif (strlen($_POST['message']) < 10) {
        $errors[] = "Le message doit contenir au moins 10 caractères";
    }

    if (empty($errors)) {
        $reponse = new Reponse(
            null,
            $_POST['id_reclamation'],
            $_POST['message'],
            new DateTime(),
            $id_admin
        );
        
        if ($reponseC->addReponse($reponse)) {
            $message = "Réponse ajoutée avec succès.";
            $message_type = "success";
            $reponses = $reponseC->getReponsesByReclamationId($_POST['id_reclamation']);
        } else {
            $message = "Erreur lors de l'ajout de la réponse.";
            $message_type = "error";
        }
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
}

// Modifier une réponse
if (isset($_POST['modifier_reponse'])) {
    $errors = [];
    if (empty($_POST['message'])) {
        $errors[] = "Le message est requis";
    } elseif (strlen($_POST['message']) < 10) {
        $errors[] = "Le message doit contenir au moins 10 caractères";
    }

    if (empty($errors)) {
        $reponse = new Reponse(
            $_POST['id_reponse'],
            $_POST['id_reclamation'],
            $_POST['message'],
            new DateTime(),
            $id_admin
        );
        
        // Correction: Passer l'ID comme second paramètre à updateReponse
        if ($reponseC->updateReponse($reponse, $_POST['id_reponse'])) {
            $message = "Réponse modifiée avec succès.";
            $message_type = "success";
            $reponses = $reponseC->getReponsesByReclamationId($_POST['id_reclamation']);
        } else {
            $message = "Erreur lors de la modification de la réponse.";
            $message_type = "error";
        }
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
}

// Supprimer une réponse
if (isset($_POST['supprimer_reponse'])) {
    if ($reponseC->deleteReponse($_POST['id_reponse'])) {
        $message = "Réponse supprimée avec succès.";
        $message_type = "success";
        $reponses = $reponseC->getReponsesByReclamationId($_POST['id_reclamation']);
    } else {
        $message = "Erreur lors de la suppression de la réponse.";
        $message_type = "error";
    }
}

// Supprimer une réclamation
if (isset($_POST['supprimer_reclamation'])) {
    if ($reclamationC->deleteReclamation($_POST['id_reclamation'])) {
        $message = "Réclamation supprimée avec succès.";
        $message_type = "success";
        header("Location: reponses.php");
        exit;
    } else {
        $message = "Erreur lors de la suppression de la réclamation.";
        $message_type = "error";
    }
}

// Statistiques
$total_reclamations = count($reclamations);
$reclamations_repondues = count(array_filter($reclamations, function($rec) {
    return $rec['has_response'] > 0;
}));
$reclamations_en_attente = $total_reclamations - $reclamations_repondues;
$taux_reponse = $total_reclamations > 0 ? round(($reclamations_repondues / $total_reclamations) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réponses - Admin</title>
    <link rel="stylesheet" href="../front/assets/css/back.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        /* Styles généraux */
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .info { background-color: #d1ecf1; color: #0c5460; }
        
        /* Styles pour les notifications */
        .notification-badge {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            cursor: pointer;
        }
        .notification-icon {
            font-size: 22px;
            color: #fff;
            transition: all 0.3s ease;
        }
        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
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
        
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 300px;
            background-color: #2a2a2a;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            animation: fadeInDown 0.3s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #444;
        }
        
        .notification-header h3 {
            margin: 0;
            font-size: 16px;
            color: #fff;
        }
        
        .notification-header a {
            color: #e74c3c;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .notification-header a:hover {
            color: #ff6b6b;
        }
        
        .notification-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #444;
            transition: background-color 0.3s;
        }
        
        .notification-item:hover {
            background-color: #333;
        }
        
        .notification-item a {
            color: #fff;
            text-decoration: none;
            display: block;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #95a5a6;
            margin-top: 5px;
        }
        
        .notification-empty {
            padding: 20px;
            text-align: center;
            color: #95a5a6;
        }
        
        /* Styles pour la recherche utilisateur */
        .user-search-container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .user-search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .user-search-input {
            flex-grow: 1;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }
        
        .user-search-btn {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .user-search-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        /* Notification popup pour les nouvelles réclamations */
        .user-notification-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2a2a2a;
            border-left: 4px solid #e74c3c;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1100;
            display: none;
            animation: slideInRight 0.5s ease, pulse 2s infinite;
            max-width: 350px;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .user-notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .user-notification-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            margin: 0;
        }
        
        .user-notification-title i {
            color: #e74c3c;
        }
        
        .user-notification-close {
            background: none;
            border: none;
            color: #95a5a6;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .user-notification-close:hover {
            color: #fff;
        }
        
        .user-notification-content {
            color: #ecf0f1;
            margin-bottom: 15px;
        }
        
        .user-notification-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .user-notification-btn {
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
        }
        
        .user-notification-btn.primary {
            background-color: #e74c3c;
            color: white;
        }
        
        .user-notification-btn.secondary {
            background-color: #34495e;
            color: white;
        }
        
        .user-notification-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* Styles pour les résultats de recherche utilisateur */
        .user-results-container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .user-results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #444;
        }
        
        .user-results-title {
            font-size: 18px;
            color: #fff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .user-badge.new {
            background-color: #e74c3c;
            color: white;
        }
        
        .user-badge i {
            margin-right: 5px;
        }
        
        /* Notification toast pour les nouvelles réclamations */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2a2a2a;
            border-left: 4px solid #e74c3c;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1100;
            display: none;
            animation: slideInUp 0.5s ease;
            max-width: 350px;
            transition: all 0.3s ease;
        }
        
        @keyframes slideInUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .toast-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .toast-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            margin: 0;
        }
        
        .toast-title i {
            color: #e74c3c;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: #95a5a6;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .toast-content {
            color: #ecf0f1;
            margin-bottom: 10px;
        }
        
        .toast-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .toast-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
        }
        
        .toast-btn.primary {
            background-color: #e74c3c;
            color: white;
        }
        
        .toast-btn.secondary {
            background-color: #34495e;
            color: white;
        }
        
        /* Filtres et tri */
        .filter-sort-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            padding: 10px;
        }
        .filter-group, .sort-group { display: flex; gap: 10px; }
        .filter-btn, .sort-btn {
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .filter-btn:hover, .sort-btn:hover { background-color: #444; }
        .filter-btn.active, .sort-btn.active { background-color: #e74c3c; }
        
        /* Badges de statut */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            min-width: 80px;
        }
        .status-answered { background-color: #2ecc71; color: white; }
        .status-pending { background-color: #f39c12; color: white; }
        
        /* Détails réclamation */
        .reclamation-details {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        /* Réponses */
        .reponse-item {
            background-color: #2a2a2a;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        
        /* Formulaire */
        .form-container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .form-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #fff;
        }
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            resize: vertical;
        }
        
        /* Boutons */
        .action-buttons { display: flex; gap: 10px; margin-top: 15px; }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn.edit { background-color: #3498db; color: white; }
        .btn.delete { background-color: #e74c3c; color: white; }
        .btn.secondary { background-color: #7f8c8d; color: white; }
        
        /* Validation */
        .error-message { color: #e74c3c; font-size: 0.8rem; margin-top: 5px; }
        .character-counter { font-size: 0.8rem; color: #95a5a6; text-align: right; margin-top: 5px; }
        .error-field { border-color: #e74c3c !important; }
        
        /* Tableau */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #444; }
        th { background-color: #333; color: white; }
        tr:hover { background-color: #2a2a2a; }
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 5px;
        }
        .action-btn.delete { background-color: #e74c3c; }
        
        /* Highlight pour les nouvelles réclamations */
        .new-reclamation {
            background-color: rgba(231, 76, 60, 0.1);
            transition: background-color 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Menu latéral -->
        <div class="sidebar">
            <div class="logo-section">
                <img src="../front/assets/images/logo-rouge.png" alt="Logo">
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
                    <h1>GESTION DES RÉPONSES AUX RÉCLAMATIONS</h1>
                </div>
                <div class="user-info">
                    <!-- Notification Icon -->
                    <div class="notification-badge" id="admin-notifications">
                        <i class="fas fa-bell notification-icon"></i>
                        <?php if ($unreadNotificationsCount > 0): ?>
                            <span class="notification-count"><?php echo $unreadNotificationsCount; ?></span>
                        <?php endif; ?>
                        
                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown" id="admin-notification-dropdown">
                            <div class="notification-header">
                                <h3>Notifications</h3>
                                <?php if ($unreadNotificationsCount > 0): ?>
                                    <a href="?mark_all_read=1">Tout marquer comme lu</a>
                                <?php endif; ?>
                            </div>
                            
                            <ul class="notification-list" id="admin-notification-list">
                                <?php if (empty($unreadNotifications)): ?>
                                    <div class="notification-empty">
                                        <i class="fas fa-bell-slash"></i>
                                        <p>Aucune notification</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($unreadNotifications as $notification): ?>
                                        <li class="notification-item">
                                            <a href="reponses.php?id=<?php echo $notification['id']; ?>">
                                                <strong>Nouvelle réclamation</strong>
                                                <div><?php echo htmlspecialchars($notification['sujet']); ?></div>
                                                <div class="notification-time">
                                                    <?php echo date('d/m/Y H:i', strtotime($notification['date_reclamation'])); ?>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="avatar">AD</div>
                    <span>Administrateur</span>
                </div>
            </header>

            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Recherche par ID utilisateur -->
            <div class="user-search-container">
                <h2 class="section-title"><i class="fas fa-search"></i> Rechercher par ID utilisateur</h2>
                <form method="POST" class="user-search-form">
                    <input type="number" name="id_utilisateur" class="user-search-input" placeholder="Entrez l'ID utilisateur" value="<?php echo $id_utilisateur_search; ?>" required>
                    <button type="submit" name="search_user" class="user-search-btn">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>

            <!-- Notification popup pour les nouvelles réclamations -->
            <?php if ($hasNewReclamations): ?>
            <div class="user-notification-popup" id="userNotificationPopup">
                <div class="user-notification-header">
                    <h3 class="user-notification-title">
                        <i class="fas fa-bell"></i> Nouvelles réclamations
                    </h3>
                    <button class="user-notification-close" id="closeUserNotification">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="user-notification-content">
                    <p>L'utilisateur #<?php echo $id_utilisateur_search; ?> a <strong><?php echo $newReclamationsCount; ?></strong> nouvelle(s) réclamation(s) non traitée(s).</p>
                </div>
                <div class="user-notification-actions">
                    <a href="#userReclamationsResults" class="user-notification-btn primary">
                        Voir les réclamations
                    </a>
                    <button class="user-notification-btn secondary" id="dismissUserNotification">
                        Ignorer
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Toast de notification pour les nouvelles réclamations -->
            <div class="toast-notification" id="newReclamationToast">
                <div class="toast-header">
                    <h3 class="toast-title">
                        <i class="fas fa-bell"></i> Nouvelle réclamation
                    </h3>
                    <button class="toast-close" id="closeToast">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-content">
                    <p>Une nouvelle réclamation a été ajoutée.</p>
                </div>
                <div class="toast-actions">
                    <a href="reponses.php" class="toast-btn primary">
                        Voir les réclamations
                    </a>
                    <button class="toast-btn secondary" id="dismissToast">
                        Ignorer
                    </button>
                </div>
            </div>

            <!-- Résultats de recherche utilisateur -->
            <?php if ($id_utilisateur_search && !empty($user_reclamations)): ?>
            <div class="user-results-container" id="userReclamationsResults">
                <div class="user-results-header">
                    <h2 class="user-results-title">
                        <i class="fas fa-user"></i> Réclamations de l'utilisateur #<?php echo $id_utilisateur_search; ?>
                        <?php if ($hasNewReclamations): ?>
                        <span class="user-badge new">
                            <i class="fas fa-bell"></i> <?php echo $newReclamationsCount; ?> nouvelle(s)
                        </span>
                        <?php endif; ?>
                    </h2>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Sujet</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_reclamations as $rec): ?>
                            <?php 
                            $hasResponse = isset($rec['has_response']) ? $rec['has_response'] > 0 : false;
                            $isNew = isset($rec['lu']) && $rec['lu'] == 0;
                            ?>
                            <tr class="<?php echo $isNew ? 'new-reclamation' : ''; ?>">
                                <td><?php echo $rec['id']; ?></td>
                                <td><?php echo $rec['type_utilisateur']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($rec['sujet']); ?>
                                    <?php if ($isNew): ?>
                                        <span class="user-badge new" style="font-size: 10px; padding: 2px 5px;">Nouveau</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($rec['date_reclamation'])); ?></td>
                                <td>
                                    <?php if ($hasResponse): ?>
                                        <span class="status-badge status-answered">Répondue</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="reponses.php?id=<?php echo $rec['id']; ?>" class="btn">
                                            <?php if ($hasResponse): ?>
                                                <i class="fas fa-eye"></i> Voir les réponses
                                            <?php else: ?>
                                                <i class="fas fa-reply"></i> Répondre
                                            <?php endif; ?>
                                        </a>
                                        
                                        <a href="edit-reclamation.php?id=<?php echo $rec['id']; ?>" class="btn edit">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                            <input type="hidden" name="id_reclamation" value="<?php echo $rec['id']; ?>">
                                            <button type="submit" name="supprimer_reclamation" class="btn delete">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php elseif ($id_utilisateur_search): ?>
            <div class="user-results-container">
                <div class="notification-empty">
                    <i class="fas fa-user-slash fa-3x" style="margin-bottom: 15px;"></i>
                    <p>Aucune réclamation trouvée pour l'utilisateur #<?php echo $id_utilisateur_search; ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($reclamation): ?>
                <!-- Détails de la réclamation -->
                <a href="reponses.php" class="btn secondary"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Détails de la réclamation</h2>
                    <div class="reclamation-details">
                        <h3><?php echo htmlspecialchars($reclamation['sujet']); ?></h3>
                        <p>
                            <strong>Type utilisateur:</strong> <?php echo htmlspecialchars($reclamation['type_utilisateur']); ?><br>
                            <strong>ID utilisateur:</strong> <?php echo $reclamation['id_utilisateur']; ?><br>
                            <strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($reclamation['date_reclamation'])); ?>
                        </p>
                        <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($reclamation['message'])); ?></p>
                        
                        <div class="action-buttons">
                            <a href="edit-reclamation.php?id=<?php echo $reclamation['id']; ?>" class="btn edit">
                                <i class="fas fa-edit"></i> Modifier la réclamation
                            </a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                <input type="hidden" name="id_reclamation" value="<?php echo $reclamation['id']; ?>">
                                <button type="submit" name="supprimer_reclamation" class="btn delete">
                                    <i class="fas fa-trash"></i> Supprimer la réclamation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Liste des réponses -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-comments"></i> Réponses (<?php echo count($reponses); ?>)</h2>
                    
                    <?php if (!empty($reponses)): ?>
                        <?php foreach ($reponses as $reponse): ?>
                            <div class="reponse-item">
                                <p>
                                    <strong>Admin #<?php echo $reponse['id_admin']; ?></strong> - 
                                    <?php echo date('d/m/Y H:i', strtotime($reponse['date_reponse'])); ?>
                                </p>
                                <p><?php echo nl2br(htmlspecialchars($reponse['message'])); ?></p>
                                <div class="action-buttons">
                                    <button type="button" onclick="editReponse(<?php echo $reponse['id']; ?>, '<?php echo addslashes($reponse['message']); ?>')" class="btn edit">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?')">
                                        <input type="hidden" name="id_reponse" value="<?php echo $reponse['id']; ?>">
                                        <input type="hidden" name="id_reclamation" value="<?php echo $reclamation['id']; ?>">
                                        <button type="submit" name="supprimer_reponse" class="btn delete">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucune réponse n'a encore été donnée à cette réclamation.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Formulaire de réponse -->
                <div class="form-container">
                    <div class="form-title" id="reponse-form-title">
                        <?php if (empty($reponses)): ?>
                            <i class="fas fa-reply"></i> Répondre à la réclamation
                        <?php else: ?>
                            <i class="fas fa-plus"></i> Ajouter une autre réponse
                        <?php endif; ?>
                    </div>
                    <form method="POST" id="reponseForm" onsubmit="return validateReponseForm()">
                        <input type="hidden" name="id_reclamation" value="<?php echo $reclamation['id']; ?>">
                        <input type="hidden" name="id_reponse" id="id_reponse" value="">
                        
                        <div class="form-group">
                            <textarea name="message" id="reponse_message" rows="5" placeholder="Votre réponse à cette réclamation" required></textarea>
                            <div id="message-error" class="error-message" style="display: none;"></div>
                            <div id="message-counter" class="character-counter" style="display: none;"></div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" name="ajouter_reponse" id="submit-btn">
                                <i class="fas fa-paper-plane"></i> Envoyer la réponse
                            </button>
                            <button type="button" onclick="resetForm()" class="btn secondary">
                                <i class="fas fa-redo"></i> Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
                
            <?php else: ?>
                <!-- Statistiques -->
                <div class="stats-container">
                    <h3>Statistiques des réclamations</h3>
                    <ul>
                        <li>Total des réclamations : <strong><?php echo $total_reclamations; ?></strong></li>
                        <li>Réclamations répondues : <strong><?php echo $reclamations_repondues; ?></strong></li>
                        <li>Réclamations en attente : <strong><?php echo $reclamations_en_attente; ?></strong></li>
                        <li>Taux de réponse : <strong><?php echo $taux_reponse; ?>%</strong></li>
                    </ul>
                </div>
                
                <!-- Filtres et tri -->
                <div class="filter-sort-container">
                    <div class="filter-group">
                        <a href="?filter=all&sort=<?php echo $sort; ?>" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                            <i class="fas fa-list"></i> Toutes
                        </a>
                        <a href="?filter=with_response&sort=<?php echo $sort; ?>" class="filter-btn <?php echo $filter === 'with_response' ? 'active' : ''; ?>">
                            <i class="fas fa-comment-dots"></i> Avec réponse
                        </a>
                        <a href="?filter=without_response&sort=<?php echo $sort; ?>" class="filter-btn <?php echo $filter === 'without_response' ? 'active' : ''; ?>">
                            <i class="fas fa-comment-slash"></i> Sans réponse
                        </a>
                    </div>
                    
                    <div class="sort-group">
                        <a href="?filter=<?php echo $filter; ?>&sort=id_asc" class="sort-btn <?php echo $sort === 'id_asc' ? 'active' : ''; ?>">
                            <i class="fas fa-sort-numeric-down"></i> ID ↑
                        </a>
                        <a href="?filter=<?php echo $filter; ?>&sort=id_desc" class="sort-btn <?php echo $sort === 'id_desc' ? 'active' : ''; ?>">
                            <i class="fas fa-sort-numeric-down-alt"></i> ID ↓
                        </a>
                        <a href="?filter=<?php echo $filter; ?>&sort=date_asc" class="sort-btn <?php echo $sort === 'date_asc' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i> Date ↑
                        </a>
                        <a href="?filter=<?php echo $filter; ?>&sort=date_desc" class="sort-btn <?php echo $sort === 'date_desc' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i> Date ↓
                        </a>
                        <a href="?filter=<?php echo $filter; ?>&sort=user_asc" class="sort-btn <?php echo $sort === 'user_asc' ? 'active' : ''; ?>">
                            <i class="fas fa-user"></i> Utilisateur ↑
                        </a>
                        <a href="?filter=<?php echo $filter; ?>&sort=user_desc" class="sort-btn <?php echo $sort === 'user_desc' ? 'active' : ''; ?>">
                            <i class="fas fa-user"></i> Utilisateur ↓
                        </a>
                    </div>
                </div>

                <!-- Liste des réclamations -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-list"></i> Liste des réclamations</h2>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type Utilisateur</th>
                                <th>ID Utilisateur</th>
                                <th>Sujet</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($reclamations)): ?>
                                <?php foreach ($reclamations as $rec): ?>
                                    <tr>
                                        <td><?php echo $rec['id']; ?></td>
                                        <td><?php echo $rec['type_utilisateur']; ?></td>
                                        <td><?php echo $rec['id_utilisateur']; ?></td>
                                        <td><?php echo $rec['sujet']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($rec['date_reclamation'])); ?></td>
                                        <td>
                                            <?php if ($rec['has_response'] > 0): ?>
                                                <span class="status-badge status-answered">Répondue</span>
                                            <?php else: ?>
                                                <span class="status-badge status-pending">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="reponses.php?id=<?php echo $rec['id']; ?>" class="btn">
                                                    <?php if ($rec['has_response'] > 0): ?>
                                                        <i class="fas fa-eye"></i> Voir les réponses
                                                    <?php else: ?>
                                                        <i class="fas fa-reply"></i> Répondre
                                                    <?php endif; ?>
                                                </a>
                                                
                                                <a href="edit-reclamation.php?id=<?php echo $rec['id']; ?>" class="btn edit">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                
                                                <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                                    <input type="hidden" name="id_reclamation" value="<?php echo $rec['id']; ?>">
                                                    <button type="submit" name="supprimer_reclamation" class="btn delete">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">Aucune réclamation trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Fonction pour éditer une réponse
    function editReponse(id, message) {
        document.getElementById("id_reponse").value = id;
        document.getElementById("reponse_message").value = message;
        document.getElementById("reponse-form-title").innerHTML = '<i class="fas fa-edit"></i> Modifier la réponse';
        document.getElementById("submit-btn").name = "modifier_reponse";
        document.getElementById("submit-btn").innerHTML = '<i class="fas fa-save"></i> Mettre à jour';
        document.getElementById("reponseForm").scrollIntoView({ behavior: "smooth" });
        validateMessageLength();
    }

    // Fonction pour réinitialiser le formulaire
    function resetForm() {
        document.getElementById("id_reponse").value = "";
        document.getElementById("reponse_message").value = "";
        const responsesCount = document.querySelectorAll(".reponse-item").length;
        
        if (responsesCount > 0) {
            document.getElementById("reponse-form-title").innerHTML = '<i class="fas fa-plus"></i> Ajouter une autre réponse';
        } else {
            document.getElementById("reponse-form-title").innerHTML = '<i class="fas fa-reply"></i> Répondre à la réclamation';
        }
        
        document.getElementById("submit-btn").name = "ajouter_reponse";
        document.getElementById("submit-btn").innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer la réponse';
        hideError("message-error");
        hideElement("message-counter");
        document.getElementById("reponse_message").classList.remove("error-field");
    }

    // Fonction pour valider la longueur du message
    function validateMessageLength() {
        const messageField = document.getElementById("reponse_message");
        const messageValue = messageField.value.trim();
        const counterElement = document.getElementById("message-counter");
        
        if (messageValue.length > 0) {
            counterElement.style.display = "block";
            counterElement.textContent = `${messageValue.length}/10 caractères minimum`;
            counterElement.style.color = messageValue.length < 10 ? "#f44336" : "#4CAF50";
        } else {
            hideElement("message-counter");
        }
    }

    // Fonction pour valider le formulaire
    function validateReponseForm() {
        let isValid = true;
        const messageField = document.getElementById("reponse_message");
        const messageValue = messageField.value.trim();
        
        if (messageValue === "") {
            showError("message-error", "Le message est requis");
            messageField.classList.add("error-field");
            isValid = false;
        } else if (messageValue.length < 10) {
            showError("message-error", "Le message doit contenir au moins 10 caractères");
            messageField.classList.add("error-field");
            isValid = false;
        } else {
            hideError("message-error");
            messageField.classList.remove("error-field");
        }
        
        return isValid;
    }

    // Fonctions utilitaires
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.style.display = "block";
    }

    function hideError(elementId) {
        const errorElement = document.getElementById(elementId);
        errorElement.style.display = "none";
    }

    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        element.style.display = "none";
    }

    // Vérification périodique des nouvelles réclamations
    function checkForNewReclamations() {
        // Simulation d'une vérification de nouvelles réclamations
        // Dans un environnement réel, cela serait fait via une requête AJAX
        const lastCheckTime = localStorage.getItem('lastReclamationCheck') || 0;
        const currentTime = new Date().getTime();
        
        // Vérifier toutes les 30 secondes (30000 ms)
        if (currentTime - lastCheckTime > 30000) {
            // Mettre à jour le temps de dernière vérification
            localStorage.setItem('lastReclamationCheck', currentTime);
            
            // Vérifier s'il y a de nouvelles réclamations via AJAX
            // Pour la démonstration, nous utilisons une simulation
            const hasNewReclamations = <?php echo $unreadNotificationsCount > 0 ? 'true' : 'false'; ?>;
            
            if (hasNewReclamations) {
                showNewReclamationToast();
            }
        }
    }

    // Afficher le toast de notification
    function showNewReclamationToast() {
        const toast = document.getElementById('newReclamationToast');
        if (toast) {
            toast.style.display = 'block';
            
            // Masquer automatiquement après 10 secondes
            setTimeout(() => {
                toast.style.display = 'none';
            }, 10000);
        }
    }

    // Écouteur d'événements pour la validation en temps réel
    document.addEventListener("DOMContentLoaded", function() {
        const messageField = document.getElementById("reponse_message");
        
        if (messageField) {
            messageField.addEventListener("input", function() {
                validateMessageLength();
                if (this.value.trim().length >= 10) {
                    hideError("message-error");
                    this.classList.remove("error-field");
                }
            });
        }
        
        // Gestion des notifications
        const notificationBadge = document.getElementById("admin-notifications");
        const notificationDropdown = document.getElementById("admin-notification-dropdown");
        
        if (notificationBadge) {
            notificationBadge.addEventListener("click", function(e) {
                e.stopPropagation();
                notificationDropdown.style.display = notificationDropdown.style.display === "block" ? "none" : "block";
            });
            
            // Fermer le dropdown quand on clique ailleurs
            document.addEventListener("click", function(e) {
                if (notificationDropdown.style.display === "block" && !notificationDropdown.contains(e.target) && e.target !== notificationBadge) {
                    notificationDropdown.style.display = "none";
                }
            });
        }
        
        // Gestion de la notification utilisateur
        const userNotificationPopup = document.getElementById("userNotificationPopup");
        const closeUserNotification = document.getElementById("closeUserNotification");
        const dismissUserNotification = document.getElementById("dismissUserNotification");
        
        if (userNotificationPopup) {
            // Afficher la notification après un court délai
            setTimeout(function() {
                userNotificationPopup.style.display = "block";
            }, 500);
            
            // Fermer la notification
            if (closeUserNotification) {
                closeUserNotification.addEventListener("click", function() {
                    userNotificationPopup.style.display = "none";
                });
            }
            
            // Ignorer la notification
            if (dismissUserNotification) {
                dismissUserNotification.addEventListener("click", function() {
                    userNotificationPopup.style.display = "none";
                });
            }
        }
        
        // Gestion du toast de notification
        const newReclamationToast = document.getElementById("newReclamationToast");
        const closeToast = document.getElementById("closeToast");
        const dismissToast = document.getElementById("dismissToast");
        
        if (closeToast) {
            closeToast.addEventListener("click", function() {
                newReclamationToast.style.display = "none";
            });
        }
        
        if (dismissToast) {
            dismissToast.addEventListener("click", function() {
                newReclamationToast.style.display = "none";
            });
        }
        
        // Vérifier périodiquement les nouvelles réclamations
        setInterval(checkForNewReclamations, 10000); // Vérifier toutes les 10 secondes
        
        // Vérifier immédiatement au chargement de la page
        checkForNewReclamations();
        
        // Mettre en évidence les nouvelles réclamations
        const newReclamations = document.querySelectorAll(".new-reclamation");
        if (newReclamations.length > 0) {
            newReclamations.forEach(function(row) {
                // Animation subtile pour attirer l'attention
                setTimeout(function() {
                    row.style.backgroundColor = "";
                    setTimeout(function() {
                        row.style.backgroundColor = "rgba(231, 76, 60, 0.1)";
                    }, 500);
                }, 1000);
            });
        }
    });
    </script>
</body>
</html>