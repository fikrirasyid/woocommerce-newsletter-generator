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
		global $post;

		$templates = new WC_Newsletter_Generator;

		// Set previewer visibility. auto-draft will not see the previewer until the status of the draft is changed into draft
		// which will be changed when the template is selected
		if( 'auto-draft' == $post->post_status ){
			$display = 'none';
		} else {
			$display = 'block';
		}

		?>
			<p>
				<label for="wcng_select_template"><?php _e( 'Select Template', 'woocommerce-newsletter-generator' ); ?></label>
				<select name="wcng_select_template" id="wcng_select_template">
					<option value=""><?php _e( 'Select Template', 'woocommerce-newsletter-generator' ); ?></option>
					<?php 
						if( is_array( $templates->templates_list() ) ){

							foreach ($templates->templates_list() as $option) {

								echo "<option value='$option'>$option</option>";

							}

						}
					?>
				</select>
			</p>

			<div id="newsletter-preview" 
				data-endpoint="<?php echo admin_url(); ?>admin-ajax.php?action=wcng_endpoint" 
				data-post-id="<?php echo $post->ID; ?>" 
				data-status="<?php echo $post->post_status?>"
				data-nonce="<?php echo wp_create_nonce( "change_auto_draft_{$post->ID}" ); ?>"
				style="display: <?php echo $display; ?>;">				
				<span><?php _e( 'Initializing newsletter preview screen...', 'woocommerce-newsletter-generator' ); ?></span>
			</div>
		<?php

		//  
	}
}
new WC_Newsletter_Generator_Editor;