<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Réserver un trajet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
  <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Réservez des trajets économiques et écologiques entre villes universitaires.">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!--<link rel="stylesheet" href="style.css">-->
  <link rel="stylesheet" href="../view/style.css"> 

</head>
<body>

  <div class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <header class="hero">
    <img src="../view/logo blanc.png" alt="Hezni" class="logo">
    <!--<div class="hero-text">
      HEZNI - Le premier site de <span>covoiturage étudiant</span> en Tunisie
    </div>-->
    <div class="voiture">
      <img src="../view/image.png" alt="voiture" class="voiture">
    </div>
    <div class="wave-bottom">
      <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
      </svg>
    </div>
  </header>

  
<!--formulaire-->
<form action="../controller/BagageController.php" method="post" class="form-container">
  <h2 class="form-title">Ajoutez votre trajet</h2>

  
  <input type="text" name="ville_depart" placeholder="Ville de départ" id="departure" required>
  
  <input type="text" name="ville_arrive" placeholder="Ville d'arrivée" id="destination" required>


  <div class="row">
    <input type="date" name="date" placeholder="Date d'aller" id="date-depart" required>
    <input type="time" name="heure" placeholder="Heure de départ" id="time-depart" required>
  </div>
  

  <input type="number" name="nb_place" placeholder="Nombre de places disponibles" id="places" min="1" required>
  
  <input type="number" name="prix" placeholder="prix d'une place" id="prix" required>
  
  <textarea name="commentaire" id="commentaire" placeholder="Commentaire (ex : bagages légers uniquement)" rows="3"></textarea>

  
  <button type="submit" name="ajouter" id="add-trip-btn">
    <i class="fas fa-plus"></i> Publier le trajet
  </button>
</form>
<!--fin formulaire -->

<!--formulaire de modification-->
<!-- Formulaire de modification (caché par défaut) -->
<div id="modifier-form-container" style="<?= isset($annonceAModifier) ? 'display: block;' : 'display: none;' ?>">
    <form action="../controller/BagageController.php" method="post" class="form-container">
        <h2 class="form-title">Modifier le trajet</h2>
        
        <input type="hidden" name="id" value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['id']) : '' ?>">
        
        <input type="text" name="ville_depart" placeholder="Ville de départ" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['ville_depart']) : '' ?>" required>
        
        <input type="text" name="ville_arrive" placeholder="Ville d'arrivée" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['ville_arrive']) : '' ?>" required>

        <div class="row">
            <input type="date" name="date" placeholder="Date d'aller" 
                   value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['date']) : '' ?>" required>
            <input type="time" name="heure" placeholder="Heure de départ" 
                   value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['heure']) : '' ?>" required>
        </div>
        
        <input type="number" name="nb_place" placeholder="Nombre de places disponibles" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['nb_place']) : '' ?>" min="1" required>
        
        <input type="number" name="prix" placeholder="prix d'une place" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['prix']) : '' ?>" required>
        
        <textarea name="commentaire" placeholder="Commentaire" rows="3"><?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['commentaire']) : '' ?></textarea>

        <button type="submit" name="modifier" id="modify-trip-btn">
            <i class="fas fa-save"></i> Enregistrer les modifications
        </button>
        <button type="button" onclick="document.getElementById('modifier-form-container').style.display='none'">
            <i class="fas fa-times"></i> Annuler
        </button>
    </form>
</div>
<!--fin formulaire modifier-->


  <div class="content">
    <div class="section-header">
      <h1 class="section-title">Vos trajets</h1>
      <div class="results-count">12 résultats trouvés</div>
      
    </div>

   
    <div class="card-container">
    <?php
    // Plus besoin de require le modèle ici, les données doivent déjà être passées via le contrôleur
   
    if (empty($annonces)) {
        echo "<p>Aucune annonce trouvée.</p>";
    } else {

        foreach ($annonces as $annonce) {
            ?>
            <div class="card">
                <div class="card-content">
                    <div class="ride-info">
                        <div class="ride-info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($annonce['ville_depart']) ?> → <?= htmlspecialchars($annonce['ville_arrive']) ?></span>
                        </div>
                        <div class="ride-info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?= date('d M Y', strtotime($annonce['date'])) ?></span>
                        </div>
                        <div class="ride-info-item">
                            <i class="fas fa-clock"></i>
                            <span><?= htmlspecialchars($annonce['heure']) ?></span>
                        </div>
                        <div class="ride-info-item">
                            <i class="fas fa-chair"></i>
                            <span><?= htmlspecialchars($annonce['nb_place']) ?> places</span>
                        </div>
                    </div>
                    <div class="price"><?= htmlspecialchars($annonce['prix']) ?> DT</div>
                </div>

                <button class="Supprimer" 
                        onclick="if(confirm('Êtes-vous sûr ?')) window.location.href='BagageController.php?action=supprimer&id=<?= $annonce['id'] ?>'">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
               <!-- <a href="BagageController.php?action=supprimer&id=<?= $annonce['id'] ?>" class="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">
                    <i class="fas fa-trash"></i> Supprimer
                </a>-->
                <!--<button class="Supprimer"><i class="fas fa-car"></i> Supprimer</button>-->
                <!--<button class="modifier"><i class="fas fa-car"></i> Modifier</button>-->
                <button class="modifier" onclick="window.location.href='BagageController.php?action=modifier&id=<?= $annonce['id'] ?>'">
                    <i class="fas fa-edit"></i> Modifier
                </button>
            </div>
            <?php
        }
    }
    ?>
</div>








    
    <button class="filtrer" id="filter-btn">
      <i class="fas fa-sliders-h"></i> Filtrer
    </button>
  </div>

  <footer>
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

  <script>
    // Animation du menu toggle en X
    const menuToggle = document.getElementById('menuToggle');
    menuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
    });



    document.querySelector("form").addEventListener("submit", function(e) {
    let valid = true;
    const departure = document.getElementById("departure");
    const destination = document.getElementById("destination");
    const dateDepart = document.getElementById("date-depart");
    const timeDepart = document.getElementById("time-depart");
    const places = document.getElementById("places");
    const prix = document.getElementById("prix");
    const commentaire = document.getElementById("commentaire");

    // Réinitialiser les styles (optionnel)
    [departure, destination, dateDepart, timeDepart, places, prix].forEach(input => {
      input.style.border = "";
    });

    // Vérif champs vides (sauf commentaire)
    if (
      !departure.value.trim() || 
      !destination.value.trim() ||
      !dateDepart.value || 
      !timeDepart.value ||
      !places.value || 
      !prix.value
    ) {
      alert("Tous les champs sont obligatoires sauf le commentaire.");
      valid = false;
    }

    // Vérif villes = chaîne de caractères sans chiffres
    const onlyLetters = /^[a-zA-ZÀ-ÿ\s\-]+$/;
    if (!onlyLetters.test(departure.value)) {
      alert("La ville de départ doit contenir uniquement des lettres.");
      departure.style.border = "2px solid red";
      valid = false;
    }

    if (!onlyLetters.test(destination.value)) {
      alert("La ville d'arrivée doit contenir uniquement des lettres.");
      destination.style.border = "2px solid red";
      valid = false;
    }

    // Vérif date et heure : date future
    const selectedDateTime = new Date(`${dateDepart.value}T${timeDepart.value}`);
    const now = new Date();

    if (selectedDateTime <= now) {
      alert("La date et l'heure doivent être dans le futur.");
      dateDepart.style.border = "2px solid red";
      timeDepart.style.border = "2px solid red";
      valid = false;
    }

    // Vérif nombre de places = entier > 0
    if (!Number.isInteger(Number(places.value)) || Number(places.value) < 1) {
      alert("Le nombre de places doit être un entier positif.");
      places.style.border = "2px solid red";
      valid = false;
    }

    // Vérif prix = float positif
    if (isNaN(prix.value) || Number(prix.value) <= 0) {
      alert("Le prix doit être un nombre positif.");
      prix.style.border = "2px solid red";
      valid = false;
    }

    // Vérif commentaire (si rempli) = texte
    if (commentaire.value && !onlyLetters.test(commentaire.value.trim())) {
      alert("Le commentaire ne doit contenir que des lettres (ou être vide).");
      commentaire.style.border = "2px solid orange";
      valid = false;
    }

    if (!valid) {
      e.preventDefault(); // Empêche l'envoi si erreurs
    }
  });



  </script>
</body>
</html>