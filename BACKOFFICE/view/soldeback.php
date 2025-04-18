<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Hezni - Gestion Recharges</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../view/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
  <!-- Header -->
  <header class="admin-header">
    <div class="admin-nav">
      <div class="admin-logo">
        <img src="../view/logo_blanc.png" alt="Hezni">
        <span>Gestion Recharges</span>
      </div>
      <div class="user-menu">
        <div class="user-profile">
          <img src="../view/sarra.jpg" alt="Admin">
          <span>Admin</span>
        </div>
      </div>
    </div>
  </header>

  <div class="admin-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-menu">
        <div class="menu-title">Gestion</div>
        <a href="#" class="menu-item active">
          <i class="fas fa-coins"></i>
          <span>Recharges</span>
        </a>
        <a href="#" class="menu-item">
          <i class="fas fa-users"></i>
          <span>Utilisateurs</span>
        </a>
        <div class="menu-title">Administration</div>
        <a href="#" class="menu-item">
          <i class="fas fa-cog"></i>
          <span>Paramètres</span>
        </a>
        <a href="#" class="menu-item">
          <i class="fas fa-sign-out-alt"></i>
          <span>Déconnexion</span>
        </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="content-header">
        <h1 class="page-title">
          <i class="fas fa-coins"></i>
          Gestion des recharges
        </h1>
        <div class="search-container">
          <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher une recharge...">
          </div>
        </div>
      </div>

      <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Recharge</th>
                    <th>ID Utilisateur</th>
                    <th>Montant (DT)</th>
                    <th>Date</th>
                    <th>Numéro Carte</th>
                    <th>Solde Total</th>
                    <th>Statut</th>
                    <th>Temps</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="rechargesTableBody">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "hezni";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM recharge";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr data-id='".$row["idr"]."'>";
                        echo "<td>".$row["idr"]."</td>";
                        echo "<td>".$row["idu"]."</td>";
                        echo "<td>".number_format($row["montant"], 3, '.', ',')."</td>";
                        echo "<td>".$row["date"]."</td>";
                        echo "<td>".$row["numcarte"]."</td>";
                        echo "<td>".number_format($row["soldetotal"], 3, '.', ',')."</td>";
                        echo "<td><span class='statut ".strtolower($row["statut"])."'>".$row["statut"]."</span></td>";
                        echo "<td>".$row["temps"]."</td>";
                        echo "<td class='actions-cell'>
                                <button class='btn-modifier' data-id='".$row["idr"]."'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <a href='../controller/rechargecontroller.php?action=supprimer&idr=".$row["idr"]."' class='btn-supprimer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette recharge ?\")'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Aucune recharge trouvée</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal de modification -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Modifier Recharge</h2>
      <form method="POST" action="../controller/rechargecontroller.php">
        <input type="hidden" name="idr" id="editId">
        
        <div class="form-group">
          <label>Statut:</label>
          <select name="statut" id="editStatut" required>
            <option value="EN ATTENTE">En attente</option>
            <option value="CONFIRMÉE">Confirmée</option>
            <option value="ECHOUÉ">Échoué</option>
          </select>
        </div>

        <div class="form-group">
          <label>Numero de carte:</label>
          <input type="number" name="numcarte" id="editNumcarte" step="0.001" required>
        </div>

        <div class="form-group">
          <label>Montant (DT):</label>
          <input type="number" name="montant" id="editMontant" step="0.001" required>
        </div>
        <div class="form-group">
          <label>Id utilisateur:</label>
          <input type="number" name="idu" id="editIdu" step="0.001" required>
        </div>
        <button type="submit" name="modifier" class="btn-save">Enregistrer</button>
      </form>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // Gestion du modal
      const modal = document.getElementById('editModal');
      
      // Ouvrir le modal et pré-remplir les données
      document.querySelectorAll('.btn-modifier').forEach(btn => {
          btn.addEventListener('click', function() {
              const row = this.closest('tr');
              document.getElementById('editId').value = row.cells[0].textContent;
              document.getElementById('editMontant').value = parseFloat(row.cells[2].textContent.replace(/,/g, ''));
              document.getElementById('editStatut').value = row.cells[6].textContent.trim();
              
              modal.style.display = 'block';
          });
      });
      
      // Fermer le modal
      document.querySelector('.close-modal').addEventListener('click', function() {
          modal.style.display = 'none';
      });
      
      // Fermer quand on clique en dehors
      window.addEventListener('click', function(event) {
          if (event.target === modal) {
              modal.style.display = 'none';
          }
      });
  });
  </script>
</body>
</html>