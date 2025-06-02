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