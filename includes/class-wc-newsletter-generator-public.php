<?php
/**
 * Handling public facing mechanism
 */
class WC_Newsletter_Generator_Public{
	function __construct(){
		add_filter( 'template_include', array( $this, 'route_template' ) );

		add_action( 'wp', array( $this, 'unhook_head_footer') );

		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_enqueue_styles_scripts'), 1000 );
	}

	/**
	 * Unhooking hooks from wp_head
	 * 
	 * @return void
	 */
	function unhook_head_footer(){
		if( is_singular( 'newsletter' ) && wcng_current_user_can_edit_newsletter() ){
			global $wp_filter;

			// Unhook actions from wp_head
			foreach ( $wp_filter['wp_head'] as $priority => $wp_head_hooks ) {
				if( is_array( $wp_head_hooks ) ){
					foreach ( $wp_head_hooks as $wp_head_hook ) {

						if( !is_array( $wp_head_hook['function'] ) && !in_array( $wp_head_hook['function'], array( 'wp_enqueue_scripts', 'wp_print_styles', 'wp_print_head_scripts' ) ) ){
							remove_action( 'wp_head', $wp_head_hook['function'], $priority );							
						}
					}
				}
			}

			// Unhook actions from wp_footer
			foreach ($wp_filter['wp_footer'] as $priority => $wp_footer_hooks ) {
				if( is_array( $wp_footer_hooks ) ){
					foreach ( $wp_footer_hooks as $wp_footer_hook ) {

						if( !is_array( $wp_footer_hook['function'] ) && !in_array( $wp_footer_hook['function'], array( 'wp_print_footer_scripts', 'wc_print_js', 'wp_print_media_templates' ) ) ){
							remove_action( 'wp_footer', $wp_footer_hook['function'], $priority );
						}

					}
				}
			}
		}
	}

	/**
	 * Removing enqueued styles and scripts
	 * 
	 * @return void
	 */
	function dequeue_enqueue_styles_scripts(){
		// Removing other scripts and styles on edit page
		if( is_singular( 'newsletter' ) && wcng_current_user_can_edit_newsletter() ){
			global $wp_styles, $wp_scripts, $post;

			// Dequeued styles
			if( is_array( $wp_styles->queue ) ){
				foreach ( $wp_styles->queue as $style ) {
					wp_dequeue_style( $style );
				}				
			}

			// Dequeue scripts
			if( is_array( $wp_scripts->queue) ){
				foreach ( $wp_scripts->queue as $script ) {
					wp_dequeue_script( $script );
				}				
			}

			// Enqueue style
			wp_enqueue_style( 'wcng-front-end-editor', WC_NEWSLETTER_GENERATOR_URL . 'css/wc-newsletter-generator-front-end-editor.css', array(), 20140828, 'all' );
	
			// Enqueue scripts
			wp_enqueue_media();
			wp_register_script( 'jquery-velocity', WC_NEWSLETTER_GENERATOR_URL . 'js/jquery.velocity.js', array( 'jquery' ), '0.11.9', false );
			wp_enqueue_script( 'wcng-front-end-editor', WC_NEWSLETTER_GENERATOR_URL . 'js/wc-newsletter-generator-front-end-editor.js', array( 'jquery', 'jquery-velocity' ), 20140828, false );

			// Attaching variables for scripts
			$wcng_params = array(
				'newsletter_id' 	=> $post->ID,
				'_n_update'			=> wp_create_nonce( 'update_' . $post->ID ),
				'_n_get_products'	=> wp_create_nonce( 'get_products_' . $post->ID ),
				'endpoint'			=> site_url( '/wp-admin/admin-ajax.php?action=wcng_endpoint' )
			);
			wp_localize_script( 'wcng-front-end-editor', 'wcng_params', $wcng_params );
		}
	}

	/**
	 * Routing single page to custom template
	 * 
	 * @return string of path
	 */
	function route_template( $single_template ){
		global $wp_query;

		if( is_singular( 'newsletter' ) ){
			return WC_NEWSLETTER_GENERATOR_DIR . '/templates/default.php';
		} else {
			return $single_template;
		}
	}
}
new WC_Newsletter_Generator_Public;