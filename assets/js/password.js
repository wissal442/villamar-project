// Validation du formulaire de réinitialisation
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.querySelector('form[action*="reset_password"]');
    
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            const password = resetForm.querySelector('input[name="password"]');
            const confirmPassword = resetForm.querySelector('input[name="confirm_password"]');
            let isValid = true;

            // Vérification force du mot de passe
            if (password.value.length < 8) {
                password.classList.add('is-invalid');
                showToast('Le mot de passe doit contenir au moins 8 caractères', 'error');
                isValid = false;
            }

            // Vérification correspondance
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                showToast('Les mots de passe ne correspondent pas', 'error');
                isValid = false;
            }

            if (!isValid) e.preventDefault();
        });

        // Afficher/masquer le mot de passe
        const togglePassword = document.createElement('span');
        togglePassword.className = 'password-toggle';
        togglePassword.innerHTML = '<i class="far fa-eye"></i>';
        togglePassword.style.position = 'absolute';
        togglePassword.style.right = '10px';
        togglePassword.style.top = '50%';
        togglePassword.style.transform = 'translateY(-50%)';
        togglePassword.style.cursor = 'pointer';
        
        const passwordField = resetForm.querySelector('input[name="password"]');
        passwordField.parentNode.style.position = 'relative';
        passwordField.parentNode.appendChild(togglePassword);

        togglePassword.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }
});