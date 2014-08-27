<?php
class WC_Newsletter_Generator_Editor{
	var $prefix;

	function __construct(){
		$this->prefix = 'wcng_';

		add_action( 'admin_print_styles', 	array( $this, 'enqueue_styles_scripts' ) );
		add_action( 'add_meta_boxes', 		array( $this, 'register_meta_box' ) );
	}

	/**
	 * Enqueueing styles and scripts on the editor page
	 * 
	 * @return void
	 */
	function enqueue_styles_scripts( $hook ){
		$screen = get_current_screen();

		if( 'newsletter' == $screen->post_type ){
			wp_enqueue_style( 'wc_newsletter_generator_editor', WC_NEWSLETTER_GENERATOR_URL . 'css/wc-newsletter-generator-editor.css', array(), false, 'all');
	        wp_enqueue_script( 'wc_newsletter_generator_editor', WC_NEWSLETTER_GENERATOR_URL . 'js/wc-newsletter-generator-editor.js', array( 'jquery' ), '20140827', true );
		}
	}

	/**
	 * Registering meta box
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	function register_meta_box(){
		add_meta_box('newsletter-metabox', __( 'Newsletter', 'woocommerce-newsletter-generator' ), array( $this, 'meta_box' ), 'newsletter' );		
	}

	/**
	 * Render meta box
	 * 
	 * @return void
	 */
	function meta_box(){
		$templates = new WC_Newsletter_Generator;

		?>
			<p>
				<label for="<?php echo $this->prefix?>template"><?php _e( 'Select Template', 'woocommerce-newsletter-generator' ); ?></label>
				<select name="<?php echo $this->prefix?>-template" id="<?php echo $this->prefix?>-template">
					<?php 
						if( is_array( $templates->templates_list() ) ){

							foreach ($templates->templates_list() as $option) {

								echo "<option value='$option'>$option</option>";

							}

						}
					?>
				</select>
			</p>

			<div id="newsletter-preview">
				
			</div>
		<?php

		//  
	}
}
new WC_Newsletter_Generator_Editor;