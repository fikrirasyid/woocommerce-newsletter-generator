<?php
/*
 Plugin Name: WooCommerce Newsletter Generator
 Plugin URI: 
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

			/*
			 * A short description of what your post type is. As far as I know, this isn't used anywhere 
			 * in core WordPress.  However, themes may choose to display this on post type archives. 
			 */
			'description'         => __( 'This is a description for my post type.', 'woocommerce-newsletter-generator' ), // string

			/* 
			 * Whether the post type should be used publicly via the admin or by front-end users.  This 
			 * argument is sort of a catchall for many of the following arguments.  I would focus more 
			 * on adjusting them to your liking than this argument.
			 */
			'public'              => true, // bool (default is FALSE)

			/*
			 * Whether queries can be performed on the front end as part of parse_request(). 
			 */
			'publicly_queryable'  => true, // bool (defaults to 'public').

			/*
			 * Whether to exclude posts with this post type from front end search results.
			 */
			'exclude_from_search' => false, // bool (defaults to FALSE - the default of 'internal')

			/*
			 * Whether individual post type items are available for selection in navigation menus. 
			 */
			'show_in_nav_menus'   => false, // bool (defaults to 'public')

			/*
			 * Whether to generate a default UI for managing this post type in the admin. You'll have 
			 * more control over what's shown in the admin with the other arguments.  To build your 
			 * own UI, set this to FALSE.
			 */
			'show_ui'             => true, // bool (defaults to 'public')

			/*
			 * Whether to show post type in the admin menu. 'show_ui' must be true for this to work. 
			 */
			'show_in_menu'        => true, // bool (defaults to 'show_ui')

			/*
			 * Whether to make this post type available in the WordPress admin bar. The admin bar adds 
			 * a link to add a new post type item.
			 */
			'show_in_admin_bar'   => true, // bool (defaults to 'show_in_menu')

			/*
			 * The position in the menu order the post type should appear. 'show_in_menu' must be true 
			 * for this to work.
			 */
			'menu_position'       => null, // int (defaults to 25 - below comments)

			/*
			 * The URI to the icon to use for the admin menu item. There is no header icon argument, so 
			 * you'll need to use CSS to add one.
			 */
			'menu_icon'           => 'dashicons-welcome-write-blog', // string (defaults to use the post icon)

			/*
			 * Whether the posts of this post type can be exported via the WordPress import/export plugin 
			 * or a similar plugin. 
			 */
			'can_export'          => true, // bool (defaults to TRUE)

			/*
			 * Whether to delete posts of this type when deleting a user who has written posts. 
			 */
			'delete_with_user'    => false, // bool (defaults to TRUE if the post type supports 'author')

			/*
			 * Whether this post type should allow hierarchical (parent/child/grandchild/etc.) posts. 
			 */
			'hierarchical'        => false, // bool (defaults to FALSE)

			/* 
			 * Whether the post type has an index/archive/root page like the "page for posts" for regular 
			 * posts. If set to TRUE, the post type name will be used for the archive slug.  You can also 
			 * set this to a string to control the exact name of the archive slug.
			 */
			'has_archive'         => 'newsletter', // bool|string (defaults to FALSE)

			/*
			 * Sets the query_var key for this post type. If set to TRUE, the post type name will be used. 
			 * You can also set this to a custom string to control the exact key.
			 */
			'query_var'           => 'newsletter', // bool|string (defaults to TRUE - post type name)

			/*
			 * A string used to build the edit, delete, and read capabilities for posts of this type. You 
			 * can use a string or an array (for singular and plural forms).  The array is useful if the 
			 * plural form can't be made by simply adding an 's' to the end of the word.  For newsletter, 
			 * array( 'box', 'boxes' ).
			 */
			'capability_type'     => 'newsletter', // string|array (defaults to 'post')

			/*
			 * Whether WordPress should map the meta capabilities (edit_post, read_post, delete_post) for 
			 * you.  If set to FALSE, you'll need to roll your own handling of this by filtering the 
			 * 'map_meta_cap' hook.
			 */
			'map_meta_cap'        => true, // bool (defaults to FALSE)

			/*
			 * Provides more precise control over the capabilities than the defaults.  By default, WordPress 
			 * will use the 'capability_type' argument to build these capabilities.  More often than not, 
			 * this results in many extra capabilities that you probably don't need.  The following is how 
			 * I set up capabilities for many post types, which only uses three basic capabilities you need 
			 * to assign to roles: 'manage_newsletters', 'edit_newsletters', 'create_newsletters'.  Each post type 
			 * is unique though, so you'll want to adjust it to fit your needs.
			 */
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

			/* 
			 * How the URL structure should be handled with this post type.  You can set this to an 
			 * array of specific arguments or true|false.  If set to FALSE, it will prnewsletter rewrite 
			 * rules from being created.
			 */
			'rewrite' => array(

				/* The slug to use for individual posts of this type. */
				'slug'       => 'newsletter', // string (defaults to the post type name)

				/* Whether to show the $wp_rewrite->front slug in the permalink. */
				'with_front' => false, // bool (defaults to TRUE)

				/* Whether to allow single post pagination via the <!--nextpage--> quicktag. */
				'pages'      => true, // bool (defaults to TRUE)

				/* Whether to create pretty permalinks for feeds. */
				'feeds'      => true, // bool (defaults to the 'has_archive' argument)

				/* Assign an endpoint mask to this permalink. */
				'ep_mask'    => EP_PERMALINK, // const (defaults to EP_PERMALINK)
			),

			/*
			 * What WordPress features the post type supports.  Many arguments are strictly useful on 
			 * the edit post screen in the admin.  However, this will help other themes and plugins 
			 * decide what to do in certain situations.  You can pass an array of specific features or 
			 * set it to FALSE to prnewsletter any features from being added.  You can use 
			 * add_post_type_support() to add features or remove_post_type_support() to remove features 
			 * later.  The default features are 'title' and 'editor'.
			 */
			'supports' => array(

				/* Post titles ($post->post_title). */
				'title',

				/* Post content ($post->post_content). */
				// 'editor',

				/* Post excerpt ($post->post_excerpt). */
				// 'excerpt',

				/* Post author ($post->post_author). */
				// 'author',

				/* Featured images (the user's theme must support 'post-thumbnails'). */
				// 'thumbnail',

				/* Displays comments meta box.  If set, comments (any type) are allowed for the post. */
				// 'comments',

				/* Displays meta box to send trackbacks from the edit post screen. */
				// 'trackbacks',

				/* Displays the Custom Fields meta box. Post meta is supported regardless. */
				// 'custom-fields',

				/* Displays the Revisions meta box. If set, stores post revisions in the database. */
				// 'revisions',

				/* Displays the Attributes meta box with a parent selector and menu_order input box. */
				// 'page-attributes',

				/* Displays the Format meta box and allows post formats to be used with the posts. */
				// 'post-formats',
			),

			/*
			 * Labels used when displaying the posts in the admin and sometimes on the front end.  These 
			 * labels do not cover post updated, error, and related messages.  You'll need to filter the 
			 * 'post_updated_messages' hook to customize those.
			 */
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

				/* Labels for hierarchical post types only. */
				'parent_item'        => __( 'Parent Newsletter',             'woocommerce-newsletter-generator' ),
				'parent_item_colon'  => __( 'Parent Newsletter:',            'woocommerce-newsletter-generator' ),

				/* Custom archive label.  Must filter 'post_type_archive_title' to use. */
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