<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page avec Menu Profil</title>
    <style>
        /* Style global de la page */
        body {
            margin: 0;
            font-family: Poppins, sans-serif;
            background-color: #f44336;
            color: white;
        }

        /* En-tête */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
        }

        .badge {
            background-color: white;
            color: #ff2d2d;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 30px;
            font-weight: bold;
            font-size: 14px;
        }

        /* Style du menu profil */
        .profile-menu {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: white;
            font-weight: bold;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid white;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #ff2d2d;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f8f8f8;
        }

        .profile-menu:hover .dropdown-content {
            display: block;
        }

        .dropdown-header {
            padding: 12px 16px;
            background-color: #ff2d2d;
            color: white;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 0;
        }

        /* Le reste de votre CSS existant... */
        /* Corps principal */
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 60px 40px;
        }

        .text-section {
            max-width: 60%;
            margin-left: -20px; /* Décale le texte un peu à gauche */
        }

        .text-section h1 {
            font-size: 28px;
            background-color: white;
            color: #ff2d2d;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .text-section h2 {
            font-size: 40px;
            font-weight: bold;
            text-align: center;
            margin-top: 120px; /* Pour baisser un peu le texte */
            margin-left: 20px; 
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            max-width: 500px;
        }
        .text-section p {
            font-size: 18px;
        line-height: 1.6;
        color: #333;
        font-weight: normal;
        text-align: left;
        margin-bottom: 20px;
        }

        .divider {
            width: 2.5px;
            background-color: white;
            height: 150px;
            margin: 90px 30px 0 30px;
        }

        .logo-section {
            text-align: center;
        }

        .logo-section img {
            width: 350px;
            height: auto;
            margin-left: 40px; /* Décale l'image un peu à gauche */
            margin-top: 55px;   /* Décale un peu vers le bas (si tu veux) */
            
        }
        .logo-apropos {
            text-align: center;
        }

        .logo-apropos img {
            width: 250px;
            height: auto;
            margin-left: 40px; /* Décale l'image un peu à gauche */
            margin-top: 55px;   /* Décale un peu vers le bas (si tu veux) */
            border-radius: 50%; /* cercle parfait */

            
        }
        footer {
            display: flex;
            justify-content: center;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 12px;
            color: white;
            padding: 10px 0;
            background-color: #f44336;
        }
        .anim {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #e53935;
            animation: blink 1s step-end infinite;
            color: #e53935;
        }
        @keyframes blink {
            0%, 100% { border-color: transparent; }
            50% { border-color: #e53935; }
        }
        .text-section h2 span {
        display: inline-block;
        position: relative;
        color: white;
        }


        .changing-word {
        opacity: 0;
        transition: opacity 1s ease;
        }

        .changing-word.fade-in {
        opacity: 1;
        }

        /* Section A Propos */
        .section-apropos {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f44336;
        padding: 50px 40px;
        margin: 20px 0;
        height: 300px;
        /* border-radius: 15px; */
        }

        .content-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1200px;
        }

        .logo-container img {
        width: 250px; /* Ajuste la taille de ton logo */
        height: auto;
        margin-right: 40px;
        }
        .text-content {
        max-width: 60%;
        margin-left: -20px;
        color: black;
        }
        .text-apropos {
        max-width: 60%;
        margin-left: -20px;
        color: #fff;
        }

        .text-apropos h2 {
        font-size: 40px;
        font-weight: bold;
        text-align: center;
        margin-top: 120px; /* Pour baisser un peu le texte */
        margin-left: 20px; 
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        max-width: 500px;
        }
        html {
        scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <header>
        <div class="badge">Nouveau</div>
        <nav>
            <a href="#">Accueil</a>
            <a href="#">Services</a>
            <a href="#">À propos</a>
            <a href="#">Contact</a>
        </nav>
        
        <!-- Menu Profil -->
        <div class="profile-menu">
            <button class="profile-btn">
                <img src="https://via.placeholder.com/40" alt="Photo de profil" class="profile-img">
                <span>Mon Profil</span>
            </button>
            <div class="dropdown-content">
                <div class="dropdown-header">Mon Compte</div>
                <a href="#"><i class="fas fa-user"></i> Profil</a>
                <a href="#"><i class="fas fa-cog"></i> Paramètres</a>
                <a href="#"><i class="fas fa-envelope"></i> Messages</a>
                <div class="dropdown-divider"></div>
                <a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>

    <!-- Le reste de votre contenu... -->
    <div class="container">
        <div class="text-section">
            <h1>Bonjour !</h1>
            <h2><span>Bienvenue sur notre site</span></h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris.</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="logo-section">
            <img src="https://via.placeholder.com/350" alt="Logo">
        </div>
    </div>

    <footer>
        <p>© 2023 Mon Site Web. Tous droits réservés.</p>
    </footer>

    <!-- Pour les icônes Font Awesome (optionnel) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <script>
        // Animation pour le menu (optionnel)
        document.querySelector('.profile-btn').addEventListener('click', function() {
            const dropdown = document.querySelector('.dropdown-content');
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
            }
        });

        // Fermer le menu quand on clique ailleurs
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.profile-btn') && !e.target.closest('.profile-menu')) {
                const dropdowns = document.querySelectorAll('.dropdown-content');
                dropdowns.forEach(function(dropdown) {
                    if (dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>