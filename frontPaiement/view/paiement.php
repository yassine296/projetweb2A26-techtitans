<?php
require_once __DIR__.'/../controller/paiementcontroller.php';

// Messages
$alert = '';
if (isset($_GET['success'])) {
    $alert = '<div class="alert success"><i class="fas fa-check-circle"></i> Paiement effectué avec succès!</div>';
} elseif (isset($_GET['error'])) {
    $alert = '<div class="alert error"><i class="fas fa-exclamation-circle"></i> Erreur lors du paiement</div>';
}

// Données utilisateur
$userId = 1;
$balance = Payment::getBalance($userId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEZNI - Paiement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
 <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .hero {
        background: linear-gradient(135deg, #ff3131, #ff3131);
        color: white;
        padding: 100px 0 150px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .alert-container {
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
        z-index: 10;
    }

    .alert {
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        border-left: 4px solid;
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .success {
        background: rgba(212, 237, 218, 0.9);
        color: #155724;
        border-left-color: #ff3131;
    }

    .error {
        background: rgba(248, 215, 218, 0.9);
        color: #721c24;
        border-left-color: #ff3131;
    }

    .wave-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    .wave-bottom svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 120px;
    }

    .wave-bottom path {
        fill: #f8f9fa;
    }

    .container {
        max-width: 1000px;
        margin: -60px auto 30px;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .balance-card {
        background: linear-gradient(135deg, #ff3131, #ff3131);
        color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(255, 49, 49, 0.2);
        text-align: center;
        margin-bottom: 30px;
        transition: transform 0.3s;
    }

    .balance-card:hover {
        transform: translateY(-5px);
    }

    .payment-options {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 15px 30px;
        background: linear-gradient(to right, #ff3131, #ff3131);
        color: white;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        min-width: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(255, 49, 49, 0.3);
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 49, 49, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(to right, #457b9d, #1d3557);
        box-shadow: 0 4px 15px rgba(29, 53, 87, 0.3);
    }

    .payment-method {
        display: none;
        max-width: 500px;
        margin: 0 auto 40px;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s;
        background-color: #f8f9fa;
    }

    .form-group input:focus {
        outline: none;
        border-color: #ff3131;
        box-shadow: 0 0 0 3px rgba(255, 49, 49, 0.2);
        background-color: white;
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-row .form-group {
        flex: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    table th {
        background: #ff3131;
        color: white;
        padding: 15px;
        text-align: left;
    }

    table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    table tr:hover {
        background-color: #f5f5f5;
    }

    @media (max-width: 768px) {
        .payment-options {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>

</head>
<body>
    <header class="hero">
        <div class="alert-container">
            <?= $alert ?>
        </div>
        <h1>HEZNI - Paiement</h1>
        <div class="wave-bottom">
            <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path d="M0,192 C360,600 1080,-150 1440,300 L1440,320 L0,320 Z"></path>
            </svg>
        </div>
    </header>

    <div class="container">
        <div class="balance-card">
            <h2>Votre solde HEZNI</h2>
            <p><strong><?= $balance ?> DT</strong></p>
            <small>Dernière mise à jour : <?= date('d/m/Y') ?></small>
        </div>

        <div class="payment-options">
            <button class="btn" onclick="showMethod('balance')">
                <i class="fas fa-wallet"></i> Payer avec Solde
            </button>
            <button class="btn" onclick="showMethod('card')">
                <i class="fas fa-credit-card"></i> Payer par Carte
            </button>
            <button class="btn btn-secondary" onclick="showMethod('cash')">
                <i class="fas fa-money-bill-wave"></i> Payer en Espèce
            </button>
        </div>

        <!-- Formulaire Paiement par solde -->
        <form id="balanceForm" class="payment-method" method="post" action="">
            <h3>Paiement avec votre solde HEZNI</h3>
            <p>Montant à payer : <strong>20 DT</strong></p>
            <input type="hidden" name="id_utilisateur" value="<?= $userId ?>">
            <input type="hidden" name="montant" value="20">
            <input type="hidden" name="type_paiement" value="solde">
            <button type="submit" name="submit_payment" class="btn">
                <i class="fas fa-check-circle"></i> Confirmer
            </button>
        </form>

        <!-- Formulaire Paiement par carte -->
        <form id="cardForm" class="payment-method" method="post" action="">
            <h3>Paiement par carte bancaire</h3>
            <input type="hidden" name="id_utilisateur" value="<?= $userId ?>">
            <input type="hidden" name="montant" value="20">
            <input type="hidden" name="type_paiement" value="carte">
            
            <div class="form-group">
                <label for="nom_carte">Nom sur la carte</label>
                <input type="text" id="nom_carte" name="nom_carte" placeholder="Ex: Jean Dupont" required>
            </div>
            
            <div class="form-group">
                <label for="numero_carte">Numéro de carte</label>
                <input type="text" id="numero_carte" name="numero_carte" placeholder="1234 5678 9012 3456" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_expiration">Date expiration</label>
                    <input type="text" id="date_expiration" name="date_expiration" placeholder="MM/AA" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                </div>
            </div>
            
            <button type="submit" name="submit_payment" class="btn">
                <i class="fas fa-lock"></i> Payer 20 DT
            </button>
        </form>

        <!-- Formulaire Paiement en espèces -->
        <form id="cashForm" class="payment-method" method="post" action="">
            <h3>Paiement en espèces</h3>
            <p>Vous paierez directement au conducteur</p>
            <input type="hidden" name="id_utilisateur" value="<?= $userId ?>">
            <input type="hidden" name="montant" value="20">
            <input type="hidden" name="type_paiement" value="espece">
            <button type="submit" name="submit_payment" class="btn btn-secondary">
                <i class="fas fa-check-circle"></i> Confirmer
            </button>
        </form>

        <!-- Historique des transactions -->
        <h2 style="margin-top: 40px; color: #e63946;">Historique des paiements</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $history = Payment::getHistory($userId);
                foreach ($history as $transaction): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($transaction['date_paiement'])) ?></td>
                    <td><?= $transaction['montant'] ?> DT</td>
                    <td><?= ucfirst($transaction['type_paiement']) ?></td>
                    <td><?= $transaction['statut'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function showMethod(method) {
            document.querySelectorAll('.payment-method').forEach(form => {
                form.style.display = 'none';
            });
            document.getElementById(method + 'Form').style.display = 'block';
        }
        
        // Afficher le premier formulaire par défaut
        document.addEventListener('DOMContentLoaded', function() {
            showMethod('balance');
        });
    </script>
</body>
</html>