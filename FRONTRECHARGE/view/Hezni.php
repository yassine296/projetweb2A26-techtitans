<?php
require_once '../model/recharge.php';
require_once '../config/connexion.php';

// ID utilisateur (fixe ou via session si dispo)
$idu = 1;

// Récupérer l’historique de la base
$historique = recharge::getHistorique($idu);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Hezni - Recharger votre solde</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../view/style.css">
  <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
  <style>
    .error-message {
      font-size: 0.9em;
      color: red;
      margin-top: 5px;
    }

    .bonus-animation {
      display: none;
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #fff0c2;
      border: 2px solid #ffd700;
      padding: 15px 25px;
      font-size: 1.1em;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      z-index: 1000;
      animation: pop 0.5s ease-out;
    }

    @keyframes pop {
      0% { transform: translateX(-50%) scale(0.5); opacity: 0; }
      100% { transform: translateX(-50%) scale(1); opacity: 1; }
    }

    .confetti {
      position: fixed;
      font-size: 20px;
      animation: fall 3s linear forwards;
    }

    @keyframes fall {
      0% { top: -50px; opacity: 1; }
      100% { top: 100vh; opacity: 0; }
    }
  </style>
</head>
<body>
  <div class="bonus-animation" id="bonus-animation">🎉 Bravo ! Recharge généreuse ! +10% bonus ajouté 💸</div>

  <!-- Header -->
  <header class="recharge-header">
    <div class="header-content">
      <img src="logo_blanc.png" alt="Hezni" class="logo">
      <h1>Recharger votre solde</h1>
    </div>
    <div class="wave-bottom">
      <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path d="M0,200 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
      </svg>
    </div>
  </header>

  <!-- Main Content -->
  <main class="recharge-container">
    <!-- Solde -->
    <section class="balance-section">
      <div class="balance-card">
        <h2><i class="fas fa-wallet"></i> Votre solde</h2>
        <div class="balance-amount">
  <span id="current-balance"><?= isset($_GET['new_balance']) ? number_format($_GET['new_balance'], 3) : '0.000' ?></span> TND
</div>

<div class="balance-history">
  <h3>Historique des recharges</h3>
  <div class="history-list">
    <?php if (!empty($historique)): ?>
      <?php foreach ($historique as $recharge): ?>
        <div class="history-item">
          <span class="amount">+<?= number_format($recharge['montant'], 3) ?> TND</span>
          <span class="date"><?= date('d/m/Y', strtotime($recharge['date'])) ?></span>
          <span class="status <?= $recharge['statut'] === 'complété' ? 'completed' : 'pending' ?>">
              <?= ucfirst($recharge['statut']) ?>
          </span>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-history">Aucun historique de recharge disponible</div>
    <?php endif; ?>
  </div>
</div>

      </div>
    </section>

    <!-- Formulaire -->
    <section class="form-section">
      <form action="../controller/rechargecontroller.php" method="post" class="recharge-form">
        <h2><i class="fas fa-coins"></i> Nouvelle recharge</h2>
        
        <div class="bonus-info">
          <i class="fas fa-gift"></i> Bonus spécial : +10% pour toute recharge supérieure à 50 DT!
        </div>

        <div class="form-group">
          <label for="montant">Montant (TND)</label>
          <div class="amount-selector">
            <button type="button" class="amount-btn" data-amount="10">10</button>
            <button type="button" class="amount-btn" data-amount="20">20</button>
            <button type="button" class="amount-btn" data-amount="50">50</button>
            <button type="button" class="amount-btn" data-amount="100">100</button>
          </div>
          <input type="number" name="montant" id="montant">
          <div class="error-message" id="montant-error"></div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date">
            <div class="error-message" id="date-error"></div>
          </div>
          <div class="form-group">
            <label for="time">Heure</label>
            <input type="time" name="temps" id="time">
            <div class="error-message" id="time-error"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="numcarte">Numéro de carte</label>
          <input type="text" name="numcarte" id="numcarte" placeholder="1234 5678 9012 3456">
          <div class="error-message" id="numcarte-error"></div>
        </div>

        <button type="submit" name="ajouter" class="submit-btn">
          <i class="fas fa-plus-circle"></i> Confirmer la recharge
        </button>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
          <div class="confirmation-message">
            Recharge de <?= number_format($_GET['montant'], 3) ?> DT effectuée avec succès!
            <?php if ($_GET['bonus'] > 0): ?>
              <div class="bonus-message">
                <i class="fas fa-gift"></i> Félicitations! Vous avez gagné un bonus de <?= number_format($_GET['bonus'], 3) ?> DT!
              </div>
            <?php endif; ?>
            Votre nouveau solde est: <span id="new-balance"><?= number_format($_GET['new_balance'], 3) ?></span> TND
          </div>
        <?php endif; ?>
      </form>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const now = new Date();
      document.getElementById('date').value = now.toISOString().split('T')[0];
      document.getElementById('time').value = now.toTimeString().substr(0, 5);

      document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const amount = parseFloat(this.dataset.amount);
          document.getElementById('montant').value = amount;

          if (amount > 50) {
            showBonusEffect();
          } else {
            document.getElementById("montant-error").textContent = '';
          }
        });
      });

      const cardInput = document.getElementById('numcarte');
      cardInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\s+/g, '');
        if (value.length > 0) {
          value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
        }
        e.target.value = value;
      });

      document.querySelector(".recharge-form").addEventListener("submit", function (e) {
        let isValid = true;

        const montant = document.getElementById("montant");
        const date = document.getElementById("date");
        const time = document.getElementById("time");
        const numcarte = document.getElementById("numcarte");
        const cardRegex = /^\d{4}\s*\d{4}\s*\d{4}\s*\d{4}$/;

        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        if (!montant.value || parseFloat(montant.value) <= 0) {
          document.getElementById("montant-error").textContent = "Veuillez entrer un montant supérieur à 0.";
          montant.style.border = "2px solid red";
          isValid = false;
        } else {
          montant.style.border = "";
        }

        if (!date.value) {
          document.getElementById("date-error").textContent = "Veuillez sélectionner une date.";
          date.style.border = "2px solid red";
          isValid = false;
        } else {
          date.style.border = "";
        }

        if (!time.value) {
          document.getElementById("time-error").textContent = "Veuillez sélectionner une heure.";
          time.style.border = "2px solid red";
          isValid = false;
        } else {
          time.style.border = "";
        }

        if (!cardRegex.test(numcarte.value.trim())) {
          document.getElementById("numcarte-error").textContent = "Le numéro de carte doit contenir 16 chiffres (ex: 1234 5678 9012 3456)";
          numcarte.style.border = "2px solid red";
          isValid = false;
        } else {
          numcarte.style.border = "";
        }

        if (parseFloat(montant.value) > 50) {
          showBonusEffect();
        }

        if (!isValid) e.preventDefault();
      });

      function showBonusEffect() {
        const msg = document.getElementById("bonus-animation");
        msg.style.display = "block";
        setTimeout(() => msg.style.display = "none", 4000);

        for (let i = 0; i < 30; i++) {
          const confetti = document.createElement("div");
          confetti.classList.add("confetti");
          confetti.textContent = ['🎉', '✨', '💸', '🎊'][Math.floor(Math.random() * 4)];
          confetti.style.left = Math.random() * 100 + "vw";
          confetti.style.top = "-30px";
          document.body.appendChild(confetti);
          setTimeout(() => confetti.remove(), 3000);
        }
      }
    });
  </script>
</body>
</html>
