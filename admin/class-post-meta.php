<?php
namespace SEO_Meta_Manager\Admin;

class Post_Meta {
    public function __construct() {
        $post_types = get_option('seo_meta_post_types', ['post', 'page']);
        
        foreach ($post_types as $post_type) {
            add_action("add_meta_boxes_{$post_type}", [$this, 'add_meta_box']);
            add_action("save_post_{$post_type}", [$this, 'save_meta'], 10, 2);
        }
        
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }
    
    public function add_meta_box($post) {
        add_meta_box(
            'seo_meta_manager_meta_box',
            __('SEO Meta Tags', 'seo-meta-manager'),
            [$this, 'render_meta_box'],
            null,
            'normal',
            'high'
        );
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('seo_meta_manager_save_meta', 'seo_meta_manager_nonce');
        
        $meta_title = get_post_meta($post->ID, '_seo_meta_title', true);
        $meta_description = get_post_meta($post->ID, '_seo_meta_description', true);
        
        ?>
        <div class="seo-meta-manager-fields">
            <div class="seo-meta-field">
                <label for="seo_meta_title">
                    <strong><?php esc_html_e('Meta Title', 'seo-meta-manager'); ?></strong>
                </label>
                <input type="text" id="seo_meta_title" name="seo_meta_title" 
                       value="<?php echo esc_attr($meta_title); ?>" 
                       class="widefat seo-meta-title-field"
                       data-optimal-min="50" data-optimal-max="60" 
                       data-warning-min="40" data-warning-max="70">
                <div class="seo-meta-counter">
                    <span class="character-count">0</span>/<span class="optimal-max">60</span>
                    <div class="progress-bar"></div>
                    <p class="description">
                        <?php esc_html_e('Recommended length: 50-60 characters', 'seo-meta-manager'); ?>
                    </p>
                </div>
            </div>
            
            <div class="seo-meta-field">
                <label for="seo_meta_description">
                    <strong><?php esc_html_e('Meta Description', 'seo-meta-manager'); ?></strong>
                </label>
                <textarea id="seo_meta_description" name="seo_meta_description" 
                          class="widefat seo-meta-description-field"
                          rows="3"
                          data-optimal-min="150" data-optimal-max="160" 
                          data-warning-min="130" data-warning-max="170"><?php 
                          echo esc_textarea($meta_description); 
                          ?></textarea>
                <div class="seo-meta-counter">
                    <span class="character-count">0</span>/<span class="optimal-max">160</span>
                    <div class="progress-bar"></div>
                    <p class="description">
                        <?php esc_html_e('Recommended length: 150-160 characters', 'seo-meta-manager'); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function save_meta($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['seo_meta_manager_nonce']) || 
            !wp_verify_nonce($_POST['seo_meta_manager_nonce'], 'seo_meta_manager_save_meta')) {
            return;
        }
        
        // Check user capabilities
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save meta title
        if (isset($_POST['seo_meta_title'])) {
            $meta_title = Validator::sanitize_meta_title($_POST['seo_meta_title']);
            update_post_meta($post_id, '_seo_meta_title', $meta_title);
        }
        
        // Save meta description
        if (isset($_POST['seo_meta_description'])) {
            $meta_description = Validator::sanitize_meta_description($_POST['seo_meta_description']);
            update_post_meta($post_id, '_seo_meta_description', $meta_description);
        }
    }
    
    public function enqueue_assets($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }
        
        $screen = get_current_screen();
        $post_types = get_option('seo_meta_post_types', ['post', 'page']);
        
        if (!in_array($screen->post_type, $post_types)) {
            return;
        }
        
        wp_enqueue_style(
            'seo-meta-manager-admin',
            SEO_META_MANAGER_PLUGIN_URL . 'admin/assets/css/admin.css',
            [],
            SEO_META_MANAGER_VERSION
        );
        
        wp_enqueue_script(
            'seo-meta-manager-character-counter',
            SEO_META_MANAGER_PLUGIN_URL . 'admin/assets/js/character-counter.js',
            ['jquery'],
            SEO_META_MANAGER_VERSION,
            true
        );
        
        wp_localize_script(
            'seo-meta-manager-character-counter',
            'seoMetaManager',
            [
                'colors' => [
                    'success' => '#46b450',
                    'warning' => '#ffb900',
                    'error' => '#dc3232'
                ]
            ]
        );
    }
}