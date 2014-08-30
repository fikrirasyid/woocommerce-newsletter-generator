<?php
/**
 * Handling AJAX processing
 */
class WC_Newsletter_Generator_Ajax{
	var $available_methods;
	var $wc_newsletter_generator;

	function __construct(){
		$this->available_methods 		= array( 'get_products', 'update', 'change_template' );
		$this->wc_newsletter_generator 	= new WC_Newsletter_Generator;

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
			'post_id' 		=> 0,
			'_n'			=> false,
			'args'			=> array()
		);

		// Parse variables
		$params = wp_parse_args( $_REQUEST, $defaults );
		extract( $params, EXTR_SKIP );

		// Authentication
		if( in_array( $method, $this->available_methods ) && 
			wcng_current_user_can_edit_newsletter() && 
			wp_verify_nonce( $_n, "{$method}_{$post_id}" ) )
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
			'post_id' 		=> false,
			'block_id' 		=> false,
			'mode'			=> false,
			'properties'	=> false
		);

		// Parse args
		$params = wp_parse_args( $params, $defaults );
		extract( $params, EXTR_SKIP );

		// Check minimum requirement parameter
		if( !$post_id || !$block_id || !$mode || !$properties ){
			return false;
		}

		// Get current value
		$blocks = get_post_meta( $post_id, '_newsletter_blocks', true );

		// If blocks 'is' still empty
		if( !$blocks ){
			$blocks = array();
		}

		// Update blocks value
		$allow_args = array( 'product_id', 'text', 'image', 'link' );

		$properties = (array)$properties;
		foreach ( $properties as $arg_key => $arg ) {
			if( in_array( $arg_key, $allow_args ) ){
				switch ( $arg_key ) {
					case 'product_id':
						$product_id = intval( $arg );
						$product_image_size = $properties['product_image_size'];

						if( $product_id ){
							$blocks[$block_id][$mode][$arg_key] = $product_id;

							// Populate and store the product data							
							$blocks[$block_id][$mode]['title'] 		= get_the_title( $product_id );
							$blocks[$block_id][$mode]['permalink'] 	= get_permalink( $product_id );

							// Get the price
							$wc_product = new WC_Product( $product_id );
							$blocks[$block_id][$mode]['price'] 		= strip_tags( $wc_product->get_price_html() );

							// Get the thumbnail, if there's any
						    $post_thumbnail_id = get_post_thumbnail_id( $product_id );

						    if( $post_thumbnail_id ){
						        $attachment_src = wp_get_attachment_image_src( $post_thumbnail_id, $product_image_size );

						        // Check for image existence
						        if( isset( $attachment_src[0] ) ){
							        $image = $attachment_src[0];						        	
						        } else {
									$image = WC_NEWSLETTER_GENERATOR_URL . 'assets/default-product-image.png';
						        }
						    } else {
								$image = WC_NEWSLETTER_GENERATOR_URL . 'assets/default-product-image.png';
						    }		
							$blocks[$block_id][$mode]['image'] = $image;
						    					
						}
						break;
					
					default:
						$blocks[$block_id][$mode][$arg_key] = sanitize_text_field( $arg );
						break;
				}
			}
		}

		// Save the blocks value
		update_post_meta( $post_id, '_newsletter_blocks', $blocks );

		// Get the updated postmeta
		$new_blocks = get_post_meta( $post_id, '_newsletter_blocks', true );

		return $new_blocks[$block_id][$mode];
	}

	/**
	 * Change the status of the post based on the ID given
	 * 
	 * @param array containing post ID
	 * 
	 * @return preview URL
	 */
	function change_template( $params = array() ){
		// Default values
		$defaults = array(
			'post_id' 	=> false,
			'template'	=> false
		);

		// Parse args
		$params = wp_parse_args( $params, $defaults );
		extract( $params );

		// Make sure that there's post ID and template value to be modified
		if( !$post_id || !$template ){
			return false;
		}

		// Make sure that the template that will be saved is registered
		if( !in_array( $template, $this->wc_newsletter_generator->templates_list() ) ){
			return false;
		}		

		// Get post status
		$post = get_post( $post_id );

		// If this is auto-draft, change it into draft
		if( 'auto-draft' == $post->post_status ){
			$post_updated = wp_update_post( array(
				'ID' 			=> $post_id,
				'post_status' 	=> 'draft'
			) );
		}

		// Update template value
		update_post_meta( $post_id, '_wcng_template', $template );

		// return preview URL
		return site_url( "?post_type=newsletter&p={$post_id}&preview=true" );
	}
}
new WC_Newsletter_Generator_Ajax;