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

