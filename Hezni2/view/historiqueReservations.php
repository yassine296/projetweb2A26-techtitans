<?php
session_start();
require_once '../model/reserverModel.php';
require_once '../config/connexion.php';

// Récupérer les réservations
$reservations = [];
try {
    $reservations = reservation_bagage::getReservations();
} catch (Exception $e) {
    $errorMessage = "Erreur de chargement de l'historique: ".$e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Historique des réservations</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../view/style.css">
  <style>
    .history-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .back-btn {
        background-color: #ff3c3c; /* Rouge vif comme dans votre back-btn */
    color: white;
    border: none;
    border-radius: 20px;
    padding: 12px 24px;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(255, 60, 60, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    max-width: 300px;
    margin: 20px auto 0;
    margin-left: 0px;
    }
    .back-btn:hover {
        background-color: #e63535;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(255, 60, 60, 0.3);
    }

    .back-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(255, 60, 60, 0.2);
}

/* Icône Font Awesome */
.back-btn i {
    font-size: 18px;
}

.section-title2 {
    font-size: 28px;
    color: #f44336;
    /*text-align: left;*/
    margin: 20px auto 0;
  }



  .reservation-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    justify-content: flex-end;
}

.action-form {
    margin: 0; /* Pour éviter les marges parasites */
}

.action-btn {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
}

.delete-btn {
    background-color: #ff3c3c;
    color: white;
}

.delete-btn:hover {
    background-color: #e63535;
}

.edit-btn {
    background-color: #4CAF50;
    color: white;
}

.edit-btn:hover {
    background-color: #45a049;
}
  </style>
</head>
<body>

  <header class="hero">
    <img src="../view/logo blanc.png" alt="Hezni" class="logo">
    <div class="wave-bottom">
      <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
      </svg>
    </div>-
  </header>

  <div class="history-container">
    <a href="../controller/reserverController.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Retour aux trajets
    </a>
    
    <h1 class="section-title2">Mon historique de réservations</h1>
    
    <?php if (!empty($reservations)): ?>
      <div class="card-container">
        <?php foreach ($reservations as $reservation): ?>
        <div class="card">
          <div class="card-content">
            
            <div class="ride-info">
              <div class="ride-info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><?= htmlspecialchars($reservation['ville_depart']) ?> → <?= htmlspecialchars($reservation['ville_arrive']) ?></span>
              </div>
              <div class="ride-info-item">
                <i class="fas fa-calendar-alt"></i>
                <span><?= date('d M Y', strtotime($reservation['date'])) ?></span>
              </div>
              <div class="ride-info-item">
                <i class="fas fa-clock"></i>
                <span><?= htmlspecialchars($reservation['heure']) ?></span>
              </div>
              <div class="ride-info-item">
                <i class="fas fa-suitcase"></i>
                <span><?= htmlspecialchars($reservation['nb_places']) ?> places</span>
              </div>
              <div class="ride-info-item">
                <i class="fas fa-tag"></i>
                <span>Type: <?= htmlspecialchars($reservation['type_bagage']) ?></span>
              </div>
            </div>
            <div class="price"><?= htmlspecialchars($reservation['prix_total']) ?> DT</div>
            <div class="reservation-date">
              Réservé le: <?= date('d M Y H:i', strtotime($reservation['date_reservation'])) ?>
            </div>
            <!-- Ajoutez cette nouvelle section pour les boutons -->
        <div class="reservation-actions">
            <form method="post" action="../controller/deleteReservationController.php" class="action-form">
                <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                <button type="submit" class="action-btn delete-btn">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </form>
            
            <button class="action-btn edit-btn" onclick="openEditModal(<?= $reservation['id'] ?>)">
                <i class="fas fa-edit"></i> Modifier
            </button>
        </div>

          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="no-reservations">Aucune réservation trouvée.</p>
    <?php endif; ?>
  </div>

  <footer>
    <div class="footer-content">
      <div class="copyright">
        &copy; 2023 Hezni. Tous droits réservés.
      </div>
    </div>
  </footer>

</body>
</html>