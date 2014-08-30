<?php
  if( have_posts() ):
    while( have_posts() ) : the_post();

    $wcng = wcng_data( get_the_ID() );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php the_title(); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />

    <?php wcng_email_header(); ?>
  </head>
  <body style="width: 100% !important; min-width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0; padding: 0;">

    <style type="text/css">
      #footer a:hover {
      color: #333333 !important;
      }
      #footer a:active {
      color: #999999 !important;
      }
      a:hover {
      color: #2795b6 !important;
      }
      a:active {
      color: #2795b6 !important;
      }
      a:visited {
      color: #2ba6cb !important;
      }
      h1 a:active {
      color: #2ba6cb !important;
      }
      h2 a:active {
      color: #2ba6cb !important;
      }
      h3 a:active {
      color: #2ba6cb !important;
      }
      h4 a:active {
      color: #2ba6cb !important;
      }
      h5 a:active {
      color: #2ba6cb !important;
      }
      h6 a:active {
      color: #2ba6cb !important;
      }
      h1 a:visited {
      color: #2ba6cb !important;
      }
      h2 a:visited {
      color: #2ba6cb !important;
      }
      h3 a:visited {
      color: #2ba6cb !important;
      }
      h4 a:visited {
      color: #2ba6cb !important;
      }
      h5 a:visited {
      color: #2ba6cb !important;
      }
      h6 a:visited {
      color: #2ba6cb !important;
      }
      table.button:hover td {
      background: #2795b6 !important;
      }
      table.button:visited td {
      background: #2795b6 !important;
      }
      table.button:active td {
      background: #2795b6 !important;
      }
      table.button:hover td a {
      color: #fff !important;
      }
      table.button:visited td a {
      color: #fff !important;
      }
      table.button:active td a {
      color: #fff !important;
      }
      table.button:hover td {
      background: #2795b6 !important;
      }
      table.tiny-button:hover td {
      background: #2795b6 !important;
      }
      table.small-button:hover td {
      background: #2795b6 !important;
      }
      table.medium-button:hover td {
      background: #2795b6 !important;
      }
      table.large-button:hover td {
      background: #2795b6 !important;
      }
      table.button:hover td a {
      color: #ffffff !important;
      }
      table.button:active td a {
      color: #ffffff !important;
      }
      table.button td a:visited {
      color: #ffffff !important;
      }
      table.tiny-button:hover td a {
      color: #ffffff !important;
      }
      table.tiny-button:active td a {
      color: #ffffff !important;
      }
      table.tiny-button td a:visited {
      color: #ffffff !important;
      }
      table.small-button:hover td a {
      color: #ffffff !important;
      }
      table.small-button:active td a {
      color: #ffffff !important;
      }
      table.small-button td a:visited {
      color: #ffffff !important;
      }
      table.medium-button:hover td a {
      color: #ffffff !important;
      }
      table.medium-button:active td a {
      color: #ffffff !important;
      }
      table.medium-button td a:visited {
      color: #ffffff !important;
      }
      table.large-button:hover td a {
      color: #ffffff !important;
      }
      table.large-button:active td a {
      color: #ffffff !important;
      }
      table.large-button td a:visited {
      color: #ffffff !important;
      }
      table.secondary:hover td {
      background: #d0d0d0 !important; color: #555;
      }
      table.secondary:hover td a {
      color: #555 !important;
      }
      table.secondary td a:visited {
      color: #555 !important;
      }
      table.secondary:active td a {
      color: #555 !important;
      }
      table.success:hover td {
      background: #457a1a !important;
      }
      table.alert:hover td {
      background: #970b0e !important;
      }
    </style>

    <table class="body" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; height: 100%; width: 100%; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;">
      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
        <td class="center" align="center" valign="top" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;">
          <center style="width: 100%; min-width: 580px;">

            <table class="container" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: inherit; width: 580px; background: white; margin: 0 auto; padding: 0;" bgcolor="white">
              <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                <td class="container-inside" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                  
                  <table class="row" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;">
                    <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                      <td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0 0px 0 0;" align="left" valign="top">
                        
                        <table class="twelve columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;">
                          <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                            <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                              
                              <table id="content" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; padding: 0;">
                                
                                <tr id="view-on-browser" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td colspan="7" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 12px; margin: 0; padding: 20px 0;" align="left" valign="top">
                                      <?php printf( __( 'Email does not displayed correctly? <a href="%s">View it on your browser</a>' ), get_the_permalink() ); ?>
                                  </td>
                                </tr><!-- #view-on-browser -->

                                <tr id="header" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td colspan="7" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                                    <?php wcng_image_block( 'header', 580, 60, get_bloginfo( 'name' ), false, site_url() ); ?>
                                  </td>
                                </tr><!-- #header -->
                                
                                <tr id="main-banner" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td colspan="7" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                                    <?php wcng_image_block( 'main-banner', 580, 225 ); ?>
                                  </td>
                                </tr><!-- #main-banner -->

                                <tr id="spacer-below-main-banner" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td colspan="7" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                                    <br />
                                  </td>
                                </tr><!-- #spacer-below-main-banner -->

                                <tr id="products" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                                    
                                     <table style="width: 100%; border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; padding: 0;">

                                      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-1-1', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-1-2', 'wcng-product-thumb'  ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-1-3', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>
                                      
                                      </tr>

                                      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-2-1', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-2-2', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-2-3', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>
                                      
                                      </tr>

                                      </table>

                                    </td>
                                </tr><!-- #products -->
                                
                                <tr id="secondary-banner" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td colspan="7" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                                    <?php wcng_image_block( 'secondary-banner', 580, 225 ); ?>
                                  </td>
                                </tr><!-- #secondary-banner --> 

                                <tr id="spacer-below-secondary-banner" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td colspan="7" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; height: 60px;" align="left" valign="top">
                                    <br />
                                  </td>
                                </tr><!-- #spacer-below-secondary-banner -->

                                <tr id="secondary-products" style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                  <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">
                                    
                                     <table style="width: 100%; border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; padding: 0;">

                                      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-3-1', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-3-2', 'wcng-product-thumb'  ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-3-3', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>
                                      
                                      </tr>

                                      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-4-1', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-4-2', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>

                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 160px; height: 310px;" align="left" valign="top">
                                          <?php wcng_product_block( 'product-4-3', 'wcng-product-thumb' ); ?>
                                        </td>
                                        
                                        <td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0; width: 20px; content: '';" align="left" valign="top"></td>
                                      
                                      </tr>

                                      </table>

                                    </td>
                                </tr><!-- #secondary-products -->                                

                                <tr id="footer" style="vertical-align: top; text-align: left; background: #f1f2f5; padding: 0;" align="left" bgcolor="#f1f2f5"><td colspan="7" bgcolor="f1f2f2" style="text-align: center; font-size: 12px; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; margin: 0; padding: 10px 0;" align="center" valign="top">
                                    <table style="width: 100%; border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; padding: 0;">
                                      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td colspan="3" style="text-align: center; font-size: 12px; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; margin: 0; padding: 10px 0;" align="center" valign="top">
                                          <span style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #808285; line-height: 1.4; display: block; text-align: center; padding: 10px 55px 20px;">
                                            <?php
                                              wcng_text_block( 'footer', __( "Footer information goes here. I personally encourage you to create new HTML email template to adjust to your brand's need then use it instead of keep on changing this default template.", 'woocommerce-newsletter-generator' ) );
                                            ?>                                            
                                          </span>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr><!-- #footer -->

                              </table><!-- #content -->

                            </td>
                          </tr>
                        </table><!-- .twelve.columns -->

                      </td>
                    </tr>
                  </table><!-- .row -->

                </td>
              </tr>
            </table><!-- .container -->
          </center>
        </td>
      </tr>
    </table><!-- .body -->

    <?php wcng_email_footer(); ?>

  </body>
</html>

<?php
      endwhile;
    endif;
?>