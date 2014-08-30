<?php
/**
 * Conditional tag to determine wheter current user can edit newsletter or not
 */
function wcng_current_user_can_edit_newsletter(){
  if( is_user_logged_in() && current_user_can( 'edit_others_pages' ) ){
    return true;
  } else {
    return false;
  }
}

/**
 * Email header for hooking scripts and stylesheet for admin
 * 
 * @return void
 */
function wcng_email_header(){
	if( wcng_current_user_can_edit_newsletter() ){
    wp_head();
	}

	do_action( 'wcng_email_header' );
}

/**
 * Email footer for hooking helper block for admin
 * 
 * @return void
 */
function wcng_email_footer(){
  if( wcng_current_user_can_edit_newsletter() ){
    global $post;
  ?>
    <div id='modal-background'></div><!-- #modal-background -->

    <div id="block-selector">
      <h2 id="selector-title"><?php _e( 'Edit Content: ', 'woocommerce-newsletter-generator' ); ?><span class="var-html" data-param="id"></span></h2>
      <button id="close-selector"><?php _e( 'Close', 'woocommerce-newsletter-generator' ); ?></button>
      
      <form action="<?php the_permalink(); ?>" class="edit-block-form" id="edit-image">
        <p>
          <label for="edit-image-image"><?php _e( 'Image', 'woocommerce-newsletter-generator' ); ?></label>
          <img src="" id="edit-image-preview">
          <span style="display: block;">            
            <button id="edit-image-change-image"><?php _e( 'Change Image', 'woocommerce-newsletter-generator' ); ?></button>
          </span>
          <input type="text" name="edit-image-image" id="edit-image-image" placeholder="" value="" disabled="disabled" />
        </p>
        <p>
          <label for="edit-image-text"><?php _e( 'Text', 'woocommerce-newsletter-generator' ); ?></label>
          <input type="text" name="edit-image-text" id="edit-image-text" placeholder="<?php _e( 'Type the text/description of your image here...', 'woocommerce-newsletter-generator' ); ?>" value="" />
        </p>
        <p>
          <label for="edit-image-href"><?php _e( 'Link', 'woocommerce-newsletter-generator' ); ?></label>
          <input type="text" name="edit-image-href" id="edit-image-href" placeholder="<?php _e( 'http://', 'woocommerce-newsletter-generator' ); ?>" value="" />
        </p>
        <input type="submit" value="<?php _e( 'Update', 'woocommerce-newsletter-generator' ); ?>">
      </form><!-- #edit-image -->

      <form action="<?php the_permalink(); ?>" class="edit-block-form" id="edit-product">
        <p>
          <label for=""><?php _e( 'Select Product', 'woocommerce-newsletter-generator' ); ?></label>
        </p>
        
        <ul id="select-product-list">
          <?php wcng_the_products(); ?>
        </ul>
        
        <div id="loading-more-products">
          <img src="<?php echo site_url('/wp-includes/images/wpspin-2x.gif'); ?>" alt="<?php _e( 'Loading More Products...', 'woocommerce-newsletter-generator' ); ?>"> <span class="label"><?php _e( 'Loading More Products...', 'woocommerce-newsletter-generator' ); ?></span>
        </div>
        <button id="load-more-products" data-paged="2"><?php _e( 'Load More Products', 'woocommerce-newsletter-generator' ); ?></button>
      </form><!-- #edit-product -->
      
      <form action="<?php the_permalink(); ?>" class="edit-block-form" id="edit-text">
        <p>
          <label for="edit-text-text"><?php _e( 'Edit Text', 'woocommerce-newsletter-generator' ); ?></label>
          <textarea name="edit-text-text" id="edit-text-text"></textarea>
        </p>

        <input type="submit" value="<?php _e( 'Update', 'woocommerce-newsletter-generator' ); ?>">
      </form><!-- #edit-text -->      
    </div><!-- #block-selector -->

    <div id="loading-indicator">
      <p></p>
    </div>

    <script id="template-product-item" type="text/template">
          <li>
            <span class="image-wrap">
            </span>

            <span class="product-name">
              <a href="" title=""></a>
            </span>
            <span class="product-price">
            </span>
            <span class="product-action">
              <button class="select-this-product" data-product-id=""><?php _e( 'Select This Product', 'woocommerce-newsletter-generator' ); ?></button>
            </span>
          </li>
    </script><!-- #template-product-item -->
  <?php

  wp_footer();
  }
}

/**
 * Get wcng body data
 * Reducing query for getting product name
 * 
 * @param int post_id
 * 
 * @return array of well-prepared $wcng data
 */
function wcng_data( $post_id ){
  $blocks = get_post_meta( $post_id, '_newsletter_blocks', true );

  return $blocks;
}

/**
 * Text Image Block
 * 
 * @return void
 */
function wcng_image_block( $block_id = 'header', $width = 150, $height = 150, $text = '', $image = false, $link = false ){
  global $wcng;

  if( !$image ){
    switch ( $block_id ) {
      case 'header':
          $image = WC_NEWSLETTER_GENERATOR_URL . 'assets/default-header.png';
        break;
      
      default:
          $image = WC_NEWSLETTER_GENERATOR_URL . 'assets/default-image.png';      
        break;
    }
  }

  // Defining variables
  $image  = wcng_get_value( $block_id, 'image', 'image', $image );
  $text   = wcng_get_value( $block_id, 'image', 'text', $text );
  $link   = wcng_get_value( $block_id, 'image', 'link', $link );
  $width  = intval( $width );
  $height = intval( $height );

  // Print wrapper for admin
  if( wcng_current_user_can_edit_newsletter() ){
    echo "<div class='edit-content-block' data-type='image' data-id='$block_id' data-text='$text' data-image='$image' data-href='$link'>";
    echo "<button class='toggle-edit-block'>". __( 'Edit', 'woocommerce-newsletter-generator' ) ."</button>";

    echo "<div class='the-image'>";
  }

  // Print anchor tag
  if( $link ){
    $link = esc_url( $link );

    echo "<a href='$link' title='$text'>";

  }

  echo "<img src='$image' alt='$text' width='$width' height='$height' style='outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; max-width: 100%; float: left; clear: both; display: block; border: none;' align='left' />";

  // Close anchor tag
  if( $link ){

    echo "</a>";

  }

  // Close wrapper for admin
  if( wcng_current_user_can_edit_newsletter() ){
    echo "</div>";
    echo "</div>";
  }
}

/**
 * Product Block
 */
function wcng_product_block( $block_id = '', $product_image_size = 'wcng-product-thumb' ){
  global $wcng;
  
  // Get product data
  $product_id = wcng_get_value( $block_id, 'product', 'product_id', 0 );
  $permalink  = wcng_get_value( $block_id, 'product', 'permalink', '#');
  $title      = wcng_get_value( $block_id, 'product', 'title', __( 'Product Name', 'woocommerce-newsletter-generator' ) );
  $price      = wcng_get_value( $block_id, 'product', 'price', '-' );
  $image      = wcng_get_value( $block_id, 'product', 'image', WC_NEWSLETTER_GENERATOR_URL . 'assets/default-product-image.png' );

  // Print wrapper for admin
  if( wcng_current_user_can_edit_newsletter() ){
    echo "<div class='edit-content-block' data-type='product' data-id='$block_id' data-product-id='$product_id' data-product-image-size='$product_image_size'>";
    echo "<button class='toggle-edit-block'>". __( 'Edit', 'woocommerce-newsletter-generator' ) ."</button>";
  }

  ?>
      <!-- .product-item-->
      <table style="border: 0;">
        <tr>
          <td class="product-image">
            <a href="<?php echo $permalink; ?>" title="product_title" style="color: #2ba6cb; text-decoration: none;">
              <img src="<?php echo $image; ?>" width="160" height="220" alt="" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; max-width: 100%; float: left; clear: both; display: block; border: none;" align="left" />
            </a>                                              
          </td>                                            
        </tr>
        <tr style="text-align: center;">
          <td class="product-name">
            <a href="<?php echo $permalink; ?>" title="product_title" style="color: #2ba6cb; text-decoration: none;">
              <?php echo $title; ?>
            </a>
          </td>
        </tr>
        <tr style="text-align: center;">
          <td class="product-price">
            <?php echo $price; ?>
          </td>
        </tr>
      </table>
      <br />
      <!-- /product-item-->
  <?php  

    // Close wrapper for admin
    if( wcng_current_user_can_edit_newsletter() ){
      echo "</div>";
    }
}

/**
 * Text Block
 */
function wcng_text_block( $block_id = 'footer', $default = ''){
  global $wcng;

  $text = wcng_get_value( $block_id, 'text', 'text', $default );

  // Print wrapper for admin
  if( wcng_current_user_can_edit_newsletter() ){
    echo "<div class='edit-content-block' data-type='text' data-id='$block_id'>";
    echo "<button class='toggle-edit-block'>". __( 'Edit', 'woocommerce-newsletter-generator' ) ."</button>";
    echo '<div class="the-text">';
  }  

  echo esc_textarea( $text );

  // Close wrapper for admin
  if( wcng_current_user_can_edit_newsletter() ){
    echo "</div>";
    echo "</div>";
  }  
}

/**
 * Get products
 */
function wcng_the_products(){
  $posts = new WP_Query(array(
    'post_type' => 'product'
  ));

  if( $posts->have_posts() ){
    while( $posts->have_posts() ){
      $posts->the_post();

      $product = new WC_Product( get_the_ID() );
      ?>
          <li>
            <span class="image-wrap">
              <?php
                if( has_post_thumbnail() ){
                  the_post_thumbnail( 'thumbnail' );
                }
              ?>
            </span>

            <span class="product-name">
              <a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </span>
            <span class="product-price">
              <?php echo $product->get_price_html(); ?>
            </span>
            <span class="product-action">
              <button class="select-this-product" data-product-id="<?php the_ID(); ?>"><?php _e( 'Select This Product', 'woocommerce-newsletter-generator' ); ?></button>
            </span>
          </li>
      <?php
    }
  }

  wp_reset_postdata();
}

/**
 * Get values from wcng_data
 * 
 * @param string of block ID
 * @param string of mode (image|text|product)
 * @param string of property (text|image|id|permalink)
 * @param mixed of default value
 * 
 * @return string of fetched value
 */
function wcng_get_value( $block_id, $mode, $property, $default = '' ){
    global $wcng;

    if( isset( $wcng[$block_id][$mode][$property] ) ){
      return $wcng[$block_id][$mode][$property];
    } else {
      return $default;
    }
}