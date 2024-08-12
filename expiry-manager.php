<?php
/*
Plugin Name: ExpiryManager
Description: ExpiryManager allows you to set an expiration date for any public post type, automatically changing its status to "draft" (or "trash") upon expiration.
Version: 1.0.0
Author: Aamir Hussain
Author URI: aammir.github.io
License: GPLv2 or later
*/

// Add meta box to all post types
function cep_add_meta_box() {
    $post_types = get_post_types(['public' => true], 'names'); // Get all public post types
    foreach ($post_types as $post_type) {
        add_meta_box(
            'cep_expiration_date',
            __('Expiration Date', 'content-expiration-plugin'),
            'cep_meta_box_callback',
            $post_type,
            'side'
        );
    }
}
add_action('add_meta_boxes', 'cep_add_meta_box');

// Meta box callback function
function cep_meta_box_callback($post) {
    wp_nonce_field('cep_save_meta_box_data', 'cep_meta_box_nonce');
    $value = get_post_meta($post->ID, '_cep_expiration_date', true);
    echo '<label for="cep_expiration_date">Expiration Date: </label>';
    echo '<input type="date" id="cep_expiration_date" name="cep_expiration_date" value="' . esc_attr($value) . '" />';
}

// Save the expiration date when the post is saved
function cep_save_meta_box_data($post_id) {
    if (!isset($_POST['cep_meta_box_nonce']) || !wp_verify_nonce($_POST['cep_meta_box_nonce'], 'cep_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['cep_expiration_date'])) {
        $expiration_date = sanitize_text_field($_POST['cep_expiration_date']);
        update_post_meta($post_id, '_cep_expiration_date', $expiration_date);
    }
}
add_action('save_post', 'cep_save_meta_box_data');

// Function to check for expired posts
function cep_check_expired_posts() {
    $post_types = get_post_types(['public' => true], 'names'); // Get all public post types
    foreach ($post_types as $post_type) {
        $args = [
            'post_type' => $post_type,
            'posts_per_page' => -1, // Get all posts
            'meta_query' => [
                [
                    'key' => '_cep_expiration_date',
                    'value' => gmdate('Y-m-d'),
                    'compare' => '<=',
                    'type' => 'DATE'
                ]
            ]
        ];
        $expired_posts = new WP_Query($args);

        if ($expired_posts->have_posts()) {
            while ($expired_posts->have_posts()) {
                $expired_posts->the_post();
                wp_update_post([
                    'ID' => get_the_ID(),
                    'post_status' => 'draft' // Change status to draft or 'trash'
                ]);
            }
            wp_reset_postdata();
        }
    }
}

// Run the expiration check on every page load
add_action('wp', 'cep_check_expired_posts');
