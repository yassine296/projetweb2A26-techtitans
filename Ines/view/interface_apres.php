<!DOCTYPE html>
<html lang="fr">

<head>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <meta charset="UTF-8">
  <title>Hezni - Covoiturage Étudiants</title>
  <link rel="stylesheet" href="interface_apres.css">

</head>

<body>

  <!-- En-tête -->
  <header>
    <div class="badge">TUNIS</div>
    <div class="profile-menu">
            <button class="profile-btn">
                <img src="https://via.placeholder.com/40" alt="Photo de profil" class="profile-img">
                <span>Mon Profil</span>
            </button>
            <div class="dropdown-content">
                <div class="dropdown-header">Mon Compte</div>
                <a href="#"><i class="fas fa-user"></i> Profil</a>
                <a href="#"><i class="fas fa-cog"></i> Paramètres</a>
                <a href="#"><i class="fas fa-envelope"></i> Messages</a>
                <div class="dropdown-divider"></div>
                <a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
            <!-- <div class="profile-menu">
            <img src="profil.jpg" class="profile-img" alt="Photo de profil">
            <div class="dropdown-content">
            <div class="dropdown-header">
            <?php echo htmlspecialchars($nom . ' ' . $prenom); ?>
          </div>
        <a href="#">Rôle : <?php echo htmlspecialchars($role); ?></a>
        <div class="dropdown-divider"></div>
        <a href="logout.php">Déconnexion</a>
    </div> -->
</div>
        </div>
    </header>
  </header>

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
  
  <!-- Contenu principal -->
  <div class="container">
    <div class="text-section">
      <!-- <h1>Projet développement web</h1> -->
      <h2>
      Hezni, Le premier site de covoiturage <span class="changing-word">en Tunisie</span>
      </h2>      
      <script>
      //const words = ["en Tunisie", "à Tunis", "en Afrique", "dans le Maghreb", "avec style"];
        const words = [
        "en Tunisie",
        "in Tunisia",
        "في تونس",
        "à Tunis",
        "in Tunesien",
        "en Túnez",
        "na Tunísia",
        "Тунис",
        "チュニジアで",
        "突尼斯"
      ];
      const wordSpan = document.querySelector(".changing-word");
      let index = 0;

        function changeWord() {
          wordSpan.classList.remove("fade-in");
          setTimeout(() => {
            index = (index + 1) % words.length;
            wordSpan.textContent = words[index];
            wordSpan.classList.add("fade-in");
          }, 300); // petite pause pour que l'effet de fondu fonctionne
        }

        // Première apparition
        wordSpan.classList.add("fade-in");

        // Lancement de l'intervalle
        setInterval(changeWord, 3000);

    </script>
    </div>

    <div class="divider"></div>

    <div class="logo-section">
      <img src="lego.png" alt="Logo Hezni">
    </div>
  </div>

  <!-- Pied de page -->
  <footer>
    © www.hezni.tn | BY TECHTITANS.
  </footer>

</body>

</html>
