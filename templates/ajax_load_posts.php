<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	 $params = $_REQUEST;  
	 $category_id =( ( isset( $params["category_id"] ) && intval( $params["category_id"] ) > 0  ) ? intval( $params["category_id"] ) : ( ( isset( $params["category_id"] ) && trim( $params["category_id"] ) == "all" ) ? "all" : "" ) );  
	 $_limit_start =( isset( $params["limit_start"] ) ? intval( $params["limit_start"] ) : 0 );
	 $_limit_end = intval( $params["number_of_post_display"] );
	 $is_default_category_with_hidden = 0; 
	 
	?><script language='javascript'>
		var request_obj_<?php echo esc_js( $params["vcode"] ); ?> = {
			category_id:'<?php echo esc_js( $category_id ); ?>',   
			hide_post_title:'<?php echo esc_js( $params["hide_post_title"] ); ?>',  
			post_title_color:'<?php echo esc_js( $params["post_title_color"] ); ?>', 
			category_tab_text_color:'<?php echo esc_js( $params["category_tab_text_color"] ); ?>',
			category_tab_background_color:'<?php echo esc_js( $params["category_tab_background_color"] ); ?>', 
			header_text_color:'<?php echo esc_js( $params["header_text_color"] ); ?>', 
			header_background_color:'<?php echo esc_js( $params["header_background_color"] ); ?>',
			display_title_over_image:'<?php echo esc_js( $params["display_title_over_image"] ); ?>', 
			number_of_post_display:'<?php echo esc_js( $params["number_of_post_display"] ); ?>', 
			vcode:'<?php echo esc_js( $params["vcode"] ); ?>'
		}
	</script><?php   
	$_total_posts = $this->getTotalProducts( $category_id, 1, $is_default_category_with_hidden );
	if( $_total_posts <= 0 ) {
		?><div class="ik-post-no-items"><?php echo __( 'No products found.', 'wccategorytab' ); ?></div><?php
		die();
	}
	$post_list = $this->getProductList( $category_id, $_limit_end );	 
	
	foreach ( $post_list as $_post ) { 
		$image  = $this->getProductImage( $_post->post_image ); 
		$wc_product = wc_get_product( $_post->post_id );
		$add_to_cart_url = esc_url( $wc_product->add_to_cart_url() );
		$add_to_cart_text = $wc_product->add_to_cart_text();  
		
		$_is_view = "bt-cart";
		if( esc_url( get_permalink( $_post->post_id ) ) == $add_to_cart_url ) {
			$add_to_cart_text = __( 'View Detail', 'wccategorytab' ); 
			$_is_view = "product_wc_view";
		}	
		else {
			$add_to_cart_text = __( 'Add to Cart', 'wccategorytab' );
			$add_to_cart_url = get_permalink($_post->post_id)."/?add-to-cart=".$_post->post_id; 
		}
		?>
		<div class='ik-post-item pid-<?php echo esc_attr( $_post->post_id ); ?>'> 
			<div class='ik-post-image' onmouseout="wcpt_pr_item_image_mouseout(this)" onmouseover="wcpt_pr_item_image_mousehover(this)">
					<a href="<?php echo get_permalink( $_post->post_id ); ?>">
					<div class="ov-layer" > 
						 <?php if( sanitize_text_field( $params["display_title_over_image"] ) == 'yes' ) { ?> 
								<div class='ik-overlay-post-content'>
									<?php if( sanitize_text_field( $params["hide_post_title"] ) == 'no' ) { ?> 
										<div class='ik-post-name' style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>" >
											  <span  style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>">
												<?php echo esc_html( $_post->post_title ); ?>
											  </span>	
										</div>
									<?php } ?>   
									<div class='ik-product-sale-price' style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>" >
										<?php echo get_woocommerce_currency_symbol().$_post->sale_price; ?>
									</div> 
									<div class='ik-product-sale-btn-price' style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>" >
										<?php echo do_shortcode("[add_to_cart show_price='false' style='' id = '".$_post->post_id."']"); ?> 
									</div>
									<div class="clr"></div>
								</div>
								<div class="clr"></div>
						<?php } ?>
					</div>
					<div class="clr"></div>
				</a>
				<div class="clr"></div>
				<a href="<?php echo get_permalink( $_post->post_id ); ?>"> 
					<?php echo $image; ?>
				</a>   
			</div>  
			<?php if( sanitize_text_field( $params["display_title_over_image"] ) == 'no' ) { ?> 
				<div class='ik-post-content'>
					<?php if( sanitize_text_field( $params["hide_post_title"] ) =='no'){ ?> 
						<div class='ik-post-name'>
							<a href="<?php echo get_permalink( $_post->post_id ); ?>" style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>" >
								<?php echo esc_html( $_post->post_title ); ?>
							</a>	
						</div>
					<?php } ?>	  
					<div class='ik-product-sale-price' style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>" >
						<?php echo get_woocommerce_currency_symbol().$_post->sale_price; ?>
					</div> 
					<div class='ik-product-sale-btn-price' style="color:<?php echo esc_attr( $params["post_title_color"] ); ?>" >
						<?php echo do_shortcode("[add_to_cart show_price='false' style='' id = '".$_post->post_id."']"); ?> 
					</div>
				</div>	
			<?php } ?> 
		</div> 
		<?php 
	}
	
	if( $_total_posts > sanitize_text_field( $params["number_of_post_display"] ) ) { ?>
			<div class="clr"></div>
			<div class='ik-post-load-more'  align="center" onclick='WCPT_loadMoreProducts( "<?php echo esc_js( $category_id ); ?>", "<?php echo esc_js( $_limit_start+$_limit_end ); ?>", "<?php echo esc_js( $params["vcode"]."-".$category_id ); ?>", "<?php echo esc_js( $_total_posts ); ?>", request_obj_<?php echo esc_js( $params["vcode"] ); ?> )'>
				<?php echo __('Load More', 'wccategorytab' ); ?>
			</div>
		<?php  
	} else {
		?><div class="clr"></div><?php
	}