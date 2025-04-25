
<?php
require_once '../controller/userController.php';
require_once '../model/usermodel.php'; // assure-toi que le chemin est correct
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office Noir</title>
    <link rel="stylesheet" href="back.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

</head>
<body>
    <div class="dashboard">
        <!-- Menu latéral -->
        <div class="sidebar">
            <div class="logo-section">
            <img src="lego.png" alt="Logo Hezni">
            </div>
            <ul class="menu">
                <li class="active"><a href="#"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="#"> ......</a></li>
                <li><a href="#">.....</a></li>
                <li><a href="#"> ......</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Statistiques</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <header>
                <div class="header-left">
                    <i class="fas fa-bars" id="menu-toggle"></i>
                    <h3>Tableau de bord</h3>
                </div>
                <div class="header-right">
                    <div class="user">
                        <img src="https://via.placeholder.com/40" alt="User">
                        <span>Admin</span>
                    </div>
                </div>
            </header>
            <div class="content">
            <a href="inscriA.php" class="btn1">Ajouter admin</a>
                <!-- Cartes de statistiques -->
                <div class="cards">
                    <div class="card">
                        <div class="card-header">
                            <h4>Utilisateurs</h4>
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-body">
                            <h2>1,254</h2>
                            <span>+12% ce mois</span>
                        </div>
                    </div>

                    <!-- <div class="card">
                        <div class="card-header">
                            <h4>Commandes</h4>
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-body">
                            <h2>324</h2>
                            <span>+5% ce mois</span>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Revenus</h4>
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-body">
                            <h2>$8,542</h2>
                            <span>-2% ce mois</span>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Produits</h4>
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-body">
                            <h2>542</h2>
                            <span>+23% ce mois</span>
                        </div>
                    </div> -->
                </div>

                <!-- Tableau -->
                <div class="tables">
                    <div class="recent-orders">
                        <h3>Liste des utilisateurs</h3>

                        <?php
                    // Assure-toi que la variable $users est bien définie (elle vient du contrôleur)
                    if (empty($users)) {
                        echo "<p>Aucun utilisateur trouvé.</p>";
                    } else {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Mot de passe</th>
                                <th>Rôle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>                        
                                    <td><?php echo htmlspecialchars($user['idU']); ?></td>  <!-- Affichage de l'ID -->
                                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['mdp']); ?></td>
                                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                                    <!-- <td><button class="btn">Supprimer</button></td> -->
                                    <!-- <td><button class="btn">Supprimer</button></td> -->
                                    <td>
                                    <a href="?action=supprimer&idU=<?= $user['idU'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');"class="btn">Supprimer</a>
                                </td>
                                </tr>
                            <?php endforeach; ?>

                         <!-- Modifier -->
                          <!-- <tr>
                            <div id="modifier-form-container" style="<?= isset($userAModifier) ? 'display: block;' : 'display: none;' ?>">
                                <form action="../controller/userController.php" method="post" class="form-container">
                                    <h2 class="form-title">Modifier le trajet</h2>
            
                                        <input type="hidden" name="idU" value="<?= isset($userAModifier) ? htmlspecialchars($userAModifier['idU']) : '' ?>">
            
                                    <input type="text" name="nom" placeholder="Nom" 
                                        value="<?= isset($userAModifier) ? htmlspecialchars($userAModifier['nom']) : '' ?>" >
                                    
                                    <input type="text" name="prenom" placeholder="Prenom" 
                                        value="<?= isset($userAModifier) ? htmlspecialchars($userAModifier['prenom']) : '' ?>" >

                                    <input type="text" name="email" placeholder="Email" 
                                        value="<?= isset($userAModifier) ? htmlspecialchars($userAModifier['email']) : '' ?>" >
                                    <input type="password" name="mdp" placeholder="Mot de passe" 
                                        value="<?= isset($userAModifier) ? htmlspecialchars($userAModifier['mdp']) : '' ?>" >
            
                                    <input type="text" name="role" placeholder="Role" 
                                        value="<?= isset($userAModifier) ? htmlspecialchars($userAModifier['role']) : '' ?>" min="1" >

                                    <button type="submit" name="modifier" id="modify-trip-btn">
                                    Enregistrer les modifications
                                    </button>
                                    <button type="button" onclick="document.getElementById('modifier-form-container').style.display='none'">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                </form>
                            </div>
                        <a href="?action=modifier&idU=<?= $user['idU'] ?>" class="btn">Modifier</a> -->
                            <!-- </tr> -->
                            </tbody>
                    </table>
            <?php
                }
                ?>

                    </div>
                    <div class="chart-container">
                        <h3>Statistiques des ventes</h3>
                        <div class="chart-placeholder">
                            <!-- Ici serait normalement un graphique (Chart.js, etc.) -->
                            <div class="fake-chart">
                                <div class="bar" style="height: 30%;"></div>
                                <div class="bar" style="height: 60%;"></div>
                                <div class="bar" style="height: 45%;"></div>
                                <div class="bar" style="height: 80%;"></div>
                                <div class="bar" style="height: 25%;"></div>
                                <div class="bar" style="height: 65%;"></div>
                                <div class="bar" style="height: 40%;"></div>
                            </div>
                            <div class="chart-labels">
                                <span>Lun</span>
                                <span>Mar</span>
                                <span>Mer</span>
                                <span>Jeu</span>
                                <span>Ven</span>
                                <span>Sam</span>
                                <span>Dim</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script> // Toggle du menu latéral en version mobile
document.getElementById('menu-toggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
});

// Simulation de données pour les cartes
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Animation des barres du graphique
const bars = document.querySelectorAll('.bar');
bars.forEach(bar => {
    bar.style.height = '0';
    setTimeout(() => {
        bar.style.height = bar.getAttribute('style').split(';')[0].split(':')[1];
    }, 100);
});

// Gestion du hover sur les lignes du tableau
const tableRows = document.querySelectorAll('table tbody tr');
tableRows.forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.backgroundColor = '#222';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.backgroundColor = '';
    });
});</script>
</body>
</html>