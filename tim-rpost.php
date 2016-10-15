<?php
/**
 * Created by PhpStorm.
 * User: milan
 * Date: 11-10-16
 * Time: 14:40
 */
/*
Plugin Name: Recent Post with shortcode
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Recent post short code with button in TinyMce.
Version: 1.1
Author: Milan Todorovich
Author URI: http://milantodorovic.nl
License: A "Slug" license name e.g. GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// tinymce section


add_action('admin_head', 'mpt_add_my_tc_button' );

function mpt_add_my_tc_button() {
	global $typenow;
	// check user permissions
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
	}
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
	    return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
	    add_filter("mce_external_plugins", "mpt_add_tinymce_plugin");
	    add_filter('mce_buttons', 'mpt_register_my_tc_button' );
    }
}

function mpt_add_tinymce_plugin($plugin_array) {
	$plugin_array['mpt_tc_button'] = plugins_url( '/js/tinymce-add-button.js', __FILE__ );
	return $plugin_array;
}

function mpt_register_my_tc_button($buttons) {
	array_push($buttons, "mpt_tc_button");
	return $buttons;
}

function mpt_tc_css() {
	wp_enqueue_style('mpt-tc', plugins_url('/css/style.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'mpt_tc_css');


/*
 *  recent posts shortcode
 */

function mpt_recent_posts_function ( $atts, $content = NULL )
{
	// Attributes
	extract( shortcode_atts(
		array(
			// difault values
			'orderby' => 'date',
			'posts_per_page' => '5'
		), $atts ));

	// do query
	$query = new WP_Query( ['orderby' => $orderby, 'posts_per_page' => $posts_per_page ] );

	// output

	$output = '<ul class="recent-posts">';

	$output .= '<h4>'.$content.'</h4>';

	while($query->have_posts()) : $query->the_post();

		$output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> - <small>' . get_the_date() . '</small></li>';

	endwhile;

	wp_reset_query();

	return $output . '</ul>';
}
add_shortcode( 'recent-posts', 'mpt_recent_posts_function' );

