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
				'name' 		=> 'default',
				'path' 		=> WC_NEWSLETTER_GENERATOR_DIR . 'templates/default.php',
				'fields' 	=> array(
					array(
						'id' 		=> '',
						'type' 		=> 'image',
						'text'		=> '',
						'link'		=> '',
						'img' 		=> WC_NEWSLETTER_GENERATOR_URL . 'assets/default.png'
					),
					array(
						'type' 		=> 'woocommerce_product',
						'default' 	=> 0
					),
					array(
						'type' 		=> 'woocommerce_product',
						'default' 	=> 0
					),
					array(
						'type' 		=> 'woocommerce_product',
						'default' 	=> 0
					),
					array(
						'type' 		=> 'woocommerce_product',
						'default' 	=> 0
					),
					array(
						'type' 		=> 'woocommerce_product',
						'default' 	=> 0
					),
					array(
						'type' 		=> 'woocommerce_product',
						'default' 	=> 0
					)
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
}