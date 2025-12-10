/**
 * Gestion de l'aperçu des fichiers uploadés
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gérer les inputs de fichiers images (photo de profil, bannière)
    // Exclure les inputs de la page réglages qui ont déjà leur propre système d'aperçu
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]:not(#profile-photo-input):not(#profile-banner-input)');
    
    imageInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const label = input.closest('label');
                
                reader.onload = function(e) {
                    // Supprimer l'aperçu précédent s'il existe
                    let oldPreview = label.querySelector('.file-preview');
                    if (oldPreview) {
                        oldPreview.remove();
                    }
                    
                    // Créer le nouvel aperçu
                    let preview = document.createElement('div');
                    preview.className = 'file-preview';
                    
                    // Créer l'image d'aperçu
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Aperçu';
                    preview.appendChild(img);
                    
                    // Afficher le nom du fichier
                    let fileName = document.createElement('span');
                    fileName.className = 'file-name';
                    fileName.textContent = file.name;
                    preview.appendChild(fileName);
                    
                    // Ajouter l'aperçu après le label
                    label.parentNode.insertBefore(preview, label.nextSibling);
                    
                    // Ajouter une classe pour indiquer qu'un fichier est sélectionné
                    label.classList.add('file-selected');
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
    
    // Gérer les inputs de fichiers audio (sons)
    const audioInputs = document.querySelectorAll('input[type="file"][accept*="audio"]');
    
    audioInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const label = input.closest('label');
                
                // Supprimer l'aperçu précédent s'il existe
                let oldPreview = label.parentNode.querySelector('.file-preview');
                if (oldPreview) {
                    oldPreview.remove();
                }
                
                // Créer le nouvel aperçu
                let preview = document.createElement('div');
                preview.className = 'file-preview';
                
                // Afficher la liste des fichiers
                const fileList = document.createElement('div');
                fileList.className = 'file-list';
                
                Array.from(files).forEach(function(file) {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    
                    const icon = document.createElement('span');
                    icon.className = 'file-icon';
                    icon.textContent = '🎵';
                    
                    const fileName = document.createElement('span');
                    fileName.className = 'file-name';
                    fileName.textContent = file.name;
                    
                    fileItem.appendChild(icon);
                    fileItem.appendChild(fileName);
                    fileList.appendChild(fileItem);
                });
                
                preview.appendChild(fileList);
                
                // Ajouter l'aperçu après le label
                label.parentNode.insertBefore(preview, label.nextSibling);
                
                // Ajouter une classe pour indiquer qu'un fichier est sélectionné
                label.classList.add('file-selected');
            }
        });
    });
});

