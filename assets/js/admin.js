// admin/assets/js/admin.js
// Confirmation avant suppression
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', (e) => {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            e.preventDefault();
        }
    });
});

// admin/assets/js/admin.js

// Confirmation avant actions importantes
document.querySelectorAll('.btn-delete, .btn-mark-read').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Confirmer cette action ?')) {
            e.preventDefault();
        }
    });
});

// Affichage dynamique des messages
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Gestion des succès après redirection
if (window.location.search.includes('success=1')) {
    showToast('Opération réussie !');
}

// admin/assets/js/admin.js

// Gestion des dates de réservation
document.querySelectorAll('.reservation-dates input[type="date"]').forEach(input => {
    input.addEventListener('change', function() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);
        
        if (startDate && endDate && startDate > endDate) {
            alert("La date de fin doit être après la date de début");
            this.value = '';
        }
    });
});

// user/assets/js/user.js

// Affichage dynamique des statistiques
function updateUserStats() {
    fetch('/api/user/stats')
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.stat-card p').forEach((el, index) => {
                const key = Object.keys(data)[index];
                if (data[key] !== undefined) {
                    el.textContent = data[key];
                }
            });
        });
}

// Actualiser toutes les 30 secondes
setInterval(updateUserStats, 30000);