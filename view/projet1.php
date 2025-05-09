<?php  
require_once '../controller/messageController.php';
?>

<!DOCTYPE html> 
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Interface de Chat</title>
  <link rel="stylesheet" href="../view/style.css"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <header>
    <div class="logo">TUNIS</div>
    <nav>
      <a href="#">HOME</a>
      <a href="#">ABOUT</a>
      <a href="#">CONTENT</a>
      <a href="#">OTHERS</a>
      <a href="#">OTHERS</a>
    </nav>
    <button id="themeToggle" class="theme-toggle" aria-pressed="false" aria-label="Toggle dark mode">Mode Sombre</button>
  </header>

  <div class="container-wrapper">
    <div class="container">
      <div class="contacts">
        <div class="contacts-header">
          <span>Contacts</span>
        </div>
        <div class="contacts-list">
          <p class="contact"><strong>Yassine Zariat</strong><br>Dernier message...</p>
          <p class="contact"><strong>Ines Borguiba</strong><br>Dernier message...</p>
          <p class="contact"><strong>Sarra Ben Boubaker</strong><br>Dernier message...</p>
          <p class="contact"><strong>Zeineb Ben Regaya</strong><br>Dernier message...</p>
          <p class="contact"><strong>Dali Ghrissi</strong><br>Dernier message...</p>
          <p class="contact"><strong>Emna Mejri</strong><br>Dernier message...</p>
          <p class="contact"><strong>Emna Gassem</strong><br>Dernier message...</p>
          <p class="contact"><strong>Amira Trabelsi</strong><br>Dernier message...</p>
          <p class="contact"><strong>Ali Gharbi</strong><br>Dernier message...</p>
        </div>
      </div>

      <div class="chat">
        <div class="person-details">
          <span><strong>Bob</strong></span>
          <span><em>En ligne</em></span>
        </div>

        <div class="messages">
          <?php foreach ($messages as $message): 
              $classe = ($message['id_expediteur'] == $id_utilisateur_actuel) ? 'from-me' : 'from-them';
          ?>
              <div class="message <?= $classe ?>" data-id="<?= $message['id_message'] ?>">
                <div class="message-content"><?= htmlspecialchars($message['contenu']) ?></div>

                <!-- Formulaire de modification -->
                <form class="edit-form" style="display: none;" method="POST" action="../controller/messageController.php">
                  <input type="hidden" name="id_message" value="<?= $message['id_message'] ?>">
                  <input type="text" name="nouveau_contenu" class="edit-input" value="<?= htmlspecialchars($message['contenu']) ?>">
                  <button type="submit" name="action" value="modifier">Valider</button>
                  <button type="button" class="cancel-edit">Annuler</button>
                </form>

                <span class="menu">⋮
                  <div class="menu-options">
                    <button class="edit-btn">Modifier</button>
                    <form method="POST" action="../controller/messageController.php" style="display:inline;">
                      <input type="hidden" name="id_message" value="<?= $message['id_message'] ?>">
                      <button type="submit" name="action" value="supprimer">Supprimer</button>
                    </form>
                  </div>
                </span>
              </div>
          <?php endforeach; ?>
        </div>

        <!-- Indicateur "en train d'écrire" -->
        <div id="typing-indicator" style="display: none; font-style: italic; color: gray; padding: 5px 15px;">L'utilisateur est en train d'écrire...</div>

        <!-- Picker d'émojis -->
        <div class="emoji-picker" style="display: none;">
          <?php 
          $emojis = ["😀", "😂", "😍", "🤔", "👍", "❤️", "🔥", "🎉", "🙏", "😊", "😎", "🥳", "🤩", "😢", "😡"];
          foreach ($emojis as $emoji): 
          ?>
              <span class="emoji-option"><?= $emoji ?></span>
          <?php endforeach; ?>
        </div>

        <!-- Formulaire d'envoi -->
        <form class="input-area" id="messageForm" method="POST" action="../controller/messageController.php">
          <input type="hidden" name="id_expediteur" value="<?= $id_utilisateur_actuel ?>">
          <input type="hidden" name="id_destinataire" value="<?= $id_destinataire ?>">
          <label for="messageInput" class="sr-only">Message</label>
          <input id="messageInput" name="contenu" type="text" placeholder="Écris ton message..." required aria-required="true">
          <button type="button" id="emojiToggle" class="emoji-btn" aria-label="Choisir un émoji">😊</button>
          <button type="submit" class="send-btn" aria-label="Envoyer le message">➜</button>
        </form>
        <div id="errorMsg"></div>
      </div>
    </div>
  </div>

  <footer>
    <div>WWW.HEZNI.TN</div>
    <div>BY TECHTITANS</div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('themeToggle');
      const savedTheme = localStorage.getItem('theme');
      const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

      if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        enableDarkMode();
      }

      themeToggle.addEventListener('click', function() {
        if (document.body.classList.contains('dark-mode')) {
          disableDarkMode();
        } else {
          enableDarkMode();
        }
      });

      function enableDarkMode() {
        document.body.classList.add('dark-mode');
        themeToggle.textContent = 'Mode Clair';
        localStorage.setItem('theme', 'dark');
        themeToggle.setAttribute('aria-pressed', 'true');
      }

      function disableDarkMode() {
        document.body.classList.remove('dark-mode');
        themeToggle.textContent = 'Mode Sombre';
        localStorage.setItem('theme', 'light');
        themeToggle.setAttribute('aria-pressed', 'false');
      }

      // Effet "en train d'écrire"
      const messageInput = document.getElementById("messageInput");
      const typingIndicator = document.getElementById("typing-indicator");
      let typingTimer;

      messageInput.addEventListener("input", () => {
        typingIndicator.style.display = "block";
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
          typingIndicator.style.display = "none";
        }, 1500);
      });

      // Gestion des émojis
      const emojiToggle = document.getElementById('emojiToggle');
      const emojiPicker = document.querySelector('.emoji-picker');
      const emojiOptions = document.querySelectorAll('.emoji-option');

      emojiToggle.addEventListener('click', function(e) {
        e.preventDefault();
        emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
      });

      emojiOptions.forEach(emoji => {
        emoji.addEventListener('click', function() {
          messageInput.value += this.textContent;
          messageInput.focus();
          emojiPicker.style.display = 'none';
        });
      });

      // Fermer le picker quand on clique ailleurs
      document.addEventListener('click', function(e) {
        if (!emojiToggle.contains(e.target) && !emojiPicker.contains(e.target)) {
          emojiPicker.style.display = 'none';
        }
      });

      // Validation message
      document.getElementById("messageForm").addEventListener("submit", function(e) {
        const input = document.getElementById("messageInput");
        const errorMsg = document.getElementById("errorMsg");
        const contenu = input.value.trim();

        if (contenu === "") {
          e.preventDefault();
          errorMsg.textContent = "❌ Tu ne peux pas envoyer un message vide.";
        } else {
          errorMsg.textContent = "";
          setTimeout(() => {
            input.value = "";
            scrollToBottom();
          }, 100);
        }
      });

      // Edit message
      document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const messageDiv = btn.closest('.message');
          messageDiv.querySelector('.message-content').style.display = 'none';
          messageDiv.querySelector('.edit-form').style.display = 'block';
        });
      });

      document.querySelectorAll('.cancel-edit').forEach(btn => {
        btn.addEventListener('click', function() {
          const messageDiv = btn.closest('.message');
          messageDiv.querySelector('.edit-form').style.display = 'none';
          messageDiv.querySelector('.message-content').style.display = 'block';
        });
      });

      function scrollToBottom() {
        const messages = document.querySelector('.messages');
        messages.scrollTo({
          top: messages.scrollHeight,
          behavior: 'smooth'
        });
      }

      scrollToBottom();
    });
  </script>
</body>
</html>