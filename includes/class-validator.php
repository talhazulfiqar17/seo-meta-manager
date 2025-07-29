<?php
namespace SEO_Meta_Manager\Admin;

class Validator {
    public static function sanitize_post_types($post_types) {
        if (!is_array($post_types)) {
            return [];
        }
        
        $valid_post_types = [];
        $available_post_types = get_post_types(['public' => true]);
        
        foreach ($post_types as $post_type) {
            if (in_array($post_type, $available_post_types)) {
                $valid_post_types[] = $post_type;
            }
        }
        
        return $valid_post_types;
    }
    
    public static function sanitize_taxonomies($taxonomies) {
        if (!is_array($taxonomies)) {
            return [];
        }
        
        $valid_taxonomies = [];
        $available_taxonomies = get_taxonomies(['public' => true]);
        
        foreach ($taxonomies as $taxonomy) {
            if (in_array($taxonomy, $available_taxonomies)) {
                $valid_taxonomies[] = $taxonomy;
            }
        }
        
        return $valid_taxonomies;
    }
    
    public static function sanitize_meta_title($title) {
        $title = sanitize_text_field($title);
        return substr($title, 0, 100); // Limit to 100 chars
    }
    
    public static function sanitize_meta_description($description) {
        $description = sanitize_textarea_field($description);
        return substr($description, 0, 300); // Limit to 300 chars
    }
    
    public static function get_title_status($title) {
        $length = mb_strlen($title);
        
        if ($length >= 50 && $length <= 60) {
            return 'success';
        } elseif (($length >= 40 && $length < 50) || ($length > 60 && $length <= 70)) {
            return 'warning';
        } else {
            return 'error';
        }
    }
    
    public static function get_description_status($description) {
        $length = mb_strlen($description);
        
        if ($length >= 150 && $length <= 160) {
            return 'success';
        } elseif (($length >= 130 && $length < 150) || ($length > 160 && $length <= 170)) {
            return 'warning';
        } else {
            return 'error';
        }
    }
}