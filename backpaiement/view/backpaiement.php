<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Hezni - Gestion Paiements</title>
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
        <span>Gestion Paiements</span>
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
          <i class="fas fa-credit-card"></i>
          <span>Paiements</span>
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
          <i class="fas fa-credit-card"></i>
          Gestion des paiements
        </h1>
        <div class="search-container">
          <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un paiement...">
          </div>
        </div>
      </div>

      <?php
      // Affichage des messages de succès/erreur
      if (isset($_GET['success'])) {
          echo '<div class="alert-success">';
          if ($_GET['success'] == 'modification') {
              echo 'Paiement modifié avec succès!';
          } elseif ($_GET['success'] == 'suppression') {
              echo 'Paiement supprimé avec succès!';
          }
          echo '</div>';
      }
      if (isset($_GET['error'])) {
          echo '<div class="alert-error">';
          if ($_GET['error'] == 'modification') {
              echo 'Erreur lors de la modification du paiement!';
          } elseif ($_GET['error'] == 'suppression') {
              echo 'Erreur lors de la suppression du paiement!';
          }
          echo '</div>';
      }
      ?>

      <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Paiement</th>
                    <th>ID Utilisateur</th>
                    <th>Montant (DT)</th>
                    <th>Date Paiement</th>
                    <th>Type Paiement</th>
                    <th>Statut</th>
                    <th>Nom Carte</th>
                    <th>Numéro Carte</th>
                    <th>Date Exp</th>
                    <th>CVV</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paiementsTableBody">
                <?php
                require_once '../model/backpaiementmodel.php';
                $paiement = new paiement();
                $paiements = $paiement->getAllpaiement();

                if (!empty($paiements)) {
                    foreach($paiements as $row) {
                        echo "<tr data-id='".htmlspecialchars($row["id_paiement"])."'>";
                        echo "<td>".htmlspecialchars($row["id_paiement"])."</td>";
                        echo "<td>".htmlspecialchars($row["id_utilisateur"])."</td>";
                        echo "<td>".number_format($row["montant"], 3, '.', ',')."</td>";
                        echo "<td>".htmlspecialchars($row["date_paiement"])."</td>";
                        echo "<td>".htmlspecialchars($row["type_paiement"])."</td>";
                        echo "<td><span class='statut ".strtolower(htmlspecialchars($row["statut"]))."'>".htmlspecialchars($row["statut"])."</span></td>";
                        echo "<td>".htmlspecialchars($row["nom_carte"])."</td>";
                        echo "<td>".htmlspecialchars($row["numero_carte"])."</td>";
                        echo "<td>".htmlspecialchars($row["date_expiration"])."</td>";
                        echo "<td>".htmlspecialchars($row["cvv"])."</td>";
                        echo "<td class='actions-cell'>
                                <button class='btn-modifier' data-id='".htmlspecialchars($row["id_paiement"])."'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <a href='../controller/backpaiementcontroller.php?action=supprimer&id_paiement=".htmlspecialchars($row["id_paiement"])."' class='btn-supprimer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce paiement ?\")'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Aucun paiement trouvé</td></tr>";
                }
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
      <h2><i class="fas fa-edit"></i> Modifier Paiement</h2>
      <form method="POST" action="../controller/backpaiementcontroller.php">
        <input type="hidden" name="id_paiement" id="editId">
        
        <div class="form-group">
          <label>Statut:</label>
          <select name="statut" id="editStatut" class="form-control" >
            <option value="EN ATTENTE">En attente</option>
            <option value="RÉUSSI">Réussi</option>
            <option value="ECHOUÉ">Échoué</option>
          </select>
        </div>

        <div class="form-group">
          <label>ID Utilisateur:</label>
          <input type="text" name="id_utilisateur" id="editIdu" class="form-control" >
        </div>

        <div class="form-group">
          <label>Montant (DT):</label>
          <input type="number" name="montant" id="editMontant" step="0.001" class="form-control" r>
        </div>

        <div class="form-group">
          <label>Date Paiement:</label>
          <input type="date" name="date_paiement" id="editDate" class="form-control" >
        </div>

        <div class="form-group">
          <label>Type Paiement:</label>
          <input type="text" name="type_paiement" id="editType" class="form-control" >
        </div>

        <div class="form-group">
          <label>Nom Carte:</label>
          <input type="text" name="nom_carte" id="editNomCarte" class="form-control" >
        </div>

        <div class="form-group">
          <label>Numéro Carte:</label>
          <input type="text" name="numero_carte" id="editNumCarte" class="form-control" >
        </div>

        <div class="form-group">
          <label>Date Expiration:</label>
          <input type="text" name="date_expiration" id="editDateExp" placeholder="MM/AA" class="form-control" >
        </div>

        <div class="form-group">
          <label>CVV:</label>
          <input type="text" name="cvv" id="editCvv" class="form-control" >
        </div>

        <div class="form-group">
          <button type="submit" name="modifier" class="btn-save">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('editModal');
      
      // Ouvrir le modal et pré-remplir les données
      document.querySelectorAll('.btn-modifier').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const cells = row.cells;
            
            if (cells.length >= 10) {
                document.getElementById('editId').value = cells[0].textContent.trim();
                document.getElementById('editIdu').value = cells[1].textContent.trim();
                document.getElementById('editMontant').value = parseFloat(cells[2].textContent.replace(/,/g, '')) || 0;
                document.getElementById('editDate').value = cells[3].textContent.trim();
                document.getElementById('editType').value = cells[4].textContent.trim();
                document.getElementById('editStatut').value = cells[5].textContent.trim();
                document.getElementById('editNomCarte').value = cells[6].textContent.trim();
                document.getElementById('editNumCarte').value = cells[7].textContent.trim();
                document.getElementById('editDateExp').value = cells[8].textContent.trim();
                document.getElementById('editCvv').value = cells[9].textContent.trim();
                
                modal.style.display = 'block';
            }
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