<?php
/**
 * Plugin Name: SEO Meta Manager
 * Description: Advanced SEO meta tag management for WordPress
 * Version: 1.0.0
 * Author: Talha Zulfiqar
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: seo-meta-manager
 * Domain Path: /languages
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Define plugin constants
define('SEO_META_MANAGER_VERSION', '1.0.0');
define('SEO_META_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEO_META_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEO_META_MANAGER_BASENAME', plugin_basename(__FILE__));

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'SEO_Meta_Manager\\';
    $base_dir = SEO_META_MANAGER_PLUGIN_DIR . 'includes/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
add_action('plugins_loaded', function() {
    require_once SEO_META_MANAGER_PLUGIN_DIR . 'includes/class-core.php';
    SEO_Meta_Manager\Core::instance();
});

// Register activation and deactivation hooks
register_activation_hook(__FILE__, ['SEO_Meta_Manager\Core', 'activate']);
register_deactivation_hook(__FILE__, ['SEO_Meta_Manager\Core', 'deactivate']);