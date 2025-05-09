<?php
  include_once __DIR__ . '/../controller/TrajetCRUD.php';

  $trajetC = new TrajetC();
  $filtre = $_GET['filtre'] ?? 'tous';

  switch ($filtre) {
    case 'Vos Trajets':
      $listeTrajets = $trajetC->consulterTrajet();
      break;
    case 'Historique':
      $listeTrajets = $trajetC->consulterHistoriqueTrajet();
      break;
    case 'complet':
      $listeTrajets = $trajetC->consulterTrajetComplet();
      break;
    case 'semaine':
      $listeTrajets = $trajetC->consulterTrajetSemaine();
      break;
    default:
      $listeTrajets = $trajetC->consulterTrajet();
      break;
  }

  foreach ($listeTrajets as $t): ?>
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
                  <div class="map-container" 
                      id="map-<?= $t->getIDT() ?>" 
                      data-depart="<?= htmlspecialchars($t->getV_DEP()) ?>" 
                      data-arrivee="<?= htmlspecialchars($t->getV_ARR()) ?>"
                      data-initialized="false">
                  </div>
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
  <?php endforeach;
?>