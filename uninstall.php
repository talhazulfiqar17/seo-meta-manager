<?php
// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('seo_meta_post_types');
delete_option('seo_meta_taxonomies');

// Delete post meta
$post_types = get_post_types(['public' => true]);
foreach ($post_types as $post_type) {
    $posts = get_posts([
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);
    
    foreach ($posts as $post_id) {
        delete_post_meta($post_id, '_seo_meta_title');
        delete_post_meta($post_id, '_seo_meta_description');
    }
}

// Delete term meta
$taxonomies = get_taxonomies(['public' => true]);
foreach ($taxonomies as $taxonomy) {
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'fields' => 'ids'
    ]);
    
    foreach ($terms as $term_id) {
        delete_term_meta($term_id, '_seo_meta_title');
        delete_term_meta($term_id, '_seo_meta_description');
    }
}