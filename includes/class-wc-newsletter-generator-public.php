<?php
/**
 * Handling public facing mechanism
 */
class WC_Newsletter_Generator_Public{
	function __construct(){
		add_filter( 'template_include', array( $this, 'route_template' ) );
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