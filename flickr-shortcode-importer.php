<?php
/**
 * Plugin Name: Flickr Shortcode Importer by Aihrus
 * Plugin URI: http://wordpress.org/extend/plugins/flickr-shortcode-importer/
 * Description: Flickr Shortcode Importer by Aihrus imports [flickr], [flickrset], [flickr-gallery] shortcodes and Flickr-sourced media into the Media Library.
 * Version: 2.1.0RC1
 * Author: Michael Cannon
 * Author URI: http://aihr.us/about-aihrus/michael-cannon-resume/
 * License: GPLv2 or later
 * Text Domain: flickr-shortcode-importer
 * Domain Path: /languages
 */


/**
 * Copyright 2014 Michael Cannon (email: mc@aihr.us)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! defined( 'FSI_AIHR_VERSION' ) )
	define( 'FSI_AIHR_VERSION', '1.1.4' );

if ( ! defined( 'FSI_BASE' ) )
	define( 'FSI_BASE', plugin_basename( __FILE__ ) );

if ( ! defined( 'FSI_DIR' ) )
	define( 'FSI_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'FSI_DIR_INC' ) )
	define( 'FSI_DIR_INC', FSI_DIR . 'includes/' );

if ( ! defined( 'FSI_DIR_LIB' ) )
	define( 'FSI_DIR_LIB', FSI_DIR_INC . 'libraries/' );

if ( ! defined( 'FSI_NAME' ) )
	define( 'FSI_NAME', 'Testimonials by Aihrus' );

if ( ! defined( 'FSI_VERSION' ) )
	define( 'FSI_VERSION', '2.1.0RC1' );

require_once FSI_DIR_INC . 'requirements.php';

global $fsi_activated;

$fsi_activated = true;
if ( ! fsi_requirements_check() ) {
	$fsi_activated = false;

	return false;
}

require_once FSI_DIR_INC . 'class-flickr-shortcode-importer.php';


add_action( 'plugins_loaded', 'flickr_shortcode_importer_plugin_init', 99 );
/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function flickr_shortcode_importer_plugin_init() {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! function_exists( 'add_screen_meta_link' ) ) {
		require_once FSI_DIR_LIB . 'screen-meta-links.php';
	}

	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( Flickr_Shortcode_Importer::PLUGIN_FILE ) ) {
		require_once FSI_DIR_INC . 'class-flickr-shortcode-importer-settings.php';

		global $Flickr_Shortcode_Importer;
		if ( is_null( $Flickr_Shortcode_Importer ) ) {
			$Flickr_Shortcode_Importer = new Flickr_Shortcode_Importer();
		}

		global $Flickr_Shortcode_Importer_Settings;
		if ( is_null( $Flickr_Shortcode_Importer_Settings ) ) {
			$Flickr_Shortcode_Importer_Settings = new Flickr_Shortcode_Importer_Settings();
		}
	}
}


register_activation_hook( __FILE__, array( 'Flickr_Shortcode_Importer', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Flickr_Shortcode_Importer', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Flickr_Shortcode_Importer', 'uninstall' ) );


add_action( 'save_post', 'fsi_save_post', 99 );
/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function fsi_save_post( $post_id ) {
	global $Flickr_Shortcode_Importer;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! is_numeric( $post_id ) ) {
		return;
	}

	if ( empty( $_POST['flickr-shortcode-importer'] ) ) {
		return;
	}

	// check that post is wanting the flickr codes imported
	if ( ! wp_verify_nonce( $_POST['flickr-shortcode-importer'], 'flickr_import' ) ) {
		return;
	}

	// save checkbox or not
	$checked = ! empty( $_POST['flickr_import'] ) ? 1 : 0;
	if ( ! $checked ) {
		return;
	} else {
		update_post_meta( $post_id, 'process_flickr_shortcode', $checked );
	}

	remove_action( 'save_post', 'fsi_save_post', 99 );
	$Flickr_Shortcode_Importer->process_flickr_shortcode( $post_id );
	add_action( 'save_post', 'fsi_save_post', 99 );
	delete_post_meta( $post_id, 'process_flickr_shortcode' );
}


?>
