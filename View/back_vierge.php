
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office Noir</title>
    <link rel="stylesheet" href="back_vierge.css">
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
                <!-- Cartes de statistiques -->
                <div class="cards">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                        </div>
                    </div>

                    
                </div>

                <!-- Tableau -->
                <div class="tables">
                    <div class="recent-orders">
                        
                    </div>
                    <div class="chart-container">
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