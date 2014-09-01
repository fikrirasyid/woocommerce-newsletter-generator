<?php
/*
 Plugin Name: WooCommerce Newsletter Generator
 Plugin URI: http://pro.fikrirasyid.com/portfolio/woocommerce-newsletter-generator/
 Description: Generate HTML email from WooCommerce's product
 Author: Fikri Rasyid
 Version: 0.1
 Author URI: http://fikrirasyid.com
*/

/**
 * Constants
 */
if (!defined('WC_NEWSLETTER_GENERATOR_DIR'))
    define('WC_NEWSLETTER_GENERATOR_DIR', plugin_dir_path( __FILE__ ));


if (!defined('WC_NEWSLETTER_GENERATOR_URL'))
    define('WC_NEWSLETTER_GENERATOR_URL', plugin_dir_url( __FILE__ ));	

/**
 * Requiring files
 */
require_once( 'includes/functions-wc-newsletter-generator-template-tags.php' );
require_once( 'includes/class-wc-newsletter-generator.php' );
require_once( 'includes/class-wc-newsletter-generator-editor.php' );
require_once( 'includes/class-wc-newsletter-generator-public.php' );
require_once( 'includes/class-wc-newsletter-generator-ajax.php' );

/**
 * Setup plugin
 */
class WC_Newsletter_Generator_Setup{
	var $wc_newsletter_generator;

	function __construct(){
		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

		$this->wc_newsletter_generator = new WC_Newsletter_Generator;

		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ) );
	}

	/**
	 * Activation task. Do this when the plugin is activated
	 * 
	 * @return void
	 */
	function activation(){
		// Registering post type here so it can be flushed right away
		$this->register_post_type();

		flush_rewrite_rules();		
	}

	/**
	 * Deactivation task. Do this when the plugin is deactivated
	 * 
	 * @return void
	 */
	function deactivation(){
	}

	/**
	 * Adding CPT for newsletter
	 * 
	 * @return void
	 */
	function register_post_type(){
		
		/* Set up the arguments for the post type. */
		$args = array(

			'description'         => __( 'Newsletter HTML Email', 'woocommerce-newsletter-generator' ), // string
			'public'              => true, // bool (default is FALSE)
			'publicly_queryable'  => true, // bool (defaults to 'public').
			'exclude_from_search' => true, // bool (defaults to FALSE - the default of 'internal')
			'show_in_nav_menus'   => false, // bool (defaults to 'public')
			'show_ui'             => true, // bool (defaults to 'public')
			'show_in_menu'        => true, // bool (defaults to 'show_ui')
			'show_in_admin_bar'   => true, // bool (defaults to 'show_in_menu')
			'menu_position'       => 21, // int (defaults to 25 - below comments)
			'menu_icon'           => 'dashicons-welcome-write-blog', // string (defaults to use the post icon)
			'can_export'          => true, // bool (defaults to TRUE)
			'delete_with_user'    => false, // bool (defaults to TRUE if the post type supports 'author')
			'hierarchical'        => false, // bool (defaults to FALSE)
			'has_archive'         => 'newsletter', // bool|string (defaults to FALSE)
			'query_var'           => 'newsletter', // bool|string (defaults to TRUE - post type name)
			'capability_type'     => 'newsletter', // string|array (defaults to 'post')
			'map_meta_cap'        => true, // bool (defaults to FALSE)
			'capabilities' => array(

				// meta caps (don't assign these to roles)
				'edit_post'              => 'edit_newsletter',
				'read_post'              => 'read_newsletter',
				'delete_post'            => 'delete_newsletter',

				// primitive/meta caps
				'create_posts'           => 'create_newsletters',

				// primitive caps used outside of map_meta_cap()
				'edit_posts'             => 'edit_newsletters',
				'edit_others_posts'      => 'manage_newsletters',
				'publish_posts'          => 'manage_newsletters',
				'read_private_posts'     => 'read',

				// primitive caps used inside of map_meta_cap()
				'read'                   => 'read',
				'delete_posts'           => 'manage_newsletters',
				'delete_private_posts'   => 'manage_newsletters',
				'delete_published_posts' => 'manage_newsletters',
				'delete_others_posts'    => 'manage_newsletters',
				'edit_private_posts'     => 'edit_newsletters',
				'edit_published_posts'   => 'edit_newsletters'
			),
			'rewrite' => array(
				'slug'       => 'newsletter', // string (defaults to the post type name)
				'with_front' => false, // bool (defaults to TRUE)
				'pages'      => false, // bool (defaults to TRUE)
				'feeds'      => false, // bool (defaults to the 'has_archive' argument)
				'ep_mask'    => EP_PERMALINK, // const (defaults to EP_PERMALINK)
			),
			'supports' => array(
				'title',
			),
			'labels' => array(
				'name'               => __( 'Newsletters',                   'woocommerce-newsletter-generator' ),
				'singular_name'      => __( 'Newsletter',                    'woocommerce-newsletter-generator' ),
				'menu_name'          => __( 'Newsletters',                   'woocommerce-newsletter-generator' ),
				'name_admin_bar'     => __( 'Newsletters',                   'woocommerce-newsletter-generator' ),
				'add_new'            => __( 'Add New',                    'woocommerce-newsletter-generator' ),
				'add_new_item'       => __( 'Add New Newsletter',            'woocommerce-newsletter-generator' ),
				'edit_item'          => __( 'Edit Newsletter',               'woocommerce-newsletter-generator' ),
				'new_item'           => __( 'New Newsletter',                'woocommerce-newsletter-generator' ),
				'view_item'          => __( 'View Newsletter',               'woocommerce-newsletter-generator' ),
				'search_items'       => __( 'Search Newsletters',            'woocommerce-newsletter-generator' ),
				'not_found'          => __( 'No newsletters found',          'woocommerce-newsletter-generator' ),
				'not_found_in_trash' => __( 'No newsletters found in trash', 'woocommerce-newsletter-generator' ),
				'all_items'          => __( 'All Newsletters',               'woocommerce-newsletter-generator' ),
				'parent_item'        => __( 'Parent Newsletter',             'woocommerce-newsletter-generator' ),
				'parent_item_colon'  => __( 'Parent Newsletter:',            'woocommerce-newsletter-generator' ),
				'archive_title'      => __( 'Newsletters',                   'woocommerce-newsletter-generator' ),
			)
		);

		/* Register the post type. */
		register_post_type(
			'newsletter', // Post type name. Max of 20 characters. Uppercase and spaces not allowed.
			$args      // Arguments for post type.
		);
	}

	/**
	 * Register image sizes for HTML email template
	 * 
	 * @return void
	 */
	function register_image_sizes(){
		$image_sizes = $this->wc_newsletter_generator->image_sizes();

		// Check the image sizes existance for our safety
		if( is_array( $image_sizes ) && !empty( $image_sizes ) ){
			
			// Loop the product sizes
			foreach ( $image_sizes as $image_size ) {
				$defaults = 					array( 
					'id' 		=> 'thumbnail', 
					'width' 	=> 150, 
					'height' 	=> 150, 
					'hard_crop' => true 
				);

				// Parse against default arguments
				$image_size = wp_parse_args( $image_size, $defaults );
				extract( $image_size );

				// Register 
				add_image_size( $id, $width, $height, $hard_crop );
			}
		}
	}
}
new WC_Newsletter_Generator_Setup;