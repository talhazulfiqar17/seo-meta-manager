<?php
namespace SEO_Meta_Manager\Admin;

class Settings {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'add_settings_page']);
    }
    
    public function add_settings_page() {
        add_options_page(
            __('SEO Meta Manager Settings', 'seo-meta-manager'),
            __('SEO Meta', 'seo-meta-manager'),
            'manage_options',
            'seo-meta-manager',
            [$this, 'render_settings_page']
        );
    }
    
    public function register_settings() {
        register_setting(
            'seo_meta_manager_settings',
            'seo_meta_post_types',
            [
                'type' => 'array',
                'sanitize_callback' => ['SEO_Meta_Manager\Validator', 'sanitize_post_types'],
                'default' => ['post', 'page']
            ]
        );
        
        register_setting(
            'seo_meta_manager_settings',
            'seo_meta_taxonomies',
            [
                'type' => 'array',
                'sanitize_callback' => ['SEO_Meta_Manager\Validator', 'sanitize_taxonomies'],
                'default' => ['category']
            ]
        );
        
        add_settings_section(
            'seo_meta_manager_section',
            __('Content Types', 'seo-meta-manager'),
            [$this, 'render_section'],
            'seo-meta-manager'
        );
        
        add_settings_field(
            'seo_meta_post_types',
            __('Post Types', 'seo-meta-manager'),
            [$this, 'render_post_types_field'],
            'seo-meta-manager',
            'seo_meta_manager_section'
        );
        
        add_settings_field(
            'seo_meta_taxonomies',
            __('Taxonomies', 'seo-meta-manager'),
            [$this, 'render_taxonomies_field'],
            'seo-meta-manager',
            'seo_meta_manager_section'
        );
    }
    
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('seo_meta_manager_settings');
                do_settings_sections('seo-meta-manager');
                submit_button(__('Save Settings', 'seo-meta-manager'));
                ?>
            </form>
        </div>
        <?php
    }
    
    public function render_section() {
        echo '<p>' . esc_html__('Select which post types and taxonomies should have SEO meta fields.', 'seo-meta-manager') . '</p>';
    }
    
    public function render_post_types_field() {
        $selected = get_option('seo_meta_post_types', ['post', 'page']);
        $post_types = get_post_types(['public' => true], 'objects');
        
        echo '<fieldset>';
        foreach ($post_types as $post_type) {
            $checked = in_array($post_type->name, $selected) ? 'checked="checked"' : '';
            echo sprintf(
                '<label><input type="checkbox" name="seo_meta_post_types[]" value="%s" %s> %s</label><br>',
                esc_attr($post_type->name),
                $checked,
                esc_html($post_type->label)
            );
        }
        echo '</fieldset>';
    }
    
    public function render_taxonomies_field() {
        $selected = get_option('seo_meta_taxonomies', ['category']);
        $taxonomies = get_taxonomies(['public' => true], 'objects');
        
        echo '<fieldset>';
        foreach ($taxonomies as $taxonomy) {
            $checked = in_array($taxonomy->name, $selected) ? 'checked="checked"' : '';
            echo sprintf(
                '<label><input type="checkbox" name="seo_meta_taxonomies[]" value="%s" %s> %s</label><br>',
                esc_attr($taxonomy->name),
                $checked,
                esc_html($taxonomy->label)
            );
        }
        echo '</fieldset>';
    }
}