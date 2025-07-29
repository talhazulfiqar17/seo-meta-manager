<?php
namespace SEO_Meta_Manager;

class Ajax {
    public function __construct() {
        add_action('wp_ajax_seo_meta_manager_preview', [$this, 'handle_preview_request']);
    }
    
    public function handle_preview_request() {
        check_ajax_referer('seo_meta_manager_preview', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have permission to do this.', 'seo-meta-manager'));
        }
        
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
        
        $posts = get_posts([
            'post_type' => $post_type,
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        
        if (empty($posts)) {
            wp_send_json_error(__('No posts found for preview.', 'seo-meta-manager'));
        }
        
        $post = $posts[0];
        $meta_title = get_post_meta($post->ID, '_seo_meta_title', true);
        $meta_description = get_post_meta($post->ID, '_seo_meta_description', true);
        
        ob_start();
        ?>
        <div class="seo-meta-preview">
            <h3><?php echo esc_html($post->post_title); ?></h3>
            <div class="preview-meta-title">
                <strong><?php esc_html_e('Meta Title:', 'seo-meta-manager'); ?></strong>
                <span><?php echo $meta_title ? esc_html($meta_title) : esc_html__('Not set', 'seo-meta-manager'); ?></span>
            </div>
            <div class="preview-meta-description">
                <strong><?php esc_html_e('Meta Description:', 'seo-meta-manager'); ?></strong>
                <span><?php echo $meta_description ? esc_html($meta_description) : esc_html__('Not set', 'seo-meta-manager'); ?></span>
            </div>
        </div>
        <?php
        
        wp_send_json_success(ob_get_clean());
    }
}