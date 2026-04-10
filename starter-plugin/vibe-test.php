<?php
/**
 * Plugin Name: DWL Vibe Test Plugin
 * Plugin URI: https://github.com/your-org/dynamic-weblab-vibe-trial
 * Description: Technical trial plugin for Dynamic Web Lab. Build your custom Elementor widget here.
 * Version: 1.0.0
 * Author: Candidate
 * Author URI:
 * License: GPL v2 or later
 * Text Domain: dwl-vibe-test
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'DWL_VIBE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DWL_VIBE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DWL_VIBE_VERSION', '1.0.0' );

require_once DWL_VIBE_PLUGIN_DIR . 'includes/functions.php';

/**
 * Load plugin translations.
 */
function dwl_vibe_load_textdomain() {
	load_plugin_textdomain( 'dwl-vibe-test', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'dwl_vibe_load_textdomain' );

/**
 * Check if Elementor is active.
 */
function dwl_vibe_check_elementor_dependency() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'dwl_vibe_elementor_missing_notice' );
		return;
	}
}
add_action( 'plugins_loaded', 'dwl_vibe_check_elementor_dependency' );

function dwl_vibe_elementor_missing_notice() {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'DWL Vibe Test requires Elementor to be installed and activated.', 'dwl-vibe-test' ) . '</p></div>';
}

/**
 * Register the custom widget.
 */
function dwl_vibe_register_custom_widgets( $widgets_manager ) {
	require_once DWL_VIBE_PLUGIN_DIR . 'includes/class-widget.php';
	$widgets_manager->register( new \Dwl_Vibe_Pricing_Widget() );
}
add_action( 'elementor/widgets/register', 'dwl_vibe_register_custom_widgets' );

/**
 * Enqueue frontend assets.
 * Candidate: Implement proper enqueue logic here.
 */
function dwl_vibe_enqueue_assets() {
	wp_register_style( 'dwl-vibe-pricing-style', DWL_VIBE_PLUGIN_URL . 'assets/css/style.css', [], DWL_VIBE_VERSION );
}
add_action( 'wp_enqueue_scripts', 'dwl_vibe_enqueue_assets' );