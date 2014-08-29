<?php
/**
 * Handling AJAX processing
 */
class WC_Newsletter_Generator_Ajax{
	var $available_methods;

	function __construct(){
		$this->available_methods = array( 'get_products', 'update', 'change_auto_draft' );

		// register ajax endpoint
		add_action( 'wp_ajax_wcng_endpoint', array( $this, 'endpoint' ) );
	}

	/**
	 * AJAX endpoint 
	 * 
	 * @return void
	 */
	function endpoint(){
		// Default variables
		$defaults = array(
			'method' 		=> 'get_products',
			'paged'			=> 1,
			'_n'			=> false,
			'newsletter_id' => 0,
		);

		// Parse variables
		$args = wp_parse_args( $_REQUEST, $defaults );
		extract( $args, EXTR_SKIP );

		// Authentication
		if( in_array( $method, $this->available_methods ) && 
			wcng_current_user_can_edit_newsletter() && 
			wp_verify_nonce( $_n, "{$method}_{$newsletter_id}" ) )
		{
			$output = $this->$method( $args );
		} else {
			$output = false;
		}

		echo json_encode( $output );
		die();
	}

	/**
	 * Get products
	 * 
	 * @param array of products arguments
	 * 
	 * @return array
	 */
	function get_products( $args = array() ){
		// Default variables
		$defaults = array(
			'paged' => 2
		);

		// Parse variables
		$args = wp_parse_args( $args, $defaults );

		// Make sure that some value aren't overriden
		$args['post_type'] 		= 'product';
		$args['posts_status'] 	= 'publish';
		$args['posts_per_page']	= 10;

		// Get products
		$products = get_posts( $args );

		// Prepare products
		if( !empty( $products ) ){
			$products = $this->_prepare_products( $products );			
		} else {
			$products = 'all_loaded';
		}

		return $products;
	}

	/**
	 * Prepare products data
	 * 
	 * @param array of get_posts output
	 * 
	 * @return array of formatted
	 */
	function _prepare_products( $products_raw = array() ){
		// Prepare the return
		$products = array();

		if( !empty( $products_raw ) ){
			foreach ( $products_raw as $product_raw ) {
				// Get WC_Object
				$wc_product = new WC_Product( $product_raw->ID );
				$post_thumbnail_id = get_post_thumbnail_id( $product_raw->ID );

				// Prepare output
				$product = array(
					'id' 		=> $product_raw->ID,
					'title' 	=> $product_raw->post_title,
					'price' 	=> $wc_product->get_price_html(),
					'permalink' => get_permalink( $product_raw->ID )
				);

				if( $post_thumbnail_id ){
					$attachment_src = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
					$product['image'] = $attachment_src[0];
				}

				array_push( $products, $product );
			}
		}

		return $products;
	}

	/**
	 * Update content block
	 * 
	 * @param array of update value
	 * 
	 * @return array of update block
	 */
	function update( $params = array() ){
		// Default values
		$defaults = array(
			'newsletter_id' => false,
			'block_id' 		=> false,
			'mode'			=> false,
			'args'			=> false
		);

		// Parse args
		$params = wp_parse_args( $params, $defaults );
		extract( $params, EXTR_SKIP );

		// Check minimum requirement parameter
		if( !$newsletter_id || !$block_id || !$mode || !$args ){
			return false;
		}

		// Get current value
		$blocks = get_post_meta( $newsletter_id, '_newsletter_blocks', true );

		// If blocks 'is' still empty
		if( !$blocks ){
			$blocks = array();
		}

		// Update blocks value
		$allow_args = array( 'product_id', 'text', 'image', 'link' );

		$args = (array)$args;
		foreach ( $args as $arg_key => $arg ) {
			if( in_array( $arg_key, $allow_args ) ){
				switch ( $arg_key ) {
					case 'product_id':
						$blocks[$block_id][$mode][$arg_key] = intval( $arg );
						break;
					
					default:
						$blocks[$block_id][$mode][$arg_key] = sanitize_text_field( $arg );
						break;
				}
			}
		}

		// Save the blocks value
		update_post_meta( $newsletter_id, '_newsletter_blocks', $blocks );

		// Get the updated postmeta
		$new_blocks = get_post_meta( $newsletter_id, '_newsletter_blocks', true );

		return $new_blocks[$block_id][$mode];
	}

	/**
	 * Change the status of the post based on the ID given
	 * 
	 * @param array containing post ID
	 * 
	 * @return preview URL
	 */
	function change_auto_draft( $params = array() ){
		// Default values
		$defaults = array(
			'newsletter_id' => false
		);

		// Parse args
		$params = wp_parse_args( $params, $defaults );
		extract( $params );

		// Make sure that there's newsletter ID to be modified
		if( !$newsletter_id ){
			return false;
		}

		// Get post status
		$post = get_post( $newsletter_id );

		// If this is auto-draft, change it into draft
		if( 'auto-draft' == $post->post_status ){
			$post_updated = wp_update_post( array(
				'ID' 			=> $newsletter_id,
				'post_status' 	=> 'draft'
			) );
		}

		// return preview URL
		return site_url( "?post_type=newsletter&p={$newsletter_id}&preview=true" );
	}
}
new WC_Newsletter_Generator_Ajax;