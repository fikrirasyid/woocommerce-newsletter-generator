<?php
/**
 * Handling AJAX processing
 */
class WC_Newsletter_Generator_Ajax{
	function __construct(){
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
			'newsletter_id' => 0
		);

		// Parse variables
		$args = wp_parse_args( $_GET, $defaults );
		extract( $args, EXTR_SKIP );

		// Available methods
		$available_methods = array( 'get_products', 'update' );

		// Authentication
		if( in_array( $method, $available_methods ) && 
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
}
new WC_Newsletter_Generator_Ajax;