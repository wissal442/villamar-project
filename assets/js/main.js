// Fonctionnalités globales du site
document.addEventListener('DOMContentLoaded', function() {
    // ============ NAVBAR MOBILE ============
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
    }

    // ============ ANIMATION SCROLL ============
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // ============ TOOLTIPS ============
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover'
        });
    });

    // ============ COMPTEUR DE CARACTÈRES ============
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const counter = document.createElement('small');
        counter.className = 'text-muted d-block text-end';
        counter.textContent = `0/${textarea.maxLength}`;
        textarea.parentNode.appendChild(counter);

        textarea.addEventListener('input', function() {
            counter.textContent = `${this.value.length}/${this.maxLength}`;
            if (this.value.length > this.maxLength * 0.9) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        });
    });
});