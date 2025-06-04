<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-envelope me-2"></i>Contacter l'annonceur</h5>
    </div>
    <div class="card-body">
        <form id="contactForm" method="POST">
            <input type="hidden" name="ad_id" value="<?= $ad['id'] ?>">
            <input type="hidden" name="recipient_id" value="<?= $ad['user_id'] ?>">
            
            <div class="mb-3">
                <textarea class="form-control" name="message" rows="4" 
                          placeholder="Votre message..." required></textarea>
            </div>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="mb-3">
                <input type="email" class="form-control" name="guest_email" 
                       placeholder="Votre email" required>
            </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/actions/send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Message envoyé avec succès');
            this.reset();
        } else {
            alert('Erreur: ' + data.error);
        }
    });
});
</script>