<?php
// Si le formulaire a été soumis, on appelle le contrôleur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../controller/userController.php');
    $controller = new UserController();
    $controller->inscrireUtilisateur();
}

// Récupérer tous les utilisateurs de la base de données
require_once 'C:/xampp/htdocs/Ines/controller/userController.php';
$controller = new UserController();
$utilisateurs = $controller->getUtilisateurs();


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
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: white;
    }

    .menu-toggle {
      position: absolute;
      top: 30px;
      right: 30px;
      width: 40px;
      height: 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      cursor: pointer;
      z-index: 100;
    }

    .menu-toggle span {
      display: block;
      width: 100%;
      height: 3px;
      background-color: white;
      border-radius: 3px;
      transition: all 0.3s ease;
      transform-origin: center;
    }

    /* Transformation en X */
    .menu-toggle.active span:nth-child(1) {
      transform: translateY(15px) rotate(45deg);
    }

    .menu-toggle.active span:nth-child(2) {
      opacity: 0;
    }

    .menu-toggle.active span:nth-child(3) {
      transform: translateY(-10px) rotate(-45deg);
    }

    .hero {
      background-color: #f44336;
      height: 450px;
      position: relative;
      z-index: 1;
    }

    .logo {
      position: absolute;
      top: 20px;
      left: 20px;
      width: 120px;
      height: auto;
      z-index: 3;
    }

    .logo:hover {
      transform: scale(1.05);
    }

    .hero-text {
      position: absolute;
      top: 59%;
      right: 150px;
      transform: translateY(-50%);
      font-weight: 700;
      font-size: 28px;
      color: #ffffff;
      text-align: right;
      z-index: 2;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
      max-width: 500px;
    }

    .hero-text span {
      color: #ffcdd2;
      font-weight: 600;
    }

    /* La vague */
    .wave-bottom {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 160px;
      overflow: hidden;
      line-height: 0;
      z-index: 2;
    }

    .wave-bottom svg {
      width: 100%;
      height: 100%;
      display: block;
    }

    .wave-bottom path {
      fill: white;
    }

    .form-container {
      position: absolute;
      top: 165px;
      left: 25%;
      transform: translateX(-50%);
      background-color: #ffffff;
      padding: 30px 25px;
      border-radius: 16px;
      width: 90%;
      max-width: 450px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 2;
      transition: all 0.3s ease;
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
      width: 100%;
      padding: 14px 16px;
      margin-bottom: 15px;
      border: 1px solid #e0e0e0 ;
      border-radius: 8px;
      background-color: #f5f5f5;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .form-container input:focus,
    .form-container select:focus {
      outline: none;
      border-color: #f44336;
      box-shadow: 0 0 0 2px #ffcdd2;
    }

    .form-container .row {
      display: flex;
      gap: 10px;
    }

    .form-container .row input {
      flex: 1;
    }

    .form-container button {
      background-color: #f44336;
      color: #ffffff;
      font-weight: 600;
      cursor: pointer;
      border: none;
      margin-top: 10px;
      transition: all 0.3s ease;
    }

    .form-container button:hover {
      background-color: #d32f2f;
      transform: translateY(-2px);
    }

    .content {
        background-color: white;
        padding: 100px 20px 120px;
        text-align: center;
        position: relative;
        z-index: 0;
        margin-top: 80px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1000px;
      margin: 0 auto 30px;
      width: 90%;
    }

    .section-title {
      font-size: 28px;
      color: #f44336;
      text-align: left;
    }
    </style>
</head>
<body>
<header class="hero">
    <img src="lego.png" alt="Hezni" class="logo">
    <div class="hero-text">
      HEZNI - Le premier site de <span>covoiturage étudiant</span> en Tunisie
    </div>
    <div class="wave-bottom">
      <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
      </svg>
    </div>
  </header>
        <div class="form-container">
        <h2 class="form-title">Inscription</h2>
        <form method="post" action="inscri.php">
            <label for="Nom">Nom :</label><br>
            <input type="text" id="Nom" name="Nom"><br><br>

            <label for="Prenom">Prénom :</label><br>
            <input type="text" id="Prenom" name="Prenom" ><br><br>

            <label for="Email">Email :</label><br>
            <input type="text" id="Email" name="Email" ><br><br>

            <label for="Mdp">Mot de passe :</label><br>
            <input type="password" id="Mdp" name="Mdp" ><br><br>

            <button type="submit">OK</button>
        </form>

<h2>Liste des utilisateurs</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Rôle</th>
    </tr>

    <?php foreach ($utilisateurs as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['IdU']) ?></td>
        <td><?= htmlspecialchars($u['Nom']) ?></td>
        <td><?= htmlspecialchars($u['Prenom']) ?></td>
        <td><?= htmlspecialchars($u['Email']) ?></td>
        <td><?= htmlspecialchars($u['Role']) ?></td>     
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
    </div>
    <script>
document.querySelector('form').addEventListener('submit', function(event) {
    let nom = document.getElementById('Nom').value.trim();
    let prenom = document.getElementById('Prenom').value.trim();
    let email = document.getElementById('Email').value.trim();
    let mdp = document.getElementById('Mdp').value;

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
