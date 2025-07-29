<?php
namespace SEO_Meta_Manager\Frontend;

class Frontend {
    public function __construct() {
        add_action('wp_head', [$this, 'inject_meta_tags'], 1);
    }
    
    public function inject_meta_tags() {
        if (is_singular()) {
            $this->inject_singular_meta();
        } elseif (is_tax() || is_category() || is_tag()) {
            $this->inject_taxonomy_meta();
        }
    }
    
    private function inject_singular_meta() {
        global $post;
        
        $post_types = get_option('seo_meta_post_types', ['post', 'page']);
        
        if (!in_array($post->post_type, $post_types)) {
            return;
        }
        
        $meta_title = get_post_meta($post->ID, '_seo_meta_title', true);
        $meta_description = get_post_meta($post->ID, '_seo_meta_description', true);
        
        if ($meta_title) {
            echo '<meta name="title" content="' . esc_attr($meta_title) . '">' . "\n";
        }
        
        if ($meta_description) {
            echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . "\n";
        }
    }
    
    private function inject_taxonomy_meta() {
        $taxonomies = get_option('seo_meta_taxonomies', ['category']);
        $current_taxonomy = get_queried_object()->taxonomy;
        
        if (!in_array($current_taxonomy, $taxonomies)) {
            return;
        }
        
        $term_id = get_queried_object_id();
        
        $meta_title = get_term_meta($term_id, '_seo_meta_title', true);
        $meta_description = get_term_meta($term_id, '_seo_meta_description', true);
        
        if ($meta_title) {
            echo '<meta name="title" content="' . esc_attr($meta_title) . '">' . "\n";
        }
        
        if ($meta_description) {
            echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . "\n";
        }
    }
}