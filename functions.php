<?php


/* Images
https://unsplash.com/photos/jUNuMQvBwGc
https://unsplash.com/photos/TMOeGZw9NY4
https://unsplash.com/photos/6cOUbEdwG24
*/

final class Extant_Theme {

	/**
	 * Directory path to the theme folder.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir_path = '';

	/**
	 * Directory URI to the theme folder.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir_uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Initial theme setup.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		$this->dir_path = trailingslashit( get_template_directory()     );
		$this->dir_uri  = trailingslashit( get_template_directory_uri() );
	}

	/**
	 * Loads include and admin files for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function includes() {

		// Load the Hybrid Core framework and theme files.
		require_once( $this->dir_path . 'library/hybrid.php'    );
		require_once( $this->dir_path . 'inc/hybrid-x.php'     );
		require_once( $this->dir_path . 'inc/hybrid-fonts.php' );

		// Load theme functions files.
		require_once( $this->dir_path . 'inc/functions-customize.php' );
		require_once( $this->dir_path . 'inc/functions-filters.php'   );
		require_once( $this->dir_path . 'inc/functions-icons.php'     );
		require_once( $this->dir_path . 'inc/functions-options.php'   );
		require_once( $this->dir_path . 'inc/functions-scripts.php'   );
		require_once( $this->dir_path . 'inc/functions-template.php'  );

		// Load Easy Digital Downloads files if plugin is active.
		if ( class_exists( 'Easy_Digital_Downloads' ) )
			require_once( $this->dir_path . 'inc/functions-edd.php' );

		// Load admin files.
		if ( is_admin() )
			require_once( $this->dir_path . 'inc/admin-welcome.php' );

		// Launch the Hybrid Core framework.
		new Hybrid();
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Theme setup.
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 5 );

		// Register menus.
		add_action( 'init', array( $this, 'register_menus' ) );

		// Register image sizes.
		add_action( 'init', array( $this, 'register_image_sizes' ) );

		// Register layouts.
		add_action( 'hybrid_register_layouts', array( $this, 'register_layouts' ) );

		// Register scripts, styles, and fonts.
		add_action( 'wp_enqueue_scripts',    array( $this, 'register_scripts' ), 0 );
		add_action( 'enqueue_embed_scripts', array( $this, 'register_scripts' ), 0 );
	}

	/**
	 * The theme setup function.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function theme_setup() {

		// Custom Content Portfolio plugin.
		add_theme_support( 'custom-content-portfolio' );

		// Theme layouts.
		add_theme_support( 'theme-layouts', array( 'default' => 'grid-landscape' ) );

		// Breadcrumbs.
		add_theme_support( 'breadcrumb-trail' );

		// Template hierarchy.
		add_theme_support( 'hybrid-core-template-hierarchy' );

		// The best thumbnail/image script ever.
		add_theme_support( 'get-the-image' );

		// Nicer [gallery] shortcode implementation.
		add_theme_support( 'cleaner-gallery' );

		// Automatically add feed links to `<head>`.
		add_theme_support( 'automatic-feed-links' );

		// Post formats.
		add_theme_support(
			'post-formats',
			array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' )
		);

		// Handle content width for embeds and images.
		hybrid_set_content_width( 950 );
	}

	/**
	 * Registers nav menus.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register_menus() {

		register_nav_menu( 'primary',   _x( 'Primary',   'nav menu location', 'extant' ) );
		register_nav_menu( 'secondary', _x( 'Secondary', 'nav menu location', 'extant' ) );
	}

	/**
	 * Registers image sizes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register_image_sizes() {

		// Sets the `post-thumbnail` size.
		set_post_thumbnail_size( 213, 160, true );

		// Custom sizes.
		add_image_size( 'extant-xlarge',    950,  535,  true );
		add_image_size( 'extant-xlarge-2x', 1900, 1069, true );
		add_image_size( 'extant-large',     750,  422,  true );
		add_image_size( 'extant-large-2x',  1500, 844,  true );
	}

	/**
	 * Registers layouts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register_layouts() {

		hybrid_register_layout( 'grid-landscape', array( 'label' => esc_html__( 'Grid: Landscape', 'extant' ), 'is_post_layout' => false, 'image' => '%s/images/layouts/grid-landscape.png' ) );
		hybrid_register_layout( 'grid-portrait',  array( 'label' => esc_html__( 'Grid: Portrait',  'extant' ), 'is_post_layout' => false, 'image' => '%s/images/layouts/grid-portrait.png' ) );
	}

	/**
	 * Registers scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register_scripts() {

		// Register scripts.
		wp_register_script( 'extant', $this->dir_uri . 'js/theme.js', array( 'jquery' ), null, true );

		// Register fonts.
		hybrid_register_font( 'extant', array( 'family' => extant_get_font_families(), 'subset' => extant_get_font_subsets() ) );

		// Register styles.
		wp_register_style( 'font-awesome',        hybrid_get_stylesheet_uri( 'font-awesome', 'template' ) );
		wp_register_style( 'extant-mediaelement', hybrid_get_stylesheet_uri( 'mediaelement', 'template' ) );
		wp_register_style( 'extant-embed',        hybrid_get_stylesheet_uri( 'embed' ) );

		if ( is_child_theme() )
			wp_register_style( 'extant-parent-embed', hybrid_get_stylesheet_uri( 'embed', 'template' ) );
	}
}

/**
 * Gets the instance of the `Extant_Theme` class.  This function is useful for quickly grabbing data
 * used throughout the theme.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function extant_theme() {
	return Extant_Theme::get_instance();
}

// Let's roll!
extant_theme();
