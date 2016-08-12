<?php
 
/*
Plugin Name: Post Author Byline
Plugin URI: 
Description: Adds an author_id to an author_byline_id in the post_meta field
Author: Evan Rose 
Version: 1.0
*/

function wser_save_author_byline_meta($post_id, $post) {
    
    if ( !wp_verify_nonce( $_POST['author_byline_meta_noncename'], plugin_basename(__FILE__) )) {       
        return $post->ID;
    }

    if ( !current_user_can( 'edit_post', $post->ID )) {
        return $post->ID;
    }
    
    echo $author_byline_id = sanitize_text_field( $_POST['author-byline-id'] );
    
    if ( get_post_meta( $post->ID, '_author_byline_id', false ) ) {
        update_post_meta( $post->ID, '_author_byline_id', $author_byline_id );
    } 
    else {
        add_post_meta( $post->ID, '_author_byline_id', $author_byline_id );
    }
}
add_action('save_post', 'wser_save_author_byline_meta', 1, 2);


function wser_author_byline_box() {
    
    add_meta_box( 'author-byline-div', __('Byline'), 'ca_meta_callback', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'wser_author_byline_box' );


function ca_meta_callback( $post ) {

    global $user_ID;

    $author_byline_id = get_post_meta( $post->ID, '_author_byline_id', true );
    if ( empty( $author_byline_id ) ) {
        $author_byline_id = $user_ID;
    }
    
    wp_dropdown_users( array(
        'name' => 'author-byline-id',
        'selected' => $author_byline_id,
        'include_selected' => true
    ) );

    echo '<input type="hidden" name="author_byline_meta_noncename" id="author_byline_meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
}