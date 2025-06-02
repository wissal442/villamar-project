document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adForm');
    const fileInput = document.getElementById('mainImage');
    const preview = document.getElementById('imagePreview');

    // Prévisualisation d'image
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail">`;
            }
            reader.readAsDataURL(file);
        }
    });

    // Soumission du formulaire
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publication...';

        try {
            // Upload de l'image
            const imageResponse = await fetch('api/upload.php', {
                method: 'POST',
                body: new FormData(document.getElementById('imageUploadForm'))
            });
            const imageData = await imageResponse.json();

            if (!imageData.success) throw new Error(imageData.error);

            // Création de l'annonce
            const adData = {
                title: formData.get('title'),
                description: formData.get('description'),
                type: formData.get('type'),
                price: parseFloat(formData.get('price')),
                location: formData.get('location'),
                surface: parseInt(formData.get('surface')),
                rooms: parseInt(formData.get('rooms')),
                main_image: imageData.filePath
            };

            const response = await fetch('api/ads/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(adData)
            });

            const result = await response.json();
            
            if (result.success) {
                window.location.href = `ads/detail.php?id=${result.ad_id}`;
            } else {
                showToast('Erreur: ' + result.error, 'error');
            }
        } catch (error) {
            showToast(error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Publier';
        }
    });
});