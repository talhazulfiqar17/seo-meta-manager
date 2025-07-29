<?php
namespace SEO_Meta_Manager;

class i18n {
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'seo-meta-manager',
            false,
            SEO_META_MANAGER_PLUGIN_DIR . '/languages/'
        );
    }
    
    public static function get_default_strings() {
        return [
            'meta_title' => __('Meta Title', 'seo-meta-manager'),
            'meta_description' => __('Meta Description', 'seo-meta-manager'),
            'optimal_length' => __('Optimal length', 'seo-meta-manager'),
            'title_optimal' => __('50-60 characters', 'seo-meta-manager'),
            'description_optimal' => __('150-160 characters', 'seo-meta-manager'),
            'settings_saved' => __('Settings saved successfully!', 'seo-meta-manager'),
            'error_saving' => __('Error saving settings', 'seo-meta-manager')
        ];
    }
}