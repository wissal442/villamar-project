// assets/js/visitor.js
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des favoris
    document.querySelectorAll('.btn-favorite').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const adId = this.dataset.adId;
            
            if (!this.classList.contains('active')) {
                // Ajouter aux favoris
                fetch(`/api/add_favorite.php?ad_id=${adId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.innerHTML = '<i class="fas fa-heart"></i>';
                            this.classList.add('active');
                            showToast('Ajouté aux favoris');
                        }
                    });
            } else {
                // Retirer des favoris
                fetch(`/api/remove_favorite.php?ad_id=${adId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.innerHTML = '<i class="far fa-heart"></i>';
                            this.classList.remove('active');
                            showToast('Retiré des favoris');
                        }
                    });
            }
        });
    });
    
    // Animation au scroll
    const animateOnScroll = () => {
        const cards = document.querySelectorAll('.property-card');
        cards.forEach(card => {
            const cardPosition = card.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (cardPosition < screenPosition) {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Initial setup
    document.querySelectorAll('.property-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
    });
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Trigger on load
    
    // Fonction pour afficher les notifications
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-check-circle me-2"></i>
                ${message}
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }, 100);
    }
});