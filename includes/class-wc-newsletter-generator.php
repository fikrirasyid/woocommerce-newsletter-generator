<?php

class WC_Newsletter_Generator{
	/**
	 * Define templates information
	 * 
	 * @return array
	 */
	function templates(){
		$templates = array(
			'default' => array(
				'name' 					=> 'default',
				'path' 					=> WC_NEWSLETTER_GENERATOR_DIR . 'templates/default.php',
				'product_image_sizes'   => array( 
					array( 
						'id' 		=> 'wcng-product-thumb', 
						'width' 	=> 160, 
						'height' 	=> 220, 
						'hard_crop' => true 
					),
				)
			)
		);

		return apply_filters( 'wc_newsletter_generator_templates', $templates );		
	}

	/**
	 * Define templates list
	 * 
	 * @return array
	 */
	function templates_list(){
		$templates = array();

		if( is_array( $this->templates() ) ){

			foreach ($this->templates() as $key => $value) {
				array_push( $templates, $key );
			}

		}

		return $templates;
	}

	/**
	 * Function define image sizes
	 * 
	 * 
	 * @return array
	 */
	function image_sizes(){
		$templates = $this->templates();

		$image_sizes = array();

		// Check if templates list is an array
		if( is_array( $templates ) ){

			// Loop the template list
			foreach ($templates as $template ) {
				
				// Check if the product image size exists and it's an array
				if( isset( $template['product_image_sizes'] ) && is_array( $template['product_image_sizes'] ) ){

					// Loop the product image sizes
					foreach ($template['product_image_sizes'] as $image_size ) {

						// Push the image size
						array_push( $image_sizes, $image_size );
					}
				}
			}
		}

		return $image_sizes;
	}
}