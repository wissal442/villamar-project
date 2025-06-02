<?php include '../includes/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Contactez nous</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-info p-4 rounded" style="background-color: var(--light);">
                                <h4 class="text-secondary mb-4"><i class="fas fa-info-circle me-2"></i>Informations</h4>
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                        <strong>Adresse :</strong> 34 avenue Oqbah , appt. 2, Agdal Rabat - Maroc

                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-phone text-secondary me-2"></i>
                                        <strong>Téléphone :</strong> +212 6 61 11 75 71
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-envelope text-secondary me-2"></i>
                                        <strong>Email :</strong> contact@villamar.ma
                                                                 Villamar.Rabat@gmail.com
                                    </li>
                                </ul>
                                <hr>
                                <h5 class="text-secondary mt-4"><i class="fas fa-clock me-2"></i>Horaires</h5>
                                <p>Lundi - Vendredi : 9h - 18h<br>Samedi : 9h - 13h</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <form class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Votre nom</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Sujet</label>
                                    <select class="form-select" id="subject" required>
                                        <option value="">Choisir...</option>
                                        <option>Information sur un bien</option>
                                        <option>Rendez-vous</option>
                                        <option>Autre demande</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/contact.js"></script>

<?php include '../includes/footer.php'; ?>