<?php
/**
 * Template Name: Réglages Page
 * Template pour la page Réglages
 */

// Rediriger vers la connexion si l'utilisateur n'est pas connecté
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth-start'));
    exit;
}

get_header(); 

$current_user = wp_get_current_user();
$profile_photo = get_user_meta($current_user->ID, 'bebeats_profile_photo', true);
$banner = get_user_meta($current_user->ID, 'bebeats_banner', true);
$description = get_user_meta($current_user->ID, 'description', true);

// Si pas de photo de profil, utiliser l'avatar WordPress par défaut
if (empty($profile_photo)) {
    $profile_photo = get_avatar_url($current_user->ID, array('size' => 200));
}
?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Section Profil -->
        <section class="settings-panel glassmorphism">
            <h2 class="settings-section-title">Profil</h2>
            
            <?php if (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                <div class="settings-success-message">
                    Votre profil a été mis à jour avec succès !
                </div>
            <?php endif; ?>
            
            <form class="profile-settings-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="bebeats_update_profile">
                <?php wp_nonce_field('bebeats_update_profile_action', 'bebeats_update_profile_nonce'); ?>
                
                <div class="profile-settings-grid">
                    <!-- Photo de profil -->
                    <div class="profile-setting-item">
                        <label class="setting-label">Photo de profil</label>
                        <div class="profile-photo-preview-container">
                            <img src="<?php echo esc_url($profile_photo); ?>" alt="Photo de profil actuelle" class="profile-photo-preview" id="profile-photo-preview">
                        </div>
                        <label class="profile-file-btn">
                            <input type="file" name="profile_photo" accept="image/*" class="profile-file-input" id="profile-photo-input">
                            <span>Changer la photo</span>
                        </label>
                    </div>
                    
                    <!-- Bannière -->
                    <div class="profile-setting-item">
                        <label class="setting-label">Bannière</label>
                        <div class="profile-photo-preview-container">
                            <?php if (!empty($banner)): ?>
                                <img src="<?php echo esc_url($banner); ?>" alt="Bannière actuelle" class="profile-photo-preview" id="profile-banner-preview">
                            <?php else: ?>
                                <div class="profile-photo-placeholder" id="profile-banner-preview">
                                    <span>Aucune bannière</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <label class="profile-file-btn">
                            <input type="file" name="banner" accept="image/*" class="profile-file-input" id="profile-banner-input">
                            <span><?php echo !empty($banner) ? 'Changer la bannière' : 'Ajouter une bannière'; ?></span>
                        </label>
                    </div>
                    
                    <!-- Description -->
                    <div class="profile-setting-item profile-setting-item-full">
                        <label class="setting-label" for="profile-description">Description</label>
                        <textarea 
                            name="description" 
                            id="profile-description"
                            class="profile-description-input" 
                            rows="4"
                            placeholder="Écris une description de ton profil..."
                        ><?php echo esc_textarea($description); ?></textarea>
                    </div>
                </div>
                
                <button type="submit" class="profile-save-btn">Enregistrer les modifications</button>
            </form>
        </section>
        
        <!-- Section Paramètres généraux -->
        <section class="settings-panel glassmorphism">
            <h2 class="settings-section-title">Paramètres</h2>
            <div class="settings-grid">
                <div class="setting-item">
                    <label class="setting-label">Cookies</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="toggle-cookies" class="toggle-input">
                        <span class="toggle-slider">
                            <span class="toggle-checkmark">✓</span>
                            <span class="toggle-knob"></span>
                        </span>
                    </label>
                </div>
                
                <div class="setting-item">
                    <label class="setting-label">Langues</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="toggle-langues" class="toggle-input">
                        <span class="toggle-slider">
                            <span class="toggle-checkmark">✓</span>
                            <span class="toggle-knob"></span>
                        </span>
                    </label>
                </div>
                
                <div class="setting-item">
                    <label class="setting-label">Mode</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="toggle-mode" class="toggle-input">
                        <span class="toggle-slider">
                            <span class="toggle-checkmark">✓</span>
                            <span class="toggle-knob"></span>
                        </span>
                    </label>
                </div>
                
                <div class="setting-item">
                    <label class="setting-label">Repost</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="toggle-repost" class="toggle-input">
                        <span class="toggle-slider">
                            <span class="toggle-checkmark">✓</span>
                            <span class="toggle-knob"></span>
                        </span>
                    </label>
                </div>
            </div>
        </section>
        
        <!-- Section Déconnexion -->
        <section class="settings-panel glassmorphism">
            <h2 class="settings-section-title">Compte</h2>
            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="bebeats_logout">
                <?php wp_nonce_field('bebeats_logout_action', 'bebeats_logout_nonce'); ?>
                <button type="submit" class="profile-save-btn">
                    Se déconnecter
                </button>
            </form>
        </section>
    </main>

    <script>
    // Aperçu des images en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profile-photo-input');
        const bannerInput = document.getElementById('profile-banner-input');
        
        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('profile-photo-preview');
                        if (preview && preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        if (bannerInput) {
            bannerInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('profile-banner-preview');
                        if (preview) {
                            if (preview.tagName === 'IMG') {
                                preview.src = e.target.result;
                            } else {
                                // Remplacer le placeholder par une image
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = 'Bannière';
                                img.className = 'profile-photo-preview';
                                img.id = 'profile-banner-preview';
                                preview.parentNode.replaceChild(img, preview);
                            }
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    </script>

<?php get_footer(); ?>

