<<<<<<< HEAD

<!DOCTYPE html>
=======
<!DOCTYPE html>
<?php
session_start(); // <-- Ajoutez cette ligne
// Initialisation des variables de notification
$driverId = 1; // ID temporaire du conducteur
$unreadCount = 0;

if (isset($_SESSION['driver_notifications'][$driverId])) {
    $unreadCount = count(array_filter(
        $_SESSION['driver_notifications'][$driverId],
        function($n) { return !$n['read']; }
    ));
}
?>
>>>>>>> bfbf316 (second commit)
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Réserver un trajet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
  <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Réservez des trajets économiques et écologiques entre villes universitaires.">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!--<link rel="stylesheet" href="style.css">-->
<<<<<<< HEAD
  <link rel="stylesheet" href="../view/style.css"> <!-- Adaptez le chemin selon votre structure -->
=======
  <link rel="stylesheet" href="../view/style.css"> 

  <style>
/* ====================== */
/* NOTIFICATIONS STYLES */
/* ====================== */

.notifications-wrapper {
  position: absolute;
  top: 30px;
  right: 80px; /* Ajustez selon l'espace nécessaire */
  z-index: 105;
}

.notifications-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  position: relative;
  padding: 8px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.notifications-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
  transform: scale(1.1);
  color: #ffcdd2;
}

.notification-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background-color: #ff0000;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
  border: 2px solid white;
}

.notifications-dropdown {
  display: none;
  position: fixed;
  top: 80px;
  right: 20px;
  width: 350px;
  max-height: 500px;
  overflow-y: auto;
  background: white;
  border-radius: 10px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  border: 1px solid #e0e0e0;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.notification-header {
  padding: 15px 20px;
  border-bottom: 1px solid #f0f0f0;
  background: #f44336;
  border-radius: 10px 10px 0 0;
  color: white;
}

.notification-header h4 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.unread-count {
  background: white;
  color: #f44336;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: bold;
}

.notification-list {
  padding: 0;
  margin: 0;
  list-style: none;
}

.notification-item {
  padding: 15px 20px;
  border-bottom: 1px solid #f5f5f5;
  display: flex;
  align-items: flex-start;
  cursor: pointer;
  transition: all 0.3s;
}

.notification-item:hover {
  background: #fff5f5;
}

.notification-item.unread {
  background: #fff0f0;
  border-left: 3px solid #f44336;
}

.notification-icon {
  margin-right: 12px;
  color: #f44336;
  font-size: 18px;
  margin-top: 3px;
}

.notification-content {
  flex: 1;
}

.notification-message {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 14px;
  line-height: 1.4;
}

.notification-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.notification-date {
  color: #888;
  font-size: 12px;
}

.new-badge {
  background: #f44336;
  color: white;
  font-size: 10px;
  padding: 2px 8px;
  border-radius: 10px;
  text-transform: uppercase;
}

.notification-empty {
  padding: 30px 20px;
  text-align: center;
  color: #888;
}

.notification-empty i {
  font-size: 24px;
  margin-bottom: 10px;
  display: block;
  color: #ddd;
}

.notification-empty p {
  margin: 0;
  font-size: 14px;
}

/* Animation pour nouvelles notifications */
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.notification-badge.pulse {
  animation: pulse 1s infinite;
}

/* Highlight animation */
@keyframes highlight {
  0% { background-color: rgba(244, 67, 54, 0.1); }
  50% { background-color: rgba(244, 67, 54, 0.3); }
  100% { background-color: rgba(244, 67, 54, 0.1); }
}

.highlight {
  animation: highlight 1s ease;
}

/* Scrollbar styling */
.notifications-dropdown::-webkit-scrollbar {
  width: 6px;
}

.notifications-dropdown::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.notifications-dropdown::-webkit-scrollbar-thumb {
  background: #f44336;
  border-radius: 10px;
}

.notifications-dropdown::-webkit-scrollbar-thumb:hover {
  background: #d32f2f;
}

/* Responsive */
@media (max-width: 768px) {
  .notifications-wrapper {
    right: 60px;
  }
  
  .notifications-dropdown {
    width: 300px;
    right: 10px;
  }
}

@media (max-width: 480px) {
  .notifications-wrapper {
    right: 50px;
  }
  
  .notifications-dropdown {
    width: 280px;
  }
}
  </style>
>>>>>>> bfbf316 (second commit)

</head>
<body>

  <div class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

<<<<<<< HEAD
=======
  <div class="notifications-wrapper">
    <button class="notifications-btn">
        <i class="fas fa-bell"></i>
        <?php if ($unreadCount > 0) : ?>
            <span class="notification-badge"><?= $unreadCount ?></span>
        <?php endif; ?>
    </button>
    
    <div class="notifications-dropdown">
        <div class="notification-header">
            <h4>Notifications <?php if ($unreadCount > 0) : ?><span class="unread-count"><?= $unreadCount ?> non lues</span><?php endif; ?></h4>
        </div>
        
        <?php if (!empty($_SESSION['driver_notifications'][$driverId])) : ?>
            <div class="notification-list">
                <?php foreach ($_SESSION['driver_notifications'][$driverId] as $index => $notification) : ?>
                    <div class="notification-item <?= $notification['read'] ? 'read' : 'unread' ?>" 
                        data-index="<?= $index ?>">
                        <div class="notification-icon">
                            <i class="fas fa-suitcase"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                            <div class="notification-meta">
                                <span class="notification-date"><?= htmlspecialchars($notification['date']) ?></span>
                                <?php if (!$notification['read']) : ?>
                                    <span class="new-badge">Nouveau</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="notification-empty">
                <i class="far fa-bell-slash"></i>
                <p>Aucune notification</p>
            </div>
        <?php endif; ?>
    </div>
</div>


>>>>>>> bfbf316 (second commit)
  <header class="hero">
    <img src="../view/logo blanc.png" alt="Hezni" class="logo">
    <!--<div class="hero-text">
      HEZNI - Le premier site de <span>covoiturage étudiant</span> en Tunisie
    </div>-->
<<<<<<< HEAD
=======

    



>>>>>>> bfbf316 (second commit)
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

  
<<<<<<< HEAD
  <input type="text" name="ville_depart" placeholder="Ville de départ" id="departure" >
  
  <input type="text" name="ville_arrive" placeholder="Ville d'arrivée" id="destination" >


  <div class="row">
    <input type="date" name="date" placeholder="Date d'aller" id="date-depart" >
    <input type="time" name="heure" placeholder="Heure de départ" id="time-depart" >
  </div>
  

  <input type="number" name="nb_place" placeholder="Nombre de places disponibles" id="places" min="1" >
  
  <input type="number" name="prix" placeholder="prix d'une place" id="prix" >
=======
  <input type="text" name="ville_depart" placeholder="Ville de départ" id="departure" required>
  
  <input type="text" name="ville_arrive" placeholder="Ville d'arrivée" id="destination" required>


  <div class="row">
    <input type="date" name="date" placeholder="Date d'aller" id="date-depart" required>
    <input type="time" name="heure" placeholder="Heure de départ" id="time-depart" required>
  </div>
  

  <input type="number" name="nb_place" placeholder="Nombre de places disponibles" id="places" min="1" required>
  
  <input type="number" name="prix" placeholder="prix d'une place" id="prix" required>
>>>>>>> bfbf316 (second commit)
  
  <textarea name="commentaire" id="commentaire" placeholder="Commentaire (ex : bagages légers uniquement)" rows="3"></textarea>

  
  <button type="submit" name="ajouter" id="add-trip-btn">
    <i class="fas fa-plus"></i> Publier le trajet
  </button>
</form>
<!--fin formulaire -->
<<<<<<< HEAD
=======

>>>>>>> bfbf316 (second commit)
<!--formulaire de modification-->
<!-- Formulaire de modification (caché par défaut) -->
<div id="modifier-form-container" style="<?= isset($annonceAModifier) ? 'display: block;' : 'display: none;' ?>">
    <form action="../controller/BagageController.php" method="post" class="form-container">
        <h2 class="form-title">Modifier le trajet</h2>
        
        <input type="hidden" name="id" value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['id']) : '' ?>">
        
        <input type="text" name="ville_depart" placeholder="Ville de départ" 
<<<<<<< HEAD
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['ville_depart']) : '' ?>" >
        
        <input type="text" name="ville_arrive" placeholder="Ville d'arrivée" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['ville_arrive']) : '' ?>" >

        <div class="row">
            <input type="date" name="date" placeholder="Date d'aller" 
                   value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['date']) : '' ?>" >
            <input type="time" name="heure" placeholder="Heure de départ" 
                   value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['heure']) : '' ?>" >
        </div>
        
        <input type="number" name="nb_place" placeholder="Nombre de places disponibles" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['nb_place']) : '' ?>" min="1" >
        
        <input type="number" name="prix" placeholder="prix d'une place" 
               value="<?= isset($annonceAModifier) ? htmlspecialchars($annonceAModifier['prix']) : '' ?>" >
=======
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
>>>>>>> bfbf316 (second commit)
        
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

<<<<<<< HEAD
    <!--

  <div class="card-container">
      <div class="card">
        
        <div class="card-content">
          <div class="ride-info">
            <div class="ride-info-item">
              <i class="fas fa-map-marker-alt"></i>
              <span>Tunis → Sfax</span>
            </div>
            <div class="ride-info-item">
              <i class="fas fa-calendar-alt"></i>
              <span>15 Juin 2023</span>
            </div>
            <div class="ride-info-item">
              <i class="fas fa-clock"></i>
              <span>08:30</span>
            </div>
            <div class="ride-info-item">
              <i class="fas fa-chair"></i>
              <span>3 places</span>
            </div>
          </div>
          <div class="price">15 DT</div>
          
        </div>
        
        <button class="Supprimer"><i class="fas fa-car"></i> Supprimer</button>
        <button class="modifier"><i class="fas fa-car"></i> Modifier</button>
      </div>

       
    </div>-->
=======
   
>>>>>>> bfbf316 (second commit)
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



<<<<<<< HEAD
=======

// Gestion des notifications
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'ouverture/fermeture du dropdown
    document.querySelector('.notifications-btn').addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.querySelector('.notifications-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Fermer le dropdown quand on clique ailleurs
    document.addEventListener('click', function() {
        document.querySelector('.notifications-dropdown').style.display = 'none';
    });

    // Empêcher la fermeture quand on clique dans le dropdown
    document.querySelector('.notifications-dropdown').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Marquer une notification comme lue quand on clique dessus
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            if (!this.classList.contains('read')) {
                // Envoyer une requête AJAX pour marquer comme lue
                fetch('markNotificationRead.php?index=' + index)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.add('read');
                            const dot = this.querySelector('.notification-dot');
                            if (dot) dot.remove();
                            
                            // Mettre à jour le badge
                            const badge = document.querySelector('.notification-badge');
                            if (badge) {
                                const count = parseInt(badge.textContent) - 1;
                                if (count > 0) {
                                    badge.textContent = count;
                                } else {
                                    badge.remove();
                                }
                            }
                        }
                    });
            }
        });
    });

    // Animation pour nouvelles notifications
    function showNewNotification() {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.classList.add('pulse');
            setTimeout(() => badge.classList.remove('pulse'), 1000);
        }
    }
});


>>>>>>> bfbf316 (second commit)
  </script>
</body>
</html>