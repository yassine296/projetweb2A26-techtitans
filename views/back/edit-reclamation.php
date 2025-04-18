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
        $message_type = "danger";
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
    
    // Rafraîchir les données
    $reclamation = $reclamationC->showReclamation($_POST['id']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réclamation - HEZNI Admin</title>
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
            text-decoration: none;
            display: inline-block;
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
            var currentSujet = "<?php echo $reclamation ? $reclamation['sujet'] : ''; ?>";
            
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
                    <li class="active"><a href="reclamations2.php"><i class="fas fa-exclamation-circle"></i> <span>Réclamations</span></a></li>
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
                    <h1>MODIFIER RÉCLAMATION</h1>
                </div>
                <div class="user-info">
                    <div class="avatar">AD</div>
                    <span>Administrateur</span>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <div class="section">
                    <h2 class="section-title"><i class="fas fa-edit"></i> Modifier la Réclamation</h2>
                    
                    <?php if ($reclamation): ?>
                        <form method="POST" class="form-container">
                            <input type="hidden" name="id" value="<?php echo $reclamation['id']; ?>">

                            <div class="form-group">
                                <label for="type_utilisateur">Type Utilisateur :</label>
                                <select name="type_utilisateur" id="type_utilisateur" class="form-control" required>
                                    <option value="etudiant" <?php if ($reclamation['type_utilisateur'] === 'etudiant') echo 'selected'; ?>>Etudiant</option>
                                    <option value="conducteur" <?php if ($reclamation['type_utilisateur'] === 'conducteur') echo 'selected'; ?>>Conducteur</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_utilisateur">ID Utilisateur :</label>
                                <input type="number" name="id_utilisateur" id="id_utilisateur" class="form-control" value="<?php echo $reclamation['id_utilisateur']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="sujet">Sujet :</label>
                                <select name="sujet" id="sujet" class="form-control" required>
                                    <!-- Les options seront remplies dynamiquement par JavaScript -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Message :</label>
                                <textarea name="message" id="message" class="form-control" required><?php echo $reclamation['message']; ?></textarea>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <button type="submit" name="modifier" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                                <a href="reclamations2.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Aucune réclamation trouvée avec cet identifiant.
                        </div>
                        <a href="reclamations2.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste des réclamations
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
