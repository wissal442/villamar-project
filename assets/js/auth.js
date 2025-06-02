// Gestion des erreurs de formulaire
document.addEventListener('DOMContentLoaded', function() {
    // Afficher les erreurs serveur
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error) {
        showToast(error, 'error');
    }

    // Validation en temps réel
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validation mot de passe
            if (form.querySelector('input[name="password"]')) {
                const password = form.querySelector('input[name="password"]');
                if (password.value.length < 8) {
                    password.classList.add('is-invalid');
                    showToast('Le mot de passe doit contenir au moins 8 caractères', 'error');
                    isValid = false;
                }
            }
            
            // Validation confirmation mot de passe
            if (form.querySelector('input[name="confirm_password"]')) {
                const password = form.querySelector('input[name="password"]');
                const confirmPassword = form.querySelector('input[name="confirm_password"]');
                
                if (password.value !== confirmPassword.value) {
                    password.classList.add('is-invalid');
                    confirmPassword.classList.add('is-invalid');
                    showToast('Les mots de passe ne correspondent pas', 'error');
                    isValid = false;
                }
            }
            
            if (!isValid) e.preventDefault();
        });
    });
});

// Fonction pour basculer la visibilité du mot de passe
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Fonction pour afficher des notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }, 100);
    
}
function togglePassword(icon) {
    const input = icon.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}