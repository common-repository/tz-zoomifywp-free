<?php
/**
 Plugin Name: ZoomifyWP Free
 Plugin URI: http://www.terrazoom.com
 Description: ZoomifyWP Free allows for the viewing of Zoomify in WordPress pages and posts using a simple shortcode.
 Version: 1.1
 Author: John Williams/TerraZoom
 Author URI: http://www.terrazoom.com
 License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

 // Exit when called directly
defined( 'ABSPATH' ) or die( 'Nope!' );

define('ZOOMIFYWP_BASE', plugin_dir_url( __FILE__ ));

// Allow ZIF uploads
	add_filter('upload_mimes', 'add_zif_support');
	function add_zif_support ( $mime_types=array() ) {
		$mime_types['zif'] = 'image/zif';
		return $mime_types;
}

// droppable image upload functionality
function load_custom_wp_admin_style() {
        wp_enqueue_script( 'jquery-ui-droppable' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

add_action('init', 'register_zoomify_script');

function register_zoomify_script() {
	wp_register_script('zoomify-script', plugins_url('zoomifywp/js/zoomify_zif.js', __FILE__), array('jquery'), '1.0', true);
}

// enquueue styles and css
function zoomify_add_scripts() {
    wp_enqueue_style('zoomify', ZOOMIFYWP_BASE . '_assets/css/zoomify_style.css');
    wp_enqueue_script('zoomify-js', ZOOMIFYWP_BASE . '_assets/js/ZoomifyImageViewerFree-min.js', '', false, false);
}
add_action('wp_enqueue_scripts', 'zoomify_add_scripts');

function zoomify_shortcode( $atts ) {
	$uploads = wp_upload_dir(); 
	$upload_dir = $uploads['baseurl'];
	$zoomPath = $upload_dir . '/zooms/';
	$zoomScript = '<div id="zoomifyContainer" style="width:100%; height:400px; margin:auto; border:1px; border-style:solid; border-color:#696969;" ></div>';	
	$atts = shortcode_atts(
		array(
			'filename' => 'no filename',
			'id' => 'no id',
		), $atts, 'zoomify' );
	return '<script type="text/javascript">Z.showImage("zoomifyContainer", "' . $zoomPath . $atts['filename'] . '");</script>' . $zoomScript;
}
add_shortcode( 'zoomify', 'zoomify_shortcode' );
