<?php
namespace SEO_Meta_Manager;

class Core {
    private static $instance = null;
    
    private $admin;
    private $public;
    private $settings;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->load_dependencies();
        $this->init_components();
        $this->set_locale();
    }
    
    private function load_dependencies() {
        require_once SEO_META_MANAGER_PLUGIN_DIR . 'includes/class-validator.php';
        require_once SEO_META_MANAGER_PLUGIN_DIR . 'includes/class-i18n.php';
        require_once SEO_META_MANAGER_PLUGIN_DIR . 'includes/class-ajax.php';
        
        if (is_admin()) {
            require_once SEO_META_MANAGER_PLUGIN_DIR . 'admin/class-admin.php';
            require_once SEO_META_MANAGER_PLUGIN_DIR . 'admin/class-settings.php';
            require_once SEO_META_MANAGER_PLUGIN_DIR . 'admin/class-post-meta.php';
        }
        
        require_once SEO_META_MANAGER_PLUGIN_DIR . 'public/class-frontend.php';
    }
    
    private function init_components() {
        if (is_admin()) {
            $this->admin = new Admin\Admin();
            $this->settings = new Admin\Settings();
            new Admin\Post_Meta();
        }
        
        $this->public = new Frontend\Frontend();
    }
    
    private function set_locale() {
        $i18n = new i18n();
        $i18n->load_plugin_textdomain();
    }
    
    public static function activate() {
        // Set default options
        if (!get_option('seo_meta_post_types')) {
            update_option('seo_meta_post_types', ['post', 'page']);
        }
        
        if (!get_option('seo_meta_taxonomies')) {
            update_option('seo_meta_taxonomies', ['category']);
        }
    }
    
    public static function deactivate() {
        // Cleanup temporary options
    }
    
    public function get_admin() {
        return $this->admin;
    }
    
    public function get_public() {
        return $this->public;
    }
    
    public function get_settings() {
        return $this->settings;
    }
}