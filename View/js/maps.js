// Version améliorée de js/maps.js
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que Leaflet est chargé
    if (typeof L === 'undefined') {
        console.error('Leaflet non chargé!');
        return;
    }

    // Délai minimum entre les requêtes (1000ms = 1s)
    const GEOCODE_DELAY = 1000;
    let lastGeocodeTime = 0;

    async function geocodeWithDelay(ville) {
        const now = Date.now();
        const waitTime = lastGeocodeTime + GEOCODE_DELAY - now;
        
        if (waitTime > 0) {
            await new Promise(resolve => setTimeout(resolve, waitTime));
        }
        
        lastGeocodeTime = Date.now();
        return geocodeVille(ville);
    }

    async function geocodeVille(ville) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(ville)}&countrycodes=tn&limit=1`,
                {
                    headers: {
                        'User-Agent': 'HezniCovoiturage/1.0 (contact@hezni.tn)'
                    }
                }
            );
            
            if (!response.ok) throw new Error('Erreur réseau');
            
            const data = await response.json();
            return data.length > 0 ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : null;
        } catch (error) {
            console.error(`Erreur géocodage ${ville}:`, error);
            return null;
        }
    }

    async function initMap(mapContainer) {
        const id = mapContainer.id;
        const villeDep = mapContainer.dataset.depart;
        const villeArr = mapContainer.dataset.arrivee;
        
        try {
            // Géocodage avec délai
            const [depCoords, arrCoords] = await Promise.all([
                geocodeWithDelay(villeDep),
                geocodeWithDelay(villeArr)
            ]);
            
            // Coordonnées par défaut si échec
            const defaultCoords = [36.8065, 10.1815]; // Tunis
            const finalDep = depCoords || defaultCoords;
            const finalArr = arrCoords || defaultCoords;
            
            // Créer la carte
            const map = L.map(id, {
                zoomControl: false,
                fadeAnimation: false
            }).fitBounds([finalDep, finalArr]);
            
            // Style de base
            map.attributionControl.setPrefix('');
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 18
            }).addTo(map);
            
            // Marqueurs avec icônes personnalisées
            const depIcon = L.divIcon({
                className: 'custom-marker departure',
                html: '<i class="fas fa-map-marker-alt"></i>',
                iconSize: [30, 30]
            });
            
            const arrIcon = L.divIcon({
                className: 'custom-marker arrival',
                html: '<i class="fas fa-map-marker-alt"></i>',
                iconSize: [30, 30]
            });
            
            L.marker(finalDep, { icon: depIcon })
                .addTo(map)
                .bindPopup(`<b>Départ:</b> ${villeDep}`);
                
            L.marker(finalArr, { icon: arrIcon })
                .addTo(map)
                .bindPopup(`<b>Arrivée:</b> ${villeArr}`);
                
            // Ligne de trajet
            if (finalDep && finalArr) {
                L.polyline([finalDep, finalArr], {
                    color: '#e53935',
                    weight: 3,
                    dashArray: '5, 5'
                }).addTo(map);
            }
            
        } catch (error) {
            console.error(`Erreur initialisation carte ${id}:`, error);
            // Fallback simple si erreur
            const fallbackMap = L.map(id).setView([36.8, 10.1], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(fallbackMap);
        }
    }

    // Initialiser les cartes une par une avec délai
    const maps = Array.from(document.querySelectorAll('.map-container'));
    let delay = 0;
    
    maps.forEach((mapContainer, index) => {
        setTimeout(() => {
            initMap(mapContainer);
        }, delay);
        
        delay += 500; // 500ms entre chaque initialisation
    });
});