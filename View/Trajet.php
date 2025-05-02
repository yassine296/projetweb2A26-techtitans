<?php
  include 'ajouterTrajet.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Ajouter un trajet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Hezni - Le premier site de covoiturage dédié aux étudiants en Tunisie.">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="styleAjouterTrajet.css">
  <script src="controleTrajet.js"></script>

  <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
</head>

<body>

  <div class="menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <script>
    const menuToggle = document.getElementById('menuToggle');
    menuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
    });
  </script>

  <header class="hero">
    <img src="logo blanc.png" alt="Hezni" class="logo">
    <img src="ajouterTrajet.png" alt="image" class="imageA">
    <div class="wave-bottom">
      <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
      </svg>
    </div>
  </header>

  <div class="form-container">
    <div class="form-title">Ajouter un Trajet</div>
    <?= $message ?>
    <form method="POST" action="ajouterTrajet.php">
      <input type="text" id="depart" name="depart" placeholder="Départ" value="<?= htmlspecialchars($trajet->getV_DEP() ?? '') ?>">
      <input type="text" id="destination" name="destination" placeholder="Destination" value="<?= htmlspecialchars($trajet->getV_ARR() ?? '') ?>">
      <div class="row">
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($trajet->getDATE() ?? '') ?>">
        <input type="time" id="heure" name="heure" value="<?= htmlspecialchars($trajet->getHEURE() ?? '') ?>">
      </div>
      <input type="number" id="places" name="places" min="1" placeholder="Nombre de places" value="<?= htmlspecialchars($trajet->getNB_PASS() ?? '') ?>">
      <div class="row">
        <input type="number" id="GV" name="GV" min="0" placeholder="Nombre de grandes valises" value="<?= htmlspecialchars($trajet->getNB_GV() ?? '') ?>">
        <input type="number" id="MV" name="MV" min="0" placeholder="Nombre de moyenne valises" value="<?= htmlspecialchars($trajet->getNB_MV() ?? '') ?>">
        <input type="number" id="PV" name="PV" min="0" placeholder="Nombre de petites valises" value="<?= htmlspecialchars($trajet->getNB_PV() ?? '') ?>">
      </div>
      <input type="number" step="0.01" id="prix" name="prix" min="1" placeholder="Prix" value="<?= htmlspecialchars($trajet->getPRIX() ?? '') ?>">
      <button type="submit"><i class="fas fa-plus"></i> Ajouter</button>
    </form>
  </div>


  <div class="content"> 
    <div class="section-header">
      <h1 class="section-title">Vos Trajets</h1>
      <div class="results-count"><?= count($listeTrajets) ?> résultat(s) trouvé(s)</div>
    </div>

    <div class="filters">
      <div class="filter-tag active">Tous</div>
      <div class="filter-tag">Aujourd'hui</div>
      <div class="filter-tag">Demain</div>
      <div class="filter-tag">Cette semaine</div>
      <div class="filter-tag">Moins de 10 DT</div>
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
                  <button type="button" class="book-btn modify-btn"
                    onclick='afficherFormulaire(<?= json_encode([ 
                        "id" => $t->getIDT(), 
                        "depart" => $t->getV_DEP(), 
                        "destination" => $t->getV_ARR(), 
                        "date" => $t->getDATE(), 
                        "heure" => $t->getHEURE(), 
                        "places" => $t->getNB_PASS(), 
                        "gv" => $t->getNB_GV(), 
                        "mv" => $t->getNB_MV(), 
                        "pv" => $t->getNB_PV(), 
                        "prix" => $t->getPRIX() 
                    ]) ?>)'>
                    <i class="fas fa-edit"></i> Modifier
                  </button>
                  <form method="GET" action="deleteTrajet.php" style="display:inline;">
                    <input type="hidden" name="IDT" value="<?= $t->getIDT() ?>">
                    <button class="book-btn delete-btn"><i class="fas fa-trash-alt"></i> Supprimer</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <!-- Overlay style popup -->
  <div class="popup-overlay" id="overlay">
    <div class="popup-content">
      <div class="popup-header">
        <h3>Modifier un Trajet</h3>
        <button class="close-popup" onclick="fermerFormulaire()">&times;</button>
      </div>
      <form id="modifier-form" method="GET" action="modifierTrajet.php" class="reservation-form">
        <input type="hidden" name="id" id="mod-id">

        <div class="form-group">
          <label for="mod-depart">Départ</label>
          <input type="text" name="depart" id="mod-depart" placeholder="Départ">
        </div>

        <div class="form-group">
          <label for="mod-destination">Destination</label>
          <input type="text" name="destination" id="mod-destination" placeholder="Destination">
        </div>

        <div class="form-group row">
          <div class="form-group" style="flex:1">
            <label for="mod-date">Date</label>
            <input type="date" name="date" id="mod-date">
          </div>
          <div class="form-group" style="flex:1">
            <label for="mod-heure">Heure</label>
            <input type="time" name="heure" id="mod-heure">
          </div>
        </div>

        <div class="form-group">
          <label for="mod-places">Places disponibles</label>
          <input type="number" name="places" id="mod-places" placeholder="Places">
        </div>

        <div class="form-group row">
          <div class="form-group" style="flex:1">
            <label for="mod-gv">Grandes valises</label>
            <input type="number" name="GV" id="mod-gv" placeholder="GV">
          </div>
          <div class="form-group" style="flex:1">
            <label for="mod-mv">Moyennes valises</label>
            <input type="number" name="MV" id="mod-mv" placeholder="MV">
          </div>
          <div class="form-group" style="flex:1">
            <label for="mod-pv">Petites valises</label>
            <input type="number" name="PV" id="mod-pv" placeholder="PV">
          </div>
        </div>

        <div class="form-group">
          <label for="mod-prix">Prix</label>
          <input type="number" step="0.01" name="prix" id="mod-prix" placeholder="Prix">
        </div>

        <div class="popup-buttons">
          <button type="submit" class="popup-btn confirm-btn"><i class="fas fa-check-circle"></i> Valider</button>
          <button type="button" onclick="fermerFormulaire()" class="popup-btn cancel-btn"><i class="fas fa-times-circle"></i> Annuler</button>
        </div>
      </form>
    </div>
  </div>


  <script>
    function afficherFormulaire(t) {
      document.querySelector('.content').classList.add('blur');
      document.querySelector('.form-container').classList.add('blur');
      document.querySelector('#overlay').style.display = 'flex';

      document.getElementById('mod-id').value = t.id;
      document.getElementById('mod-depart').value = t.depart;
      document.getElementById('mod-destination').value = t.destination;
      document.getElementById('mod-date').value = t.date;
      document.getElementById('mod-heure').value = t.heure;
      document.getElementById('mod-places').value = t.places;
      document.getElementById('mod-gv').value = t.gv;
      document.getElementById('mod-mv').value = t.mv;
      document.getElementById('mod-pv').value = t.pv;
      document.getElementById('mod-prix').value = t.prix;
    }

    function fermerFormulaire() {
      document.querySelector('#overlay').style.display = 'none';
      document.querySelector('.content').classList.remove('blur');
      document.querySelector('.form-container').classList.remove('blur');
    }

    document.querySelector('.form-container form').addEventListener('submit', function(event) {
      let depart = document.getElementById('depart').value.trim();
      let destination = document.getElementById('destination').value.trim();
      let date = document.getElementById('date').value;
      let heure = document.getElementById('heure').value;
      let places = document.getElementById('places').value;
      let gv = document.getElementById('GV').value;
      let mv = document.getElementById('MV').value;
      let pv = document.getElementById('PV').value;
      let prix = document.getElementById('prix').value;

      let erreurs = [];

      if (!/^[A-Za-z\s]{2,}$/.test(depart)) {
        erreurs.push("Le lieu de départ doit contenir au moins 2 lettres.");
      }

      else if (!/^[A-Za-z\s]{2,}$/.test(destination)) {
        erreurs.push("La destination doit contenir au moins 2 lettres.");
      }

      else if (!date) {
        erreurs.push("La date est obligatoire.");
      }

      else if (!heure) {
        erreurs.push("L'heure est obligatoire.");
      }

      else if (!/^\d+$/.test(places) || places < 1) {
        erreurs.push("Le nombre de places doit être un nombre valide supérieur à 0.");
      }

      else if (!/^\d+$/.test(gv)) {
        erreurs.push("Le nombre de grands véhicules doit être un nombre.");
      }

      else if (!/^\d+$/.test(mv)) {
        erreurs.push("Le nombre de moyens véhicules doit être un nombre.");
      }

      else if (!/^\d+$/.test(pv)) {
        erreurs.push("Le nombre de petits véhicules doit être un nombre.");
      }

      else if (!/^\d+(\.\d{1,2})?$/.test(prix) || prix <= 0) {
        erreurs.push("Le prix doit être un nombre positif.");
      }

      if (erreurs.length > 0) {
        event.preventDefault();
        alert(erreurs.join("\n"));
      }
    });

    document.querySelector('.popup-content').addEventListener('submit', function(event) {
      let depart = document.getElementById('mod-depart').value.trim();
      let destination = document.getElementById('mod-destination').value.trim();
      let date = document.getElementById('mod-date').value;
      let heure = document.getElementById('mod-heure').value;
      let places = document.getElementById('mod-places').value;
      let gv = document.getElementById('mod-gv').value;
      let mv = document.getElementById('mod-mv').value;
      let pv = document.getElementById('mod-pv').value;
      let prix = document.getElementById('mod-prix').value;

      let erreurs = [];

      if (!/^[A-Za-z\s]{2,}$/.test(depart)) {
          erreurs.push("Le lieu de départ doit contenir au moins 2 lettres.");
      }

      else if (!/^[A-Za-z\s]{2,}$/.test(destination)) {
          erreurs.push("La destination doit contenir au moins 2 lettres.");
      }

      else if (!date) {
          erreurs.push("La date est obligatoire.");
      }

      else if (!heure) {
          erreurs.push("L'heure est obligatoire.");
      }

      else if (!/^\d+$/.test(places) || places < 1) {
          erreurs.push("Le nombre de places doit être un nombre valide supérieur à 0.");
      }

      else if (!/^\d+$/.test(gv)) {
          erreurs.push("Le nombre de grands véhicules doit être un nombre.");
      }

      else if (!/^\d+$/.test(mv)) {
          erreurs.push("Le nombre de moyens véhicules doit être un nombre.");
      }

      else if (!/^\d+$/.test(pv)) {
          erreurs.push("Le nombre de petits véhicules doit être un nombre.");
      }

      else if (!/^\d+(\.\d{1,2})?$/.test(prix) || prix <= 0) {
          erreurs.push("Le prix doit être un nombre positif.");
      }

      if (erreurs.length > 0) {
          event.preventDefault();
          alert(erreurs.join("\n"));
      }
  });


  </script>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
  </script>
  <script src="js/maps.js"></script>

  <?php 
    if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <script>
          alert("Trajet ajouté avec succès.");
      </script>
  <?php endif; ?>


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
        &copy; <?= date('Y') ?> Hezni. Tous droits réservés.
      </div>
    </div>
  </footer>

</body>
</html>
