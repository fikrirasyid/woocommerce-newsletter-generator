<?php
class WC_Newsletter_Generator_Editor{
	var $prefix;
	var $wc_newsletter_generator;

	function __construct(){
		$this->prefix = 'wcng_';
		$this->wc_newsletter_generator = new WC_Newsletter_Generator;

		add_action( 'admin_print_styles', 	array( $this, 'enqueue_styles_scripts' ) );
		add_action( 'add_meta_boxes', 		array( $this, 'register_meta_box' ) );
		add_action( 'save_post', 			array( $this, 'save_meta_box' ) );
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
		global $post;

		if( 'publish' == $post->post_status ){
			add_meta_box('newsletter-markup-metabox', __( 'Newsletter HTML Code', 'woocommerce-newsletter-generator' ), array( $this, 'meta_box_markup' ), 'newsletter' );
		}

		add_meta_box('newsletter-metabox', __( 'Newsletter', 'woocommerce-newsletter-generator' ), array( $this, 'meta_box' ), 'newsletter' );		
	}

	/**
	 * Save meta box's value
	 * 
	 * @since 0.1
	 * 
	 * @param int Post ID
	 * 
	 * @return void
	 */
	function save_meta_box( $post_id ){
		$screen = get_current_screen();

		// Only run this on newsletter editor screen
		if ($screen != null && $screen->post_type != 'newsletter') 
			return;

		// Cancel if this is an autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// Verify nonce
		if( !isset( $_POST['wcng_nonce'] ) || 
			!wp_verify_nonce( $_POST['wcng_nonce'], "wcng_save_{$post_id}" ) ) 
			return;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_posts' ) ) return;

		// Updating process
		if( isset( $_POST['wcng_campaign_name'] ) ) 
			update_post_meta( $post_id, '_wcng_campaign_name', sanitize_text_field( $_POST['wcng_campaign_name'] ) ); 

	}

	/**
	 * Render meta box
	 * 
	 * @return void
	 */
	function meta_box(){
		global $post;

		// Set previewer visibility. auto-draft will not see the previewer until the status of the draft is changed into draft
		// which will be changed when the template is selected
		if( 'auto-draft' == $post->post_status ){
			$display = 'none';
		} else {
			$display = 'block';
		}

		?>
			<p>
				<label for="wcng_campaign_name"><?php _e( 'Campaign Name', 'woocommerce-newsletter-generator' ); ?></label>
				<input type="text" class="wide" name="wcng_campaign_name" id="campaign_wcng_name" value="<?php echo get_post_meta( $post->ID, '_wcng_campaign_name', true ); ?>" placeholder="<?php _e( 'e.g. Newsletter', 'woocommerce-newsletter-generator' ); ?>">
				<span class="description" style="display: block; margin: 10px 0 20px;">
					<?php _e( 'WooCommerce Newsletter Generator automatically append Google Analytics\'s parameter on your email\'s links on your email with.', 'woocommerce-newsletter-generator' ); ?>
				</span>
			</p>
			<p>
				<label for="wcng_select_template"><?php _e( 'Select Template', 'woocommerce-newsletter-generator' ); ?></label>
				<select name="wcng_select_template" id="wcng_select_template">
					<option value=""><?php _e( 'Select Template', 'woocommerce-newsletter-generator' ); ?></option>
					<?php 
						if( is_array( $this->wc_newsletter_generator->templates_list() ) ){

							// Get currently saved template
							$template = $this->wc_newsletter_generator->get_template( $post->ID );

							foreach ($this->wc_newsletter_generator->templates_list() as $option) {

								// Apply selected="selected" if the value has been saved
								if( $option == $template ){
									echo "<option value='$option' selected='selected'>$option</option>";									
								} else {
									echo "<option value='$option'>$option</option>";									
								}

							}

						}
					?>
				</select>
			</p>

			<div id="newsletter-preview" 
				data-preview-url="<?php echo site_url( "?post_type=newsletter&p={$post->ID}&preview=true" ); ?>"
				data-endpoint="<?php echo admin_url(); ?>admin-ajax.php?action=wcng_endpoint" 
				data-post-id="<?php echo $post->ID; ?>" 
				data-nonce="<?php echo wp_create_nonce( "change_template_{$post->ID}" ); ?>"
				style="display: <?php echo $display; ?>;">				
				<span><?php _e( 'Initializing newsletter preview screen...', 'woocommerce-newsletter-generator' ); ?></span>
			</div>
		<?php

		wp_nonce_field( "wcng_save_{$post->ID}", 'wcng_nonce' );

		//  
	}

	/**
	 * Render meta box for copy pasting markup
	 * 
	 * @return void
	 */
	function meta_box_markup(){
		global $post;

		?>
			<textarea id="newsletter-markup" data-permalink="<?php echo get_the_permalink( $post->ID ); ?>" disabled="disabled"><?php echo htmlspecialchars( file_get_contents( get_the_permalink( $post->ID ) ) ); ?></textarea>
		<?php
	}
}
new WC_Newsletter_Generator_Editor;