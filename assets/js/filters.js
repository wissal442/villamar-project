// assets/js/filters.js
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du changement de pays
    document.querySelectorAll('.country-selector').forEach(select => {
        select.addEventListener('change', function() {
            const countryCode = this.value;
            if (countryCode) {
                fetch(`/actions/set_country.php?code=${countryCode}`)
                    .then(() => window.location.reload());
            }
        });
    });
    
    // Mise à jour automatique des filtres
    const filterForm = document.querySelector('.filter-form');
    if (filterForm) {
        filterForm.querySelectorAll('select, input').forEach(element => {
            element.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
});