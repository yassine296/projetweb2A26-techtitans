<!DOCTYPE html>
<html lang="fr">

<head>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <meta charset="UTF-8">
  <title>Hezni - Covoiturage Étudiants</title>
  <link rel="stylesheet" href="interface_init.css">

</head>

<body>

  <!-- En-tête -->
  <header>
    <div class="badge">TUNIS</div>
    <nav>
      <a href="#">ACCUEIL</a>
      <a href="#a-propos">A PROPOS</a>
      <a href="#">CONTACTEZ-NOUS</a>
      <a href="inscri.php">S'INSCRIRE</a>
      <a href="connect.php">SE CONNECTER</a>
    </nav>
  </header>

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


  <!-- les sections -->
   <!-- <section class="about-section">
      <div class="section-content">
        <div class="about-image_wrapper">
          <img src="lego.png" alt="About" class="about-image">
        </div>
        <div class="about-details">
        <h2 class="section-title"> A propos de nous</h2>
        <p class="text"> Imaginez une plateforme de covoiturage étudiant qui ne se contente pas seulement d’organiser des trajets, mais qui les optimise intelligemment pour être plus rapides, plus sûrs et plus respectueux de l’environnement.
        </p>
        </div>
      </div>
      </section> -->

      <section id="a-propos" class="section-apropos">
  <div class="content-container">
  <!-- <h1 class=" text-apropos"> 🚗 . À propos de nous</h1>
  <br> </br>
  <br> </br> -->


    <div class="logo-apropos">
      <img src="logoo.png" alt="Logo Hezni">
    </div>
    <div class="text-apropos">
      <h2>Bienvenue sur Hezni</h2>
      <p>Hezni est la solution de covoiturage dédiée aux étudiants. Facile, rapide et sécurisé !</p>
    </div>
  </div>
</section>




  <!-- Pied de page -->
  <footer>
    © www.hezni.tn | BY TECHTITANS.
  </footer>

</body>

</html>
