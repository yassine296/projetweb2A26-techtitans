
<?php
require_once '../controller/userController.php';
require_once '../model/usermodel.php'; // assure-toi que le chemin est correct


// Récupérer tous les utilisateurs de la base de données
// require_once 'C:/xampp/htdocs/Ines/controller/userController.php';
// $controller = new UserController();
// $utilisateurs = $controller->getUtilisateurs();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
  <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Réservez des trajets économiques et écologiques entre villes universitaires.">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* style.css */
body {
  margin: 0;
  font-family: Poppins, sans-serif;
  background-color: #f44336;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
.logo {
  position: absolute;
  margin-right: 10%;
  top: 185px;
  left: 790px;
  width: 350px;
  height: auto;
  z-index: 3;
}
  /* filter: drop-shadow(5px 5px 10px rgba(0, 0, 0, 0.5));
  animation: fadeInSlide 1.5s ease-out forwards;
}
@keyframes fadeInSlide {
  0% {
      opacity: 0;
      transform: translateY(-30px) scale(0.95);
  }
  100% {
      opacity: 1;
      transform: translateY(0) scale(1);
  }
  } */
.logo:hover {
  transform: scale(1.05);
}

.title {
  font-size: 50px;
  font-weight: 600;
  margin-bottom: 20px;
  text-align: center;
  color: #ff4d4d;
}

.form-title {
  color: #ff4d4d;
  text-align: center; /* centre le contenu dans le conteneur */

}
  
.form-title h2{
  position: absolute;
  top: 5%;
  right: 160px;
  transform: translateY(-50%);
  font-weight: 700;
  font-size: 28px;
  /* color: #ff4d4d; */
  /* text-align: right; */
  z-index: 2;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
  max-width: 500px;
}

.form-container {
  text-align: center;
  position: absolute;
  top: 100px;     /* diminue pour le remonter */
  right: 50%;             
  left: auto;            
  transform: none;        
  background-color: #ffffff;
  /* padding: 30px 25px; */
  padding-left: 10px;
  padding-right: 10px;
  border-radius: 16px;
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
  z-index: 2;
  transition: all 0.3s ease;
  width: 95%;           /* élargit le container */
  max-width: 550px; 
  height: 500px;

}

.form-container:hover {
  box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.form-title {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #ff4d4d;
  text-align: center;
}

.form-container input,
.form-container button,
.form-container select {
  width: 90%;
  padding: 14px 16px;
  margin-bottom: 15px;
  border: 1px solid #e0e0e0 ;
  border-radius: 8px;
  background-color: #f5f5f5;
  font-size: 16px;
  transition: all 0.3s ease;
  text-align: left;
}
.form-container button {
  background-color: #f44336;
  color: #f44336;
  font-weight: 600;
  cursor: pointer;
  border: none;
  margin-top: 10px;
  transition: all 0.3s ease;
  text-align: center;

}

.form-container button:hover {
  /* background-color: #d32f2f;
  transform: translateY(-2px); */
  transform: scale(1.1); /* Augmente la taille du bouton */
  background-color: #ff2d2d; /* Changement de couleur au survol */
}
p {
  margin-top: 15px;
}

.divider {
  width: 3px;
  background-color: white;
  height: 150px;
  margin: 90px auto; /* Marge automatique pour centrer horizontalement */
  margin-top: 20%;
  margin-left: 55%;
}
.badge {
  background-color: white;
  color: #ff2d2d;
  padding: 5px 15px;
  border-radius: 20px;
  font-weight: bold;
  font-size: 14px;
}
header {
  position: absolute;          /* Positionne le header en fonction du coin supérieur gauche */
  top: 20px;                   /* Distance du haut */
  left: 20px;                  /* Distance de la gauche */
  display: flex;
  justify-content: flex-start; /* Aligne les éléments à gauche */
  align-items: flex-start;     /* Aligne les éléments en haut */
}

nav a {
  color: white;
  text-decoration: none;
  margin-left: 30px;
  font-weight: bold;
  font-size: 14px;
}
.text-section {
  max-width: 60%;
  margin-left: -20px; /* Décale le texte un peu à gauche */
}
.text-section h2 {
  font-size: 40px;
  color: #fff;
  font-weight: bold;
  text-align: center;
  margin-top: 120px; /* Pour baisser un peu le texte */
  margin-left: 20px; 
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
  max-width: 500px;
  display: flex;

}
.text-section span {
  color: #ffcdd2;
  font-weight: 600;
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
}
.form-container .row {
      display: flex;
      gap: 10px;
    }

    .form-container .row input {
      flex: 1;
    }
</style>
</head>
<body>
<header>
    <img src="lego.png" alt="Hezni" class="logo">
    <div class="badge">TUNIS</div>
  </header>
  <div class="divider"></div>
    <div class="form-container">
        <h2 class="form-title">Inscription</h2>
        
        <form method="post" action="inscri.php">
        <div class="row">
            <label for="nom"></label><br>
            <input type="text" name="nom" placeholder="Nom" id="nom" required>

            <label for="prenom"></label><br>
            <input type="text" name="prenom" placeholder="Prenom" id="prenom" required>
        </div>

            <label for="email"></label><br>
            <input type="text" name="email" placeholder="Email" id="email" required>

            <label for="mdp"></label><br>
            <input type="password" name="mdp" placeholder="Mot de passe " id="mdp" required>

            <label for="role"></label><br>
                <select id="role" name="role"  placeholder="Role ">
                    <option value="etudiant">Etudiant</option>
                    <option value="admin">Admin</option>
                    <option value="conducteur">Conducteur</option>

                </select><br><br>
            
            <!-- <button type="submit" name="ajouter"> OK </button> -->
            <a href="inscriA.php">
            <button type="submit" name="ajouter">Aller à l'interface</button>
            </a>

        </form> 
        
        <p style="text-align: center;">Déjà inscrit ? <a href="connect.php">Se connecter</a></p>    
    </div>
    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
    let nom = document.getElementById('nom').value.trim();
    let prenom = document.getElementById('prenom').value.trim();
    let email = document.getElementById('email').value.trim();
    let mdp = document.getElementById('mdp').value;

    let erreurs = [];

    // Vérifie nom
    if (!/^[A-Za-z]{2,}$/.test(nom)) {
        erreurs.push("Le nom doit contenir au moins 2 lettres sans chiffres.");
    }

    // Vérifie prénom
    if (!/^[A-Za-z]{2,}$/.test(prenom)) {
        erreurs.push("Le prénom doit contenir au moins 2 lettres sans chiffres.");
    }

    // Vérifie email
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        erreurs.push("Adresse e-mail invalide.");
    }

    // Vérifie mot de passe
    if (!/(?=.*\d).{6,}/.test(mdp)) {
        erreurs.push("Le mot de passe doit contenir au moins 6 caractères et un chiffre.");
    }

    // Si des erreurs, empêche l'envoi et affiche les messages
    if (erreurs.length > 0) {
        event.preventDefault(); // empêche l'envoi du formulaire
        alert(erreurs.join("\n")); // affiche les erreurs dans une popup
    }
});
</script>

</body>
</html>
