<?php
session_start(); // Ajoutez cette ligne tout en haut
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Réserver un trajet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
  <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Réservez des trajets économiques et écologiques entre villes universitaires.">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../view/style.css">
</head>
<body>

  <div class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <header class="hero">
    <img src="../view/logo blanc.png " alt="Hezni" class="logo">
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
  <div class="form-container">
    <h2 class="form-title">Trouvez une place pour vos bagages</h2>
    <input type="text" placeholder="Ville de départ" id="departure">
    <input type="text" placeholder="Ville d'arrivée" id="destination">
    <input type="text" placeholder="nombre de bagages" id="bagages">
    <div class="row">
      <input type="date" placeholder="Date d'aller" id="date-depart">
      <input type="date" placeholder="Date de retour" id="date-return">
    </div>
    <select id="Type de bagages">
      <option value="1">sac à dos</option>
      <option value="2">lunch box</option>
      <option value="3">petite valise</option>
      <option value="4">grande valise</option>
      <option value="4">valise moyenne</option>
    </select>
    <button id="search-btn"><i class="fas fa-search"></i> Rechercher</button>
  </div>
<!--fin formulaire-->

  <div class="content">
    <div class="section-header">
      <h1 class="section-title">Trajets disponibles</h1>
      <div class="results-count">12 résultats trouvés</div>
    </div>

   
    <div class="filters">
      <div class="filter-tag active">Tous</div>
      <div class="filter-tag">Aujourd'hui</div>
      <div class="filter-tag">Demain</div>
      <div class="filter-tag">Cette semaine</div>
      <div class="filter-tag">Moins de 10 DT</div>
      <a href="../view/historiqueReservations.php" class="filter-tag history-tag">
          <i class="fas fa-history"></i> Mon historique
      </a>
    </div>

  




<!-- Dans la partie des cartes -->
<div class="card-container">
  <?php foreach ($annonces as $annonce): ?>
  <div class="card">
  <img src="../view/zaineb.jpg" alt="Mouna Youssef" class="driver-avatar">
      <div class="card-content">
       <h3 class="driver-name">Zaineb Ben Regaya </h3>
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
                  <i class="fas fa-suitcase"></i>
                  <span><?= htmlspecialchars($annonce['nb_place']) ?> places</span>
              </div>
          </div>
          <div class="price"><?= htmlspecialchars($annonce['prix']) ?> DT</div>
      </div>
      
      <button type="button" class="book-btn" data-annonce-id="<?= $annonce['id'] ?>">
    <i class="fas fa-suitcase"></i> Réserver
</button>
     
  </div>
  <?php endforeach; ?>
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

<!-- Popup de réservation -->
        <div class="popup-overlay" id="reservationPopup">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Réserver ce trajet</h3>
                    <button class="close-popup">&times;</button>
                </div>
                <div class="popup-body" id="popupBody">
                    <!-- Le contenu sera injecté ici par JavaScript -->
                </div>
            </div>
        </div>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation du menu toggle en X
        const menuToggle = document.getElementById('menuToggle');
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
        });

        // Gestion du popup de réservation
        const popup = document.getElementById('reservationPopup');
        const popupBody = document.getElementById('popupBody');
        const closePopup = document.querySelector('.close-popup');

        document.querySelectorAll('.book-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const annonceId = this.getAttribute('data-annonce-id');
                const card = this.closest('.card');
                const maxPlaces = card.querySelector('.ride-info-item:nth-child(4) span').textContent.match(/\d+/)[0];
                const unitPrice = parseFloat(card.querySelector('.price').textContent.match(/[\d.]+/)[0]);

                // Injecter le formulaire avec calcul de prix
                popupBody.innerHTML = `
                    <div class="price-summary">
                        <span>Prix unitaire:</span>
                        <span>${unitPrice} DT</span>
                        <span>Prix total:</span>
                        <span id="totalPrice">${unitPrice} DT</span>
                    </div>
                    <form method="post" action="../controller/reserverController.php" id="reservationForm">
                        <input type="hidden" name="id_annonce" value="${annonceId}">
                        <input type="hidden" name="prix_total" id="hiddenTotalPrice" value="${unitPrice}">
                        <div class="form-group">
                            <label>Nombre de places (max: ${maxPlaces})</label>
                            <input type="number" name="nb_places" id="nbPlaces" min="1" max="${maxPlaces}" 
                                   value="1" required onchange="updateTotalPrice(${unitPrice})">
                        </div>
                        <div class="form-group">
                            <label>Type de bagage</label>
                            <select name="type_bagage" required>
                                <option value="">-- Choisissez --</option>
                                <option value="Sac à dos">Sac à dos</option>
                                <option value="Lunch box">Lunch box</option>
                                <option value="Petite valise">Petite valise</option>
                                <option value="Valise moyenne">Valise moyenne</option>
                                <option value="Grande valise">Grande valise</option>
                            </select>
                        </div>
                        <div class="popup-buttons">
                            <button type="button" class="cancel-btn">Annuler</button>
                            <button type="submit" name="reserver" class="confirm-btn">Confirmer</button>
                        </div>
                    </form>
                `;

                // Afficher le popup
                popup.classList.add('active');

                // Gestion des boutons
                document.querySelector('.cancel-btn').addEventListener('click', () => {
                    popup.classList.remove('active');
                });
            });
        });

        // Fermeture du popup
        closePopup.addEventListener('click', () => {
            popup.classList.remove('active');
        });

        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.classList.remove('active');
            }
        });
    });

    // Fonction pour mettre à jour le prix total (doit être globale)
    function updateTotalPrice(unitPrice) {
        const nbPlaces = document.getElementById('nbPlaces').value;
        const totalPrice = nbPlaces * unitPrice;
        document.getElementById('totalPrice').textContent = totalPrice.toFixed(2) + ' DT';
        document.getElementById('hiddenTotalPrice').value = totalPrice.toFixed(2);
    }
  </script>

</body>
</html>