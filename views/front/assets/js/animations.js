document.addEventListener('DOMContentLoaded', function() {
    // Animation des éléments au défilement
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.form-container, .table-container, .section-header');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                if (!element.classList.contains('animate-fadeIn') && 
                    !element.classList.contains('animate-slideInLeft') && 
                    !element.classList.contains('animate-slideInRight') && 
                    !element.classList.contains('animate-slideInTop') && 
                    !element.classList.contains('animate-slideInBottom')) {
                    element.classList.add('animate-fadeIn');
                }
            }
        });
    };
    
    // Exécuter l'animation au chargement et au défilement
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
    
    // Animation des boutons
    const buttons = document.querySelectorAll('button, .action-btn, .back-button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
    
    // Animation du logo
    const logo = document.querySelector('.logo');
    if (logo) {
        logo.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        logo.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
    
    // Animation des lignes du tableau
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        // Ajouter un délai progressif pour l'apparition des lignes
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        row.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, 100 * index);
    });
    
    // Animation des alertes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.animation = 'slideInFromTop 0.5s ease-out';
        
        // Faire disparaître l'alerte après 5 secondes
        setTimeout(() => {
            alert.style.animation = 'slideOutToTop 0.5s ease-in forwards';
        }, 5000);
    });
    
    // Animation de la vague
    const wave = document.querySelector('.wave-bottom path');
    if (wave) {
        let waveAnimation = function() {
            const d = wave.getAttribute('d');
            const newD = d.replace(/C\d+,\d+\s+\d+,-\d+\s+\d+,\d+/, function(match) {
                const values = match.split(/[C,\s]+/).filter(Boolean);
                const newValues = values.map(val => {
                    // Ajouter une légère variation aléatoire
                    if (val.includes('-')) {
                        return parseInt(val) - Math.floor(Math.random() * 20);
                    } else {
                        return parseInt(val) + Math.floor(Math.random() * 20 - 10);
                    }
                });
                return `C${newValues[0]},${newValues[1]} ${newValues[2]},${newValues[3]} ${newValues[4]},${newValues[5]}`;
            });
            wave.setAttribute('d', newD);
            
            // Répéter l'animation
            setTimeout(waveAnimation, 2000);
        };
        
        // Démarrer l'animation de la vague
        setTimeout(waveAnimation, 2000);
    }
});

// Ajouter une animation de chargement de page
window.addEventListener('load', function() {
    document.body.classList.add('page-loaded');
});

// Définir l'animation de chargement de page en CSS
document.head.insertAdjacentHTML('beforeend', `
<style>
    body {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    
    body.page-loaded {
        opacity: 1;
    }
    
    @keyframes slideOutToTop {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(-20px); opacity: 0; }
    }
</style>
`);
