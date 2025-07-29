<?php
namespace SEO_Meta_Manager\Admin;

class Admin {
    public function __construct() {
        // Add plugin action links
        add_filter('plugin_action_links_' . SEO_META_MANAGER_BASENAME, [$this, 'add_action_links']);
        
        // Add admin notices
        add_action('admin_notices', [$this, 'show_admin_notices']);
    }
    
    public function add_action_links($links) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=seo-meta-manager'),
            __('Settings', 'seo-meta-manager')
        );
        
        array_unshift($links, $settings_link);
        return $links;
    }
    
    public function show_admin_notices() {
        $screen = get_current_screen();
        
        if ($screen->id === 'settings_page_seo-meta-manager') {
            if (isset($_GET['settings-updated'])) {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p>' . __('Settings saved successfully!', 'seo-meta-manager') . '</p>';
                echo '</div>';
            }
        }
    }
}