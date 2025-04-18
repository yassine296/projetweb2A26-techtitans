<?php
include '../../controllers/ReclamationController.php';
require_once '../../models/Reclamation.php';

$reclamationC = new ReclamationController();

// Initialisation de la variable pour éviter les warnings
$reclamation_a_modifier = null;
$message = null;
$message_type = null;

// Récupérer toutes les réclamations
$list = $reclamationC->getAllReclamations();

// Gérer la modification (pré-remplir)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['charger_modif'])) {
    $reclamation_a_modifier = $reclamationC->getReclamationById($_POST['id']);
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
    $message = "Réclamation ajoutée avec succès.";
    $message_type = "success";
    
    // Rafraîchir la liste après l'ajout
    $list = $reclamationC->getAllReclamations();
}

// Gérer la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $reclamation = new Reclamation(
        $_POST['id'],
        $_POST['type_utilisateur'],
        $_POST['id_utilisateur'],
        $_POST['sujet'],
        $_POST['message'],
        new DateTime()
    );
    $reclamationC->updateReclamation($reclamation, $_POST['id']);
    $message = "Réclamation modifiée avec succès.";
    $message_type = "success";
    
    // Réinitialiser la réclamation à modifier
    $reclamation_a_modifier = null;
    
    // Rafraîchir la liste après la modification
    $list = $reclamationC->getAllReclamations();
}

// Gérer la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $reclamationC->deleteReclamation($_POST['id']);
    $message = "Réclamation supprimée avec succès.";
    $message_type = "success";
    
    // Rafraîchir la liste après la suppression
    $list = $reclamationC->getAllReclamations();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations - HEZNI Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --rouge-principal: #f44336;
            --rouge-fonce: #d32f2f;
            --rouge-clair: #ffcdd2;
            --noir-fonce: #212121;
            --noir-moyen: #424242;
            --gris-fonce: #757575;
            --gris-moyen: #9e9e9e;
            --gris-clair: #e0e0e0;
            --blanc: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: var(--noir-fonce);
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--noir-fonce);
            color: var(--blanc);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--noir-moyen);
            text-align: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--rouge-principal);
            margin-bottom: 10px;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--gris-clair);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-nav a:hover, .sidebar-nav li.active a {
            background-color: var(--noir-moyen);
            color: var(--blanc);
        }

        .sidebar-nav i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        /* Top Navigation */
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: var(--blanc);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header-title h1 {
            font-size: 20px;
            color: var(--noir-fonce);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background-color: var(--rouge-principal);
            color: var(--blanc);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }

        /* Content Styles */
        .content {
            padding: 20px 0;
        }

        .section {
            background-color: var(--blanc);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            color: var(--noir-fonce);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--rouge-principal);
        }

        /* Form Styles */
        .form-container {
            background-color: var(--blanc);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--gris-clair);
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--rouge-principal);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--rouge-principal);
            color: var(--blanc);
        }

        .btn-primary:hover {
            background-color: var(--rouge-fonce);
        }

        .btn-secondary {
            background-color: var(--gris-moyen);
            color: var(--blanc);
        }

        .btn-secondary:hover {
            background-color: var(--gris-fonce);
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--gris-clair);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--gris-clair);
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .action-btn {
            color: var(--noir-moyen);
            margin-right: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            color: var(--rouge-principal);
        }

        /* Status Styles */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .pending {
            background-color: #fff8e1;
            color: #f57f17;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 10px 0;
            }

            .sidebar-header {
                padding: 0 10px 10px;
            }

            .sidebar-header h2, .sidebar-nav span {
                display: none;
            }

            .sidebar-nav a {
                padding: 12px;
                justify-content: center;
            }

            .sidebar-nav i {
                margin-right: 0;
            }

            .main-content {
                margin-left: 70px;
            }
        }
    </style>
    <script>
        // Fonction pour mettre à jour les options du sujet en fonction du type d'utilisateur
        function updateSujetOptions() {
            var typeUtilisateur = document.querySelector("select[name='type_utilisateur']").value;
            var sujetSelect = document.querySelector("select[name='sujet']");
            var currentSujet = "<?php echo $reclamation_a_modifier ? $reclamation_a_modifier['sujet'] : ''; ?>";
            
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
                    "Problème de transport",
                    "Difficulté d'accès aux cours",
                    "Comportement inapproprié"
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

        // Initialiser les options de sujet au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateSujetOptions();
            
            // Ajouter un écouteur d'événement pour mettre à jour les options lorsque le type change
            document.querySelector("select[name='type_utilisateur']").addEventListener('change', updateSujetOptions);
        });
    </script>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">HEZNI</div>
                <h2>Administration</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                    <li><a href="#"><i class="fas fa-users"></i> <span>Utilisateurs</span></a></li>
                    <li><a href="#"><i class="fas fa-car"></i> <span>Trajets</span></a></li>
                    <li class="active"><a href="#"><i class="fas fa-exclamation-circle"></i> <span>Réclamations</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Statistiques</span></a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> <span>Paramètres</span></a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav">
                <div class="header-title">
                    <h1>GESTION DES RÉCLAMATIONS</h1>
                </div>
                <div class="user-info">
                    <div class="avatar">AD</div>
                    <span>Administrateur</span>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?>">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <!-- Form Section -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-<?php echo $reclamation_a_modifier ? 'edit' : 'plus-circle'; ?>"></i> 
                        <?php echo $reclamation_a_modifier ? "Modifier une Réclamation" : "Ajouter une Réclamation"; ?>
                    </h2>
                    
                    <form method="POST" class="form-container">
                        <?php if ($reclamation_a_modifier): ?>
                            <input type="hidden" name="id" value="<?php echo $reclamation_a_modifier['id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="type_utilisateur">Type Utilisateur :</label>
                            <select name="type_utilisateur" id="type_utilisateur" class="form-control" required>
                                <option value="">-- Sélectionnez un type --</option>
                                <option value="etudiant" <?php if (($reclamation_a_modifier['type_utilisateur'] ?? '') === 'etudiant') echo 'selected'; ?>>Etudiant</option>
                                <option value="conducteur" <?php if (($reclamation_a_modifier['type_utilisateur'] ?? '') === 'conducteur') echo 'selected'; ?>>Conducteur</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_utilisateur">ID Utilisateur :</label>
                            <input type="number" name="id_utilisateur" id="id_utilisateur" class="form-control" required value="<?php echo $reclamation_a_modifier['id_utilisateur'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="sujet">Sujet :</label>
                            <select name="sujet" id="sujet" class="form-control" required>
                                <!-- Les options seront remplies dynamiquement par JavaScript -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Message :</label>
                            <textarea name="message" id="message" class="form-control" required><?php echo $reclamation_a_modifier['message'] ?? ''; ?></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" name="<?php echo $reclamation_a_modifier ? 'modifier' : 'ajouter'; ?>" class="btn btn-primary">
                                <i class="fas fa-<?php echo $reclamation_a_modifier ? 'save' : 'plus'; ?>"></i>
                                <?php echo $reclamation_a_modifier ? 'Enregistrer les modifications' : 'Ajouter'; ?>
                            </button>
                            
                            <?php if ($reclamation_a_modifier): ?>
                                <button type="submit" name="annuler" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-list"></i> Liste des Réclamations</h2>
                    
                    <div class="data-table">
                        <?php if (!empty($list)): ?>
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
                                            <td><?php echo substr($reclamation['message'], 0, 50) . (strlen($reclamation['message']) > 50 ? '...' : ''); ?></td>
                                            <td><?php echo $reclamation['date_reclamation']; ?></td>
                                            <td>
                                                <!-- Modifier sur la même page -->
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">
                                                    <button type="submit" name="charger_modif" class="action-btn" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>
                                                
                                                <!-- Modifier sur une page séparée -->
                                                <a href="edit-reclamation.php?id=<?php echo $reclamation['id']; ?>" class="action-btn" title="Modifier (page séparée)">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                
                                                <!-- Supprimer -->
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">
                                                    <button type="submit" name="supprimer" class="action-btn" title="Supprimer" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Aucune réclamation trouvée.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
