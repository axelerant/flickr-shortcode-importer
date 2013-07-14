<?php
/*
	Copyright 2013 Michael Cannon (email: mc@aihr.us)

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


class Flickr_Shortcode_Importer_Settings {
	const ID = 'flickr-shortcode-importer-settings';

	public static $default  = array(
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
	public static $defaults = array();
	public static $sections = array();
	public static $settings = array();
	public static $version  = null;


	public function __construct() {
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'init', array( &$this, 'init' ) );
		load_plugin_textdomain( 'flickr-shortcode-importer', false, '/flickr-shortcode-importer/languages/' );
	}


	public function init() {
		self::sections();
		self::settings();
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


	public static function get_defaults( $mode = null ) {
		if ( empty( self::$defaults ) )
			self::settings();

		$do_backwards = false;
		if ( 'backwards' == $mode ) {
			$old_version = fsi_get_option( 'version' );
			if ( ! empty( $old_version ) )
				$do_backwards = true;
		}

		foreach ( self::$settings as $id => $parts ) {
			$std = isset( $parts['std'] ) ? $parts['std'] : '';
			if ( $do_backwards ) {
				$version = ! empty( $parts['backwards']['version'] ) ? $parts['backwards']['version'] : false;
				if ( ! empty( $version ) ) {
					if ( $old_version < $version )
						$std = $parts['backwards']['std'];
				}
			}

			self::$defaults[ $id ] = $std;
		}

		return self::$defaults;
	}


	public static function get_settings() {
		if ( empty( self::$settings ) )
			self::settings();

		return self::$settings;
	}


	public function admin_init() {
		$version       = fsi_get_option( 'version' );
		self::$version = Flickr_Shortcode_Importer::VERSION;
		self::$version = apply_filters( 'flickr_shortcode_importer_version', self::$version );

		if ( $version != self::$version )
			$this->initialize_settings();

		$this->register_settings();
	}


	public function admin_menu() {
		$admin_page = add_options_page( esc_html__( 'Flickr Shortcode Importer Settings', 'flickr-shortcode-importer' ), esc_html__( 'Flickr Shortcode Importer', 'flickr-shortcode-importer' ), 'manage_options', self::ID, array( 'Flickr_Shortcode_Importer_Settings', 'display_page' ) );

		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );

		add_screen_meta_link(
			'fsi-importer-link',
			esc_html__( '[Flickr] Importer', 'flickr-shortcode-importer' ),
			admin_url( 'tools.php?page=' . Flickr_Shortcode_Importer::ID ),
			$admin_page,
			array( 'style' => 'font-weight: bold;' )
		);
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public function create_setting( $args = array() ) {
		extract( $args );

		if ( preg_match( '#(_expand_begin|_expand_end)#', $id ) )
			return;

		$field_args = array(
			'type' => $type,
			'id' => $id,
			'desc' => $desc,
			'std' => $std,
			'choices' => $choices,
			'label_for' => $id,
			'class' => $class,
		);

		self::$defaults[$id] = $std;

		add_settings_field( $id, $title, array( &$this, 'display_setting' ), self::ID, $section, $field_args );
	}


	public static function display_page() {
		echo '<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2>' . esc_html__( 'Flickr Shortcode Importer Settings', 'flickr-shortcode-importer' ) . '</h2>';

		echo '<form action="options.php" method="post">';

		settings_fields( self::ID );

		echo '<div id="' . self::ID . '">
			<ul>';

		foreach ( self::$sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

		echo '</ul>';

		self::do_settings_sections( self::ID );

		echo '
			<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . esc_html__( 'Save Changes', 'flickr-shortcode-importer' ) . '" /></p>
			</form>
		</div>
		';

		echo '
			<p>If you like this plugin, please <a href="http://aihr.us/about-aihrus/donate/" title="Donate for Good Karma"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" alt="Donate for Good Karma" /></a> to help fund further development and <a href="http://wordpress.org/support/plugin/flickr-shortcode-importer" title="Support forums">support</a>.</p>
		';

		$text = esc_html__( 'Copyright &copy;%1$s %2$s.', 'flickr-shortcode-importer' );
		$link = '<a href="http://aihr.us">Aihrus</a>';
		echo '<p class="copyright">' . sprintf( $text, date( 'Y' ), $link ) . '</p>';

		self::section_scripts();

		echo '</div>';
	}


	public static function section_scripts() {
		echo '
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( "#' . self::ID . '" ).tabs();
		// This will make the "warning" checkbox class really stand out when checked.
		$( ".warning" ).change(function() {
			if ($(this).is( ":checked" ) )
				$(this).parent().css( "background", "#c00" ).css( "color", "#fff" ).css( "fontWeight", "bold" );
			else
				$(this).parent().css( "background", "inherit" ).css( "color", "inherit" ).css( "fontWeight", "inherit" );
		});
	});
</script>
';
	}


	public static function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections ) || !isset( $wp_settings_sections[$page] ) )
			return;

		foreach ( (array) $wp_settings_sections[$page] as $section ) {
			if ( $section['callback'] )
				call_user_func( $section['callback'], $section );

			if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;

			echo '<table id=' . $section['id'] . ' class="form-table">';
			do_settings_fields( $page, $section['id'] );
			echo '</table>';
		}
	}


	public function display_section() {}


	public function display_about_section() {
		echo '
			<div id="about" style="width: 70%; min-height: 225px;">
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="' . WP_PLUGIN_URL . '/flickr-shortcode-importer/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/flickr-shortcode-importer/">Flickr Shortcode Importer</a> is by <a href="http://aihr.us/about-aihrus/michael-cannon-resume/">Michael Cannon</a>. He\'s <a title="Lot\'s of stuff about Peichi Liu…" href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an adventurous <a title="Water rat" href="http://www.chinesehoroscope.org/chinese_zodiac/rat/" target="_blank">water-rat</a>, <a title="Axelerant – Open Source. Engineered." href="http://axelerant.com/who-we-are">chief people officer</a>, <a title="Aihrus – website support made easy since 1999" href="http://aihr.us/about-aihrus/">chief technology officer</a>, <a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">cyclist</a>, <a title="Michael\'s poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>, <a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a> and <a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>.</p>
			</div>
		';
	}


	public static function display_setting( $args = array(), $do_echo = true, $input = null ) {
		$content = '';

		extract( $args );

		if ( is_null( $input ) ) {
			$options = get_option( self::ID );
		} else {
			$options      = array();
			$options[$id] = $input;
		}

		if ( ! isset( $options[$id] ) && $type != 'checkbox' ) {
			$options[$id] = $std;
		} elseif ( ! isset( $options[$id] ) ) {
			$options[$id] = 0;
		}

		$field_class = '';
		if ( ! empty( $class ) )
			$field_class = ' ' . $class;

		// desc isn't escaped because it's might contain allowed html
		$choices      = array_map( 'esc_attr', $choices );
		$field_class  = esc_attr( $field_class );
		$id           = esc_attr( $id );
		$options[$id] = esc_attr( $options[$id] );
		$std          = esc_attr( $std );

		switch ( $type ) {
		case 'checkbox':
			$content .= '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';

			if ( ! empty( $desc ) )
				$content .= '<label for="' . $id . '"><span class="description">' . $desc . '</span></label>';

			break;

		case 'file':
			$content .= '<input class="regular-text' . $field_class . '" type="file" id="' . $id . '" name="' . self::ID . '[' . $id . ']" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'heading':
			$content .= '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
			break;

		case 'hidden':
			$content .= '<input type="hidden" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . $options[$id] . '" />';

			break;

		case 'password':
			$content .= '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . $options[$id] . '" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'radio':
			$i             = 1;
			$count_choices = count( $choices );
			foreach ( $choices as $value => $label ) {
				$content .= '<input class="radio' . $field_class . '" type="radio" name="' . self::ID . '[' . $id . ']" id="' . $id . $i . '" value="' . $value . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';

				if ( $i < $count_choices )
					$content .= '<br />';

				$i++;
			}

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'readonly':
			$content .= '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . $options[$id] . '" readonly="readonly" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'select':
			$content .= '<select class="select' . $field_class . '" name="' . self::ID . '[' . $id . ']">';

			foreach ( $choices as $value => $label )
				$content .= '<option value="' . $value . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

			$content .= '</select>';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'text':
			$content .= '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" value="' . $options[$id] . '" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'textarea':
			$content .= '<textarea class="' . $field_class . '" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		default:
			break;
		}

		if ( ! $do_echo )
			return $content;

		echo $content;
	}


	public function initialize_settings() {
		$defaults                 = self::get_defaults( 'backwards' );
		$current                  = get_option( self::ID );
		$current                  = wp_parse_args( $current, $defaults );
		$current['admin_notices'] = fsi_get_option( 'version', self::$version );
		$current['version']       = self::$version;

		update_option( self::ID, $current );
	}


	public function register_settings() {
		register_setting( self::ID, self::ID, array( &$this, 'validate_settings' ) );

		foreach ( self::$sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), self::ID );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), self::ID );
		}

		foreach ( self::$settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
	}


	public function scripts() {
		wp_enqueue_script( 'jquery-ui-tabs' );
	}


	public function styles() {
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function validate_settings( $input, $options = null, $do_errors = false ) {
		$errors = array();

		if ( is_null( $options ) ) {
			$options  = self::get_settings();
			$defaults = self::get_defaults();

			if ( is_admin() ) {
				if ( ! empty( $input['reset_defaults'] ) ) {
					foreach ( $defaults as $id => $std )
						$input[$id] = $std;

					unset( $input['reset_defaults'] );
				}

				if ( ! empty( $input['import'] ) && $_SESSION['export'] != $input['import'] ) {
					$import       = $input['import'];
					$unserialized = unserialize( $import );
					if ( is_array( $unserialized ) ) {
						foreach ( $unserialized as $id => $std )
							$input[$id] = $std;
					}
				}
			}
		}

		foreach ( $options as $id => $parts ) {
			$default     = $parts['std'];
			$type        = $parts['type'];
			$validations = ! empty( $parts['validate'] ) ? $parts['validate'] : array();
			if ( ! empty( $validations ) )
				$validations = explode( ',', $validations );

			if ( ! isset( $input[ $id ] ) ) {
				if ( 'checkbox' != $type )
					$input[ $id ] = $default;
				else
					$input[ $id ] = 0;
			}

			if ( $default == $input[ $id ] && ! in_array( 'required', $validations ) )
				continue;

			if ( 'checkbox' == $type ) {
				if ( self::is_true( $input[ $id ] ) )
					$input[ $id ] = 1;
				else
					$input[ $id ] = 0;
			} elseif ( in_array( $type, array( 'radio', 'select' ) ) ) {
				// single choices only
				$keys = array_keys( $parts['choices'] );

				if ( ! in_array( $input[ $id ], $keys ) ) {
					if ( self::is_true( $input[ $id ] ) )
						$input[ $id ] = 1;
					else
						$input[ $id ] = 0;
				}
			}

			if ( ! empty( $validations ) ) {
				foreach ( $validations as $validate )
					self::validators( $validate, $id, $input, $default, $errors );
			}
		}

		// same has_archive and rewrite_slug causes problems
		if ( $input['has_archive'] == $input['rewrite_slug'] )
			$input['rewrite_slug'] = $defaults['rewrite_slug'];

		// did URL slugs change?
		$has_archive  = fsi_get_option( 'has_archive' );
		$rewrite_slug = fsi_get_option( 'rewrite_slug' );
		if ( $has_archive != $input['has_archive'] || $rewrite_slug != $input['rewrite_slug'] )
			flush_rewrite_rules();

		$input['version']        = self::$version;
		$input['donate_version'] = Flickr_Shortcode_Importer::VERSION;
		$input                   = apply_filters( 'flickr_shortcode_importer_validate_settings', $input, $errors );

		unset( $input['export'] );
		unset( $input['import'] );

		if ( empty( $do_errors ) ) {
			$validated = $input;
		} else {
			$validated = array(
				'input' => $input,
				'errors' => $errors,
			);
		}

		return $validated;
	}


	public static function validators( $validate, $id, &$input, $default, &$errors ) {
		switch ( $validate ) {
		case 'absint':
		case 'intval':
			if ( '' !== $input[ $id ] )
				$input[ $id ] = $validate( $input[ $id ] );
			else
				$input[ $id ] = $default;
			break;

		case 'ids':
			$input[ $id ] = self::validate_ids( $input[ $id ], $default );
			break;

		case 'min1':
			$input[ $id ] = intval( $input[ $id ] );
			if ( 0 >= $input[ $id ] )
				$input[ $id ] = $default;
			break;

		case 'nozero':
			$input[ $id ] = intval( $input[ $id ] );
			if ( 0 === $input[ $id ] )
				$input[ $id ] = $default;
			break;

		case 'order':
			$input[ $id ] = self::validate_order( $input[ $id ], $default );
			break;

		case 'required':
			if ( empty( $input[ $id ] ) )
				$errors[ $id ] = esc_html__( 'Required', 'flickr-shortcode-importer' );
			break;

		case 'slug':
			$input[ $id ] = self::validate_slug( $input[ $id ], $default );
			$input[ $id ] = strtolower( $input[ $id ] );
			break;

		case 'slugs':
			$input[ $id ] = self::validate_slugs( $input[ $id ], $default );
			$input[ $id ] = strtolower( $input[ $id ] );
			break;

		case 'term':
			$input[ $id ] = self::validate_term( $input[ $id ], $default );
			$input[ $id ] = strtolower( $input[ $id ] );
			break;

		default:
			$input[ $id ] = $validate( $input[ $id ] );
			break;
		}
	}


	public static function validate_ids( $input, $default ) {
		if ( preg_match( '#^\d+(,\s?\d+)*$#', $input ) )
			return preg_replace( '#\s#', '', $input );

		return $default;
	}


	public static function validate_order( $input, $default ) {
		if ( preg_match( '#^desc|asc$#i', $input ) )
			return $input;

		return $default;
	}


	public static function validate_slugs( $input, $default ) {
		if ( preg_match( '#^[\w-]+(,\s?[\w-]+)*$#', $input ) )
			return preg_replace( '#\s#', '', $input );

		return $default;
	}


	public static function validate_slug( $input, $default ) {
		if ( preg_match( '#^[\w-]+$#', $input ) )
			return $input;

		return $default;
	}


	public static function validate_term( $input, $default ) {
		if ( preg_match( '#^\w+$#', $input ) )
			return $input;

		return $default;
	}


	/**
	 * Let values like "true, 'true', 1, and 'yes'" to be true. Else, false
	 */
	public static function is_true( $value = null, $return_boolean = true ) {
		if ( true === $value || 'true' == strtolower( $value ) || 1 == $value || 'yes' == strtolower( $value ) ) {
			if ( $return_boolean )
				return true;
			else
				return 1;
		} else {
			if ( $return_boolean )
				return false;
			else
				return 0;
		}
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
