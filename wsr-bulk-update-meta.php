<?php
/*
Plugin Name: WSR Bulk update meta
Plugin URI: http://websector.com.au
Description: Bulks updates using javascript so the php script doesnt time out
Version: 0.0.1
Author: WSR
Author URI: http://websector.com.au
License: A short license name. Example: GPL2
*/


add_action( 'admin_enqueue_scripts', 'wbum_ajax_script' );
function wbum_ajax_script() {
    wp_register_script('wbum-ajax', plugin_dir_url( __FILE__ ) . 'wbum.js', array( 'jquery' ));
    wp_enqueue_script('wbum-ajax');
}


add_action( 'wp_ajax_wbum_process', 'wbum_process' );
function wbum_process() {
    $result = false;
    $paged = (int)$_POST['page'];
    $post_type = $_POST['wbum_post_type'];
    $meta_key = $_POST['wbum_meta_key'];
    $meta_value = $_POST['wbum_meta_value'];
    $posts_per_page = 10;

    //Change the post_type
    $wp_query = new WP_Query(array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    ));
    
    if ($paged < $wp_query->max_num_pages && $wp_query->have_posts() ) {

        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();

            $postid = $wp_query->post->ID;
            $result = ($paged + 1) . ' of ' . $wp_query->max_num_pages . ' (pages of ' . $posts_per_page . ')';

            update_post_meta($postid, $meta_key, $meta_value);
        }
        wp_reset_postdata();
    }

	if( $result === false ) {
		echo '-1';
	} else {
		echo $result;
	}

	wp_die();
}


add_action('admin_menu', 'wbum_register_options_page');
function wbum_register_options_page() {
    add_options_page('WSR Bulk Meta Update', 'WSR Bulk Meta Update', 'manage_options', 'wbum', 'wbum_bulk_meta_options_page');
  }


function wbum_bulk_meta_options_page(){?>
    <p>Bulk Update meta fields</p>
    <input id="wbum_post_type" name="wbum_post_type" placeholder="post_type" />
    <input id="wbum_meta_key" name="wbum_meta_key" placeholder="meta_key" />
    <input id="wbum_meta_value" name="wbum_meta_value" placeholder="meta_value" />
    <button id="wbum_process_start_20190819">Start bulk update</button>
    <div id="wbum_bulk_meta_update_ouput"></div>
<?php }