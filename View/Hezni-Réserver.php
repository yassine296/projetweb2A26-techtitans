<?php
  include 'ajouterTrajet.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Hezni - Réserver un trajet</title>
    <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie. Réservez des trajets économiques et écologiques entre villes universitaires.">
    
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="styleHezni.css">

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
  </head>
  
  <body>
    <!-- Menu Toggle -->
    <div class="menu-toggle" id="menuToggle">
      <span></span>
      <span></span>
      <span></span>
    </div>

    <!-- Hero Section -->
    <header class="hero">
      <img src="logo blanc.png" alt="Hezni" class="logo">
      <div class="hero-text">
        HEZNI - Le premier site de <span>covoiturage étudiant</span> en Tunisie
      </div>
      <div class="wave-bottom">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
          <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
        </svg>
      </div>
    </header>

    <!-- Form Container -->
    <div class="form-container">
      <h2 class="form-title">Trouvez votre trajet</h2>
      <input type="text" placeholder="Ville de départ" id="departure">
      <input type="text" placeholder="Ville d'arrivée" id="destination">
      <div class="row">
        <input type="date" placeholder="Date d'aller" id="date-depart">
        <input type="time" id="heure-depart" name="heure-depart">
      </div>
      <select id="passengers">
        <option value="1">1 passager</option>
        <option value="2">2 passagers</option>
        <option value="3">3 passagers</option>
        <option value="4">4 passagers</option>
      </select>
      <input type="text" placeholder="Prix en TND" id="Prix">
      <button id="search-btn"><i class="fas fa-search"></i> Rechercher</button>
    </div>

    <!-- Available Trajets Section -->
    <div class="content">
      <div class="section-header">
        <h1 class="section-title">Trajets disponibles</h1>
        <div class="results-count">12 résultats trouvés</div>
      </div>

      <div class="filters">
        <button class="tab-btn active" onclick="afficherTrajets()">Vos Trajets</button>
        <button class="tab-btn" onclick="afficherReservations()">Vos Réservations</button>
        <div class="tab-btn">Demain</div>
        <div class="tab-btn">Cette semaine</div>
        <div class="tab-btn">Moins de 10 DT</div>
      </div>

      <div class="page-container">
        <div class="cards-wrapper">
          <?php foreach ($listeTrajets as $t): ?>
            <div class="card-container">
              <div class="driver-info">
                <img src="photo.jpg" alt="Conducteur" class="driver-pic">
                <div>
                  <h3 class="driver-pseudo">Yassine Zariat</h3>
                  <div class="rating">⭐⭐⭐☆☆ (3.5)</div>
                </div>
              </div>
              <div class="trajet-content">
                <div class="trajets-dispo">
                  <div><i class="fas fa-map-marker-alt"></i><span><?= htmlspecialchars($t->getV_DEP()) ?> → <?= htmlspecialchars($t->getV_ARR()) ?></span></div>
                  <div><i class="fas fa-calendar-alt"></i><span><?= htmlspecialchars($t->getDATE()) ?></span></div>
                  <div><i class="fas fa-clock"></i><span><?= htmlspecialchars($t->getHEURE()) ?></span></div>
                  <div><i class="fas fa-chair"></i><span><?= $t->getNB_PASS() ?> places disponibles</span></div>
                  <div><i class="fas fa-suitcase-rolling"></i><span><?= $t->getNB_GV() ?> Grandes valises</span></div>
                  <div><i class="fas fa-suitcase"></i><span><?= $t->getNB_MV() ?> Moyennes valises</span></div>
                  <div><i class="fas fa-briefcase"></i><span><?= $t->getNB_PV() ?> Petites valises</span></div>
                  <div><i class="fas fa-car"></i><span>Peugeot 208 - Gris</span></div>
                  <div class="price-reserve">
                    <div><i class="fas fa-money-bill-wave"></i><span><?= $t->getPRIX() ?> DT</span></div>
                  </div>
                </div>
                <div class="map-and-buttons">
                <div class="map-container" id="map-<?= $t->getIDT() ?>" data-depart="<?= htmlspecialchars($t->getV_DEP()) ?>" data-arrivee="<?= htmlspecialchars($t->getV_ARR()) ?>"></div>
                  <div class="buttons-container">
                  <button type="button" class="book-btn" onclick='afficherPopupReservation(<?= json_encode([ "id" => $t->getIDT(), "prix" => $t->getPRIX() ]) ?>)'><i class="fas fa-car"></i> Réserver</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Reservation Popup -->
    <div id="popup-reservation" class="popup-reservation">
      <div class="popup-header">
        <h2>Réserver ce trajet</h2>
        <button onclick="fermerPopupReservation()" class="close-btn">&times;</button>
      </div>
      <p>
        <strong>Prix unitaire:</strong> <span id="prix_unitaire">-- DT</span><br>
        <strong>Prix total:</strong> <span id="prix_total">-- DT</span>
      </p>
      <form action="ajouterRes.php" method="POST">
        <input type="hidden" id="idt_hidden" name="IDT">
        <div class="form-group">
            <label for="passenger_email">Votre email</label>
            <input type="email" id="passenger_email" name="passenger_email" required class="form-input" 
                  placeholder="exemple@email.com">
        </div>
        <label for="places">Nombre de places (max: 1)</label>
        <input type="number" name="places" id="places" min="1" max="1" value="1" oninput="calculerPrixTotal()" required class="form-input">
        <label for="bagage">Type de bagage</label>
        <div class="row">
          <input class="form-input" type="number" id="GV" name="GV" min="0" placeholder="Nombre de grandes valises" value="<?= htmlspecialchars($trajet->getNB_GV() ?? '') ?>">
          <input class="form-input" type="number" id="MV" name="MV" min="0" placeholder="Nombre de moyenne valises" value="<?= htmlspecialchars($trajet->getNB_MV() ?? '') ?>">
          <input class="form-input" type="number" id="PV" name="PV" min="0" placeholder="Nombre de petites valises" value="<?= htmlspecialchars($trajet->getNB_PV() ?? '') ?>">
        </div>
        <div class="popup-actions">
          <button type="button" onclick="fermerPopupReservation()" class="btn btn-cancel">Annuler</button>
          <button type="submit" class="btn btn-confirm">Confirmer</button>
        </div>
      </form>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="popup-overlay"></div>

    <!-- Filter Button -->
    <div>
      <button class="filtrer" id="filter-btn">
        <i class="fas fa-sliders-h"></i> Filtrer
      </button>
    </div>

    <!-- Footer Section -->
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

    <!-- Script Section -->
    <script>
      const menuToggle = document.getElementById('menuToggle');
      menuToggle.addEventListener('click', function() {
        this.classList.toggle('active');
      });

      let prixUnitaireGlobal = 0;

      function afficherPopupReservation(data) {
        prixUnitaireGlobal = parseFloat(data.prix);
        document.getElementById('prix_unitaire').innerText = prixUnitaireGlobal + ' DT';
        document.getElementById('prix_total').innerText = prixUnitaireGlobal + ' DT';
        document.getElementById('idt_hidden').value = data.id;
        document.getElementById('places').value = 1;
        document.getElementById('popup-reservation').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
      }

      function fermerPopupReservation() {
        document.getElementById('popup-reservation').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      }

      function calculerPrixTotal() {
        const places = parseInt(document.getElementById('places').value);
        const total = places * prixUnitaireGlobal;
        document.getElementById('prix_total').innerText = total + ' DT';
      }
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script>
    <script src="js/maps.js"></script>
  </body>
</html>