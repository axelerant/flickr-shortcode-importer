<?php
/*
	Copyright 2014 Michael Cannon (email: mc@aihr.us)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Flickr Shortcode Importer settings class
 *
 * Based upon http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 */

require_once AIHR_DIR_INC . 'class-aihrus-settings.php';

if ( class_exists( 'Flickr_Shortcode_Importer_Settings' ) )
	return;


class Flickr_Shortcode_Importer_Settings extends Aihrus_Settings {
	const ID   = 'flickr-shortcode-importer-settings';
	const NAME = 'Flickr Shortcode Importer Settings';

	public static $admin_page;
	public static $class    = __CLASS__;
	public static $defaults = array();
	public static $plugin_assets;
	public static $plugin_url = 'http://wordpress.org/plugins/flickr-shortcode-importer/';
	public static $sections   = array();
	public static $settings   = array();
	public static $version;

	public static $default = array(
		'backwards' => array(
			'version' => '', // below this version number, use std
			'std' => '',
		),
		'choices' => array(), // key => value
		'class' => '',
		'desc' => '',
		'id' => 'default_field',
		'section' => 'general',
		'std' => '', // default key or value
		'title' => '',
		'type' => 'text', // textarea, checkbox, radio, select, hidden, heading, password, expand_begin, expand_end
		'validate' => '', // required, term, slug, slugs, ids, order, single paramater PHP functions
		'widget' => 1, // show in widget options, 0 off
	);


	public function __construct() {
		parent::__construct();

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'init', array( $this, 'init' ) );
	}


	public function init() {
		load_plugin_textdomain( 'flickr-shortcode-importer', false, '/flickr-shortcode-importer/languages/' );

		self::$plugin_assets = Flickr_Shortcode_Importer::$plugin_assets;
	}


	public static function sections() {
		self::$sections['general']   = esc_html__( 'Import Settings', 'flickr-shortcode-importer' );
		self::$sections['api']       = esc_html__( 'Flickr API', 'flickr-shortcode-importer' );
		self::$sections['selection'] = esc_html__( 'Posts Selection', 'flickr-shortcode-importer' );
		self::$sections['testing']   = esc_html__( 'Testing Options', 'flickr-shortcode-importer' );
		self::$sections['posts']     = esc_html__( 'Post Options', 'flickr-shortcode-importer' );
		self::$sections['reset']     = esc_html__( 'Reset', 'flickr-shortcode-importer' );
		self::$sections['about']     = esc_html__( 'About Flickr Shortcode Importer', 'flickr-shortcode-importer' );

		self::$sections = apply_filters( 'flickr_shortcode_importer_sections', self::$sections );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function settings() {
		// General
		self::$settings['skip_videos'] = array(
			'title' => esc_html__( 'Skip Importing Videos?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Importing videos from Flickr often fails. Shortcode is still converted to object/embed linking to Flickr.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['import_flickr_sourced_tags'] = array(
			'title' => esc_html__( 'Import Flickr-sourced A/IMG tags?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Converts Flickr-sourced A/IMG tags to [flickr] and then proceeds with import.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['set_featured_image'] = array(
			'title' => esc_html__( 'Set Featured Image?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Set the first [flickr] or [flickrset] image found as the Featured Image. Will not replace the current Featured Image of a post.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['force_set_featured_image'] = array(
			'title' => esc_html__( 'Force Set Featured Image?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Set the Featured Image even if one already exists for a post.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['remove_first_flickr_shortcode'] = array(
			'title' => esc_html__( 'Remove First Flickr Shortcode?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Removes the first [flickr] from post content. If you use Featured Images as header or lead images, then this might prevent duplicate images in your post.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['make_nice_image_title'] = array(
			'title' => esc_html__( 'Make Nice Image Title?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Try to make a nice title if none is set. For Flickr set images, Flickr set title plus a numeric suffix is applied.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['replace_file_name'] = array(
			'title' => esc_html__( 'Replace Filename with Image Title?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Mainly for SEO purposes. This setting replaces the imported media filename with the media\'s title. For non-images, this is always done.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['image_import_size'] = array(
			'title' => esc_html__( 'Image Import Size', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Size of image to import into media library from Flickr. If requested size doesn\'t exist, then original is imported because it\'s the closest to the requested import size.', 'flickr-shortcode-importer' ),
			'type' => 'select',
			'std' => 'Large',
			'choices' => array(
				'Small' => 'Small (240px wide)',
				'Medium 640' => 'Medium (640px wide)',
				'Large' => 'Large (1024px wide)',
				'Original' => 'Original',
			),
		);

		self::$settings['default_image_alignment'] = array(
			'title' => esc_html__( 'Default Image Alignment', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Default alignment of image displayed in post when no alignment is found.', 'flickr-shortcode-importer' ),
			'type' => 'select',
			'std' => 'left',
			'choices' => array(
				'none' => 'None',
				'left' => 'Left',
				'center' => 'Center',
				'right' => 'Right',
			),
		);

		self::$settings['default_image_size'] = array(
			'title' => esc_html__( 'Default Image Size', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Default size of image displayed in post when no size is found.', 'flickr-shortcode-importer' ),
			'type' => 'select',
			'std' => 'medium',
			'choices' => array(
				'thumbnail' => 'Thumbnail',
				'medium' => 'Medium',
				'large' => 'Large',
				'full' => 'Full',
			),
		);

		self::$settings['default_a_tag_class'] = array(
			'title' => esc_html__( 'Default A Tag Class', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Inserts a class into links around imported images. Useful for lightbox\'ing.', 'flickr-shortcode-importer' ),
			'std' => '',
			'type' => 'text',
		);

		self::$settings['link_image_to_attach_page'] = array(
			'title' => esc_html__( 'Link Image to Attachment Page?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'If set, post single view images are linked to attachment pages. Otherwise the image links to its source file.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['image_wrap_class'] = array(
			'title' => esc_html__( 'Image Wrap Class', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'If set, a span tag is wrapped around the image with the given class. Also wraps attribution if enabled. e.g. Providing `flickr-image` results in `&lt;span class="flickr-image"&gt;|&lt;/span&gt;`', 'flickr-shortcode-importer' ),
			'std' => esc_html__( '', 'flickr-shortcode-importer' ),
			'type' => 'text',
		);

		self::$settings['set_caption'] = array(
			'title' => esc_html__( 'Set Captions?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Uses media title as the caption.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['flickr_image_attribution'] = array(
			'title' => esc_html__( 'Include Flickr Author Attribution?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Appends Flickr username, linked back to Flickr image to the imported Flickr image.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['flickr_image_attribution_text'] = array(
			'title' => esc_html__( 'Flickr Author Attribution Text', 'flickr-shortcode-importer' ),
			'std' => esc_html__( 'Photo by ', 'flickr-shortcode-importer' ),
			'type' => 'text',
		);

		self::$settings['flickr_image_attribution_wrap_class'] = array(
			'title' => esc_html__( 'Flickr Author Attribution Wrap Class', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'If set, a span tag is wrapped around the attribution with the given class. e.g. Providing `flickr-attribution` results in `&lt;span class="flickr-attribution"&gt;|&lt;/span&gt;`', 'flickr-shortcode-importer' ),
			'std' => esc_html__( '', 'flickr-shortcode-importer' ),
			'type' => 'text',
		);

		self::$settings['flickr_link_in_desc'] = array(
			'title' => esc_html__( 'Add Flickr Attribution to Description?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Like `Include Flickr Author Attribution` but appends the image description.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['flickr_link_text'] = array(
			'title' => esc_html__( 'Flickr Attribution Text', 'flickr-shortcode-importer' ),
			'std' => esc_html__( 'Photo by ', 'flickr-shortcode-importer' ),
			'type' => 'text',
		);

		self::$settings['flickr_image_license'] = array(
			'title' => esc_html__( 'Add Image License to Description?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Append image license and link to image description.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['flickr_image_license_text'] = array(
			'title' => esc_html__( 'Flickr Image License Text', 'flickr-shortcode-importer' ),
			'std' => esc_html__( 'License ', 'flickr-shortcode-importer' ),
			'type' => 'text',
		);

		self::$settings['posts_to_import'] = array(
			'title' => esc_html__( 'Posts to Import', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( "A CSV list of post ids to import, like '1,2,3'.", 'flickr-shortcode-importer' ),
			'std' => '',
			'type' => 'text',
			'section' => 'selection',
			'validate' => 'ids',
		);

		self::$settings['skip_importing_post_ids'] = array(
			'title' => esc_html__( 'Skip Importing Posts', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( "A CSV list of post ids to not import, like '1,2,3'.", 'flickr-shortcode-importer' ),
			'std' => '',
			'type' => 'text',
			'section' => 'selection',
			'validate' => 'ids',
		);

		self::$settings['limit'] = array(
			'title' => esc_html__( 'Import Limit', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Useful for testing import on a limited amount of posts. 0 or blank means unlimited.', 'flickr-shortcode-importer' ),
			'std' => '',
			'type' => 'text',
			'section' => 'testing',
			'validate' => 'intval',
		);

		self::$settings['debug_mode'] = array(
			'section' => 'testing',
			'title' => esc_html__( 'Debug Mode?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Bypass Ajax controller to handle posts_to_import directly for testing purposes.', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
		);

		self::$settings['flickr_api_key'] = array(
			'title' => esc_html__( 'Flickr API Key', 'flickr-shortcode-importer' ),
			'desc' => __( '<a href="http://www.flickr.com/services/api/">Flickr API Documentation</a>', 'flickr-shortcode-importer' ),
			'std' => '9f9508c77dc554c1ee7fdc006aa1879e',
			'type' => 'text',
			'section' => 'api',
		);

		self::$settings['flickr_api_secret'] = array(
			'title' => esc_html__( 'Flickr API Secret', 'flickr-shortcode-importer' ),
			'std' => 'e63952df7d02cc03',
			'type' => 'text',
			'section' => 'api',
		);

		self::$settings['fg-user_id'] = array(
			'title' => esc_html__( 'Flickr User ID', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'For Flickr Gallery plugin. Example: 90901451@N00', 'flickr-shortcode-importer' ),
			'std' => get_option( 'fg-user_id' ),
			'type' => 'text',
			'section' => 'api',
		);

		self::$settings['fg-per_page'] = array(
			'title' => esc_html__( 'Images Per Page', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'For Flickr Gallery plugin', 'flickr-shortcode-importer' ),
			'std' => get_option( 'fg-per_page', 10 ),
			'type' => 'text',
			'section' => 'api',
		);

		self::$settings['role_enable_post_widget'] = array(
			'section' => 'posts',
			'title' => esc_html__( 'Post [flickr] Import Widget?', 'flickr-shortcode-importer' ),
			'desc' => esc_html__( 'Minimum role to enable for [flickr] Import widget on posts and page edit screens.', 'flickr-shortcode-importer' ),
			'type' => 'select',
			'std' => 'level_1',
			'choices' => array(
				'' => 'Disable',
				'level_10' => 'Administrator',
				'level_7' => 'Editor',
				'level_4' => 'Author',
				'level_1' => 'Contributor',
			),
		);

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $post_types as $post_type => $ptype_obj ) {
			self::$settings[ 'enable_post_widget_' . $post_type ] = array(
				'section' => 'posts',
				'title' => esc_html__( 'Enable for ' . $ptype_obj->labels->name, 'flickr-shortcode-importer' ),
				'type' => 'checkbox',
				'std' => ( 'attachment' != $post_type ) ? 1 : 0,
			);
		}

		// Reset
		self::$settings['force_reimport'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Reimport Flickr Source Images', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'std' => 0,
			'desc' => esc_html__( 'Needed when changing the Flickr image import size from prior imports.', 'flickr-shortcode-importer' ),
		);

		$options = get_option( self::ID );
		if ( ! empty( $options ) ) {
			$serialized_options = serialize( $options );
			$_SESSION['export'] = $serialized_options;

			self::$settings['export'] = array(
				'section' => 'reset',
				'title' => esc_html__( 'Export Settings', 'flickr-shortcode-importer' ),
				'type' => 'readonly',
				'desc' => esc_html__( 'These are your current settings in a serialized format. Copy the contents to make a backup of your settings.', 'flickr-shortcode-importer' ),
				'std' => $serialized_options,
				'widget' => 0,
			);
		}

		self::$settings['import'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Import Settings', 'flickr-shortcode-importer' ),
			'type' => 'textarea',
			'desc' => esc_html__( 'Paste new serialized settings here to overwrite your current configuration.', 'flickr-shortcode-importer' ),
			'widget' => 0,
		);

		self::$settings['delete_data'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Remove Plugin Data on Deletion?', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'class' => 'warning', // Custom class for CSS
			'desc' => esc_html__( 'Delete all Flickr Shortcode Importer data and options from database on plugin deletion', 'flickr-shortcode-importer' ),
			'widget' => 0,
		);

		self::$settings['reset_defaults'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Reset to Defaults?', 'flickr-shortcode-importer' ),
			'type' => 'checkbox',
			'class' => 'warning', // Custom class for CSS
			'desc' => esc_html__( 'Check this box to reset options to their defaults', 'flickr-shortcode-importer' ),
			'widget' => 0,
		);

		self::$settings = apply_filters( 'flickr_shortcode_importer_settings', self::$settings );

		foreach ( self::$settings as $id => $parts ) {
			self::$settings[ $id ] = wp_parse_args( $parts, self::$default );
		}
	}


	public static function get_defaults( $mode = null, $old_version = null ) {
		$old_version = fsi_get_option( 'version' );

		$defaults = parent::get_defaults( $mode, $old_version );
		$defaults = apply_filters( 'fsi_settings_defaults', $defaults );

		return $defaults;
	}


	public function admin_init() {
		$version       = fsi_get_option( 'version' );
		self::$version = Flickr_Shortcode_Importer::VERSION;
		self::$version = apply_filters( 'flickr_shortcode_importer_version', self::$version );

		if ( $version != self::$version ) {
			$this->initialize_settings();
		}

		if ( ! Flickr_Shortcode_Importer::do_load() ) {
			return;
		}

		self::load_options();
		self::register_settings();
	}


	public function admin_menu() {
		$admin_page = add_options_page( esc_html__( 'Flickr Shortcode Importer Settings', 'flickr-shortcode-importer' ), esc_html__( 'Flickr Shortcode Importer', 'flickr-shortcode-importer' ), 'manage_options', self::ID, array( 'Flickr_Shortcode_Importer_Settings', 'display_page' ) );

		add_action( 'admin_print_scripts-' . $admin_page, array( $this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( $this, 'styles' ) );

		add_screen_meta_link(
			'fsi-importer-link',
			esc_html__( '[Flickr] Importer', 'flickr-shortcode-importer' ),
			admin_url( 'tools.php?page=' . Flickr_Shortcode_Importer::ID ),
			$admin_page,
			array( 'style' => 'font-weight: bold;' )
		);
	}


	public static function display_page( $disable_donate = false ) {
		$disable_donate = fsi_get_option( 'disable_donate' );

		parent::display_page( $disable_donate );
	}


	public static function initialize_settings( $version = null ) {
		$version = fsi_get_option( 'version', self::$version );

		parent::initialize_settings( $version );
	}


}


function fsi_get_options() {
	$options = get_option( Flickr_Shortcode_Importer_Settings::ID );

	if ( false === $options ) {
		$options = Flickr_Shortcode_Importer_Settings::get_defaults();
		update_option( Flickr_Shortcode_Importer_Settings::ID, $options );
	}

	return $options;
}


function fsi_get_option( $option, $default = null ) {
	$options = get_option( Flickr_Shortcode_Importer_Settings::ID, null );

	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return $default;
}


function fsi_set_option( $option, $value = null ) {
	$options = get_option( Flickr_Shortcode_Importer_Settings::ID );

	if ( ! is_array( $options ) )
		$options = array();

	$options[$option] = $value;
	update_option( Flickr_Shortcode_Importer_Settings::ID, $options );
}


?>
