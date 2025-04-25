<!-- Page "Se connecter" -->
<?php 
require_once '../controller/userController.php';
require_once '../model/usermodel.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <title>Connexion - Hezni</title>
  <link rel="stylesheet" href="connect.css">
</head>
<body>
    <header>
    <div class="badge">TUNIS</div>
    </header>
    <!-- <header> -->
    <!-- <div class="badge">TUNIS</div>
    </header>
    <div class="divider"></div> -->
    <img src="lego.png" alt="Hezni" class="logo">
    <div class="divider"></div>
    <div class="form-container">
    <div class="form-title">
    <h2>Se Connecter</h2>
    <br></br>
    </div>
    <form action="../controller/userController.php?action=login" method="POST">
    <input type="hidden" name="type" value="register">
    <input type="email" name="email" placeholder="Adresse e-mail" required>
      <input type="password" name="mdp" placeholder="Mot de passe" required>
    <br><br>
    <button type="submit" name="submit">Se connecter</button>
    </form>
    <p> Vous n'avez pas de compte ? <a href="inscri.php">Créer un compte !</a>
    </p>
    </div>
    <footer>
    © www.hezni.tn | BY TECHTITANS.
  </footer>
</body>
</html>
