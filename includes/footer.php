<footer class="footer mt-5" style="background-color: var(--primary); color: white;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4><i class="fas fa-leaf me-2"></i>villamar Immobilier</h4>
                <p>Trouvez votre havre de paix dans des propriétés qui respectent la nature.</p>
                <div class="social-icons">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Liens rapides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-white">Accueil</a></li>
                    <li class="mb-2"><a href="annonces.php" class="text-white">Nos biens</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-white">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Contactez-nous</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 34 avenue Oqbah , appt. 2, Agdal Rabat - Maroc
</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> +212 6 61 11 75 71</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i>contact@villamar.ma
Villamar.Rabat@gmail.com</li>
                </ul>
            </div>
            
            <div class="col-lg-3 mb-4">
                <h5>

                </h5>

                <p>
                    Abonnez-vous pour recevoir nos dernières offres</p>
                <form class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre email">
                        <button class="btn btn-accent" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="text-center py-3" style="background-color: rgba(0,0,0,0.1);">
        <p class="mb-0">&copy; <?= date('Y') ?> Villamar Immobilier. Tous droits réservés.</p>
    </div>
</footer>

<!-- Dans footer.php -->
<script src="/assets/js/filters.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Activer les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
</body>
</html>