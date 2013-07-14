<?php
/**
 * Plugin Name: Flickr Shortcode Importer
 * Plugin URI: http://wordpress.org/extend/plugins/flickr-shortcode-importer/
 * Description: TBD
 * Version: 0.0.1
 * Author: Michael Cannon
 * Author URI: http://aihr.us/about-aihrus/michael-cannon-resume/
 * License: GPLv2 or later
 */


/**
 * Copyright 2013 Michael Cannon (email: mc@aihr.us)
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
class Flickr_Shortcode_Importer {
	const ID          = 'flickr-shortcode-importer';
	const PLUGIN_FILE = 'flickr-shortcode-importer/flickr-shortcode-importer.php';
	const VERSION     = '0.0.1';

	private static $base = null;

	public static $donate_button = '';
	public static $settings_link = '';


	public function __construct() {
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'init', array( &$this, 'init' ) );
		self::$base = plugin_basename( __FILE__ );
	}


	public function admin_init() {
		$this->update();
		add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );
		self::$settings_link = '<a href="' . get_admin_url() . 'options-general.php?page=' . Flickr_Shortcode_Importer_Settings::ID . '">' . __( 'Settings', 'flickr-shortcode-importer' ) . '</a>';
	}


	public function init() {
		load_plugin_textdomain( self::ID, false, 'flickr-shortcode-importer/languages' );
		self::$donate_button = <<<EOD
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="WM4F995W9LHXE">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
EOD;
	}


	public function plugin_action_links( $links, $file ) {
		if ( $file == self::$base )
			array_unshift( $links, self::$settings_link );

		return $links;
	}


	public function activation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}


	public function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}


	public function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		global $wpdb;

		require_once 'lib/class-flickr-shortcode-importer-settings.php';
		$delete_data = cbqe_get_option( 'delete_data', false );
		if ( $delete_data ) {
			delete_option( Flickr_Shortcode_Importer_Settings::ID );
			$wpdb->query( 'OPTIMIZE TABLE `' . $wpdb->options . '`' );
		}
	}


	public static function plugin_row_meta( $input, $file ) {
		if ( $file != self::$base )
			return $input;

		$links = array(
			'<a href="http://aihr.us/about-aihrus/donate/"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" alt="PayPal - The safer, easier way to pay online!" /></a>',
			'<a href="http://aihr.us/downloads/flickr-shortcode-importer-premium-wordpress-plugin/">Purchase Flickr Shortcode Importer Premium</a>',
		);

		$input = array_merge( $input, $links );

		return $input;
	}


	public function admin_notices_0_0_1() {
		$content  = '<div class="updated"><p>';
		$content .= sprintf( __( 'If your Flickr Shortcode Importer display has gone to funky town, please <a href="%s">read the FAQ</a> about possible CSS fixes.', 'flickr-shortcode-importer' ), 'https://aihrus.zendesk.com/entries/23722573-Major-Changes-Since-2-10-0' );
		$content .= '</p></div>';

		echo $content;
	}


	public function admin_notices_donate() {
		$content  = '<div class="updated"><p>';
		$content .= sprintf( __( 'Please donate $2 towards development and support of this Flickr Shortcode Importer plugin. %s', 'flickr-shortcode-importer' ), self::$donate_button );
		$content .= '</p></div>';

		echo $content;
	}


	public function update() {
		$prior_version = cbqe_get_option( 'admin_notices' );
		if ( $prior_version ) {
			if ( $prior_version < '0.0.1' )
				add_action( 'admin_notices', array( $this, 'admin_notices_0_0_1' ) );

			cbqe_set_option( 'admin_notices' );
		}

		// display donate on major/minor version release
		$donate_version = cbqe_get_option( 'donate_version', false );
		if ( ! $donate_version || ( $donate_version != self::VERSION && preg_match( '#\.0$#', self::VERSION ) ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notices_donate' ) );
			cbqe_set_option( 'donate_version', self::VERSION );
		}
	}


	public static function scripts() {
		wp_enqueue_script( 'jquery' );
	}


	public static function styles() {
		wp_register_style( 'flickr-shortcode-importer', plugins_url( 'flickr-shortcode-importer.css', __FILE__ ) );
		wp_enqueue_style( 'flickr-shortcode-importer' );
	}


}


register_activation_hook( __FILE__, array( 'Flickr_Shortcode_Importer', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Flickr_Shortcode_Importer', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Flickr_Shortcode_Importer', 'uninstall' ) );


add_action( 'plugins_loaded', 'flickr_shortcode_importer_plugin_init', 99 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function flickr_shortcode_importer_plugin_init() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( Flickr_Shortcode_Importer::PLUGIN_FILE ) ) {
		require_once 'lib/class-flickr-shortcode-importer-settings.php';

		global $Flickr_Shortcode_Importer;
		if ( is_null( $Flickr_Shortcode_Importer ) )
			$Flickr_Shortcode_Importer = new Flickr_Shortcode_Importer();

		global $Flickr_Shortcode_Importer_Settings;
		if ( is_null( $Flickr_Shortcode_Importer_Settings ) )
			$Flickr_Shortcode_Importer_Settings = new Flickr_Shortcode_Importer_Settings();
	}
}


?>
