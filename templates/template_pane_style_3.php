<?php if ( ! defined( 'ABSPATH' ) ) exit;   $vcode = $this->_config["vcode"];   ?>
 <script type='text/javascript' language='javascript'>
	var default_category_id_<?php echo esc_js( $vcode ); ?> = '<?php echo  $this->_config["category_id"] ; ?>';
	var request_obj_<?php echo esc_js( $vcode ); ?> = {
			category_id:'<?php echo  $this->_config["category_id"] ; ?>',    
			hide_post_title:'<?php echo esc_js( $this->_config["hide_post_title"] ); ?>', 
			post_title_color:'<?php echo esc_js( $this->_config["title_text_color"] ); ?>',
			category_tab_text_color:'<?php echo esc_js( $this->_config["category_tab_text_color"] ); ?>', 
			category_tab_background_color:'<?php echo esc_js( $this->_config["category_tab_background_color"] ); ?>',
			header_text_color:'<?php echo esc_js( $this->_config["header_text_color"] ); ?>', 
			header_background_color:'<?php echo esc_js( $this->_config["header_background_color"] ); ?>',
			display_title_over_image:'<?php echo esc_js( $this->_config["display_title_over_image"] ); ?>', 
			number_of_post_display:'<?php echo esc_js( $this->_config["number_of_post_display"] ); ?>',  
			vcode:'<?php echo esc_js( $vcode ); ?>'
		}
 </script>  
<?php    
$_categories = $this->_config["category_id"]; 
?> 
<div id="wccategorytab" style="width:<?php echo $this->_config["tp_widget_width"]; ?>"  class=" pane_style_3 <?php echo ( ( trim( $this->_config["display_title_over_image"] ) == "yes" ) ? "disp_title_over_img" : "" ); ?>">
	<?php if($this->_config["hide_widget_title"]=="no"){ ?>
		<div class="ik-pst-tab-title-head" style="background-color:<?php echo esc_attr( $this->_config["header_background_color"] ); ?>;color:<?php echo esc_attr( $this->_config["header_text_color"] ); ?>"  >
			<?php echo esc_html( $this->_config["widget_title"] ); ?>   
		</div>
	<?php } ?> 
	<span class='wp-load-icon'>
		<img width="18px" height="18px" src="<?php echo WCPT_MEDIA.'images/loader.gif'; ?>" />
	</span>
	<div class="wea_content lt-tab">
		<?php  
			$_category_res = array();
			$_total_post_count = 0;
			$_category_res_n = array();
			$_copened_id = 0;
			if( trim($_categories)=="0" || trim($_categories) == "" )
				$_category_res = $this->getCategories();
			else 
				$_category_res = $this->getCategories($_categories); 
				 
			if( count( $_category_res ) > 0 ) { 
				 
			
				foreach( $_category_res as $_category ) { 
					$_total_post_count = $_total_post_count + $_category->count;
				} 
				
				$_copen = 0;
				foreach( $_category_res as $_category ) {  
				
					$_category_name = $_category->category;
					$_category_id = $_category->id; 
					$_post_count = 0; 
					$_copen = $_copen + 1; 
					if($_copen==1)
					$_copened_id = $_category->id;
					?>
					<div class="item-pst-list">
						<div class="pst-item <?php echo ((( $_copen == 1  ))?"pn-active":""); ?>"  onmouseout="wcpt_cat_tab_ms_out( this )" onmouseover="wcpt_cat_tab_ms_hover( this )" id="<?php echo $vcode.'-'.$_category_id; ?>" onclick="WCPT_fillProducts( this.id, '<?php echo esc_js($_category_id ); ?>', request_obj_<?php echo esc_js( $vcode ); ?>, 1 )"  style="color:<?php echo esc_attr($this->_config["category_tab_text_color"] ); ?>;background-color:<?php echo esc_attr( $this->_config["category_tab_background_color"] ); ?>;" >
							<div class="pst-item-text"  onmouseout="wcpt_cat_tab_ms_out( this.parentNode )" onmouseover="wcpt_cat_tab_ms_hover( this.parentNode )">
								<?php 
							 
									echo esc_html( $_category_name );   
									 
								?>								
							</div>
							<div class="ld-pst-item-text"></div>
							<div class="clr"></div>
						</div>		 
						<div class="clr"></div>
					 </div>  
				   <?php
				   
				}
				
			} 
		?>
		<div class="clr"></div>
		<div class="item-posts">
			<?php  
					// Default category opened category start 
					if( trim( $_copened_id ) != "" && trim( $_copened_id ) != "0"  ) { 
					  
						 $category_id = $_copened_id;
						 $_limit_start = 0;
						 $_limit_end = $this->_config["number_of_post_display"]; 
						
						if(trim($_copened_id) != "all"){
							$__current_term = get_term($_copened_id);
							$__current_term_count =  $__current_term->count;
						}
						else
						{ 
						   $__current_term_count =  $_total_post_count;
						} 
						?><script language='javascript'>
							var request_obj_<?php echo esc_js( $vcode ); ?> = {
								category_id:'<?php echo esc_js( $category_id ); ?>',   
								hide_post_title:'<?php echo esc_js( $this->_config["hide_post_title"] ); ?>',  
								post_title_color:'<?php echo esc_js( $this->_config["title_text_color"] ); ?>', 
								category_tab_text_color:'<?php echo esc_js( $this->_config["category_tab_text_color"] ); ?>',
								category_tab_background_color:'<?php echo esc_js( $this->_config["category_tab_background_color"] ); ?>', 
								header_text_color:'<?php echo esc_js( $this->_config["header_text_color"] ); ?>', 
								header_background_color:'<?php echo esc_js( $this->_config["header_background_color"] ); ?>',
								display_title_over_image:'<?php echo esc_js( $this->_config["display_title_over_image"] ); ?>', 
								number_of_post_display:'<?php echo esc_js( $this->_config["number_of_post_display"] ); ?>', 
								vcode:'<?php echo esc_js( $vcode ); ?>'
							}
						</script><?php   
						$_total_posts =  $__current_term_count;
						if( $_total_posts <= 0 ) {
							?><div class="ik-post-no-items"><?php echo __( 'No products found.', 'wccategorytab' ); ?></div></div><?php  
						}  
						$post_list = $this->getSqlResult( $category_id, 0, $_limit_end ); 
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
											 <?php if( sanitize_text_field( $this->_config["display_title_over_image"] ) == 'yes' ) { ?> 
													<div class='ik-overlay-post-content'>
														<?php if( sanitize_text_field( $this->_config["hide_post_title"] ) == 'no' ) { ?> 
															<div class='ik-post-name' >
																 <span style="color:<?php echo esc_attr( $this->_config["title_text_color"] ); ?>" ><?php echo esc_html( $_post->post_title ); ?></span>
															</div>
														<?php } ?>  	 
														<div class='ik-product-sale-price' style="color:<?php echo esc_attr( $this->_config["title_text_color"] ); ?>" >
															<?php echo get_woocommerce_currency_symbol().$_post->sale_price; ?>
														</div> 
														<div class='ik-product-sale-btn-price' style="color:<?php echo esc_attr( $this->_config["title_text_color"] ); ?>" >
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
								<?php if( sanitize_text_field( $this->_config["display_title_over_image"] ) == 'no' ) { ?> 
									<div class='ik-post-content'>
										<?php if( sanitize_text_field( $this->_config["hide_post_title"] ) =='no'){ ?> 
											<div class='ik-post-name'>
												<a href="<?php echo get_permalink( $_post->post_id ); ?>" style="color:<?php echo esc_attr( $this->_config["title_text_color"] ); ?>" >
													<?php echo esc_html( $_post->post_title ); ?>
												</a>	
											</div>
										<?php } ?>	  
										<div class='ik-product-sale-price' style="color:<?php echo esc_attr( $this->_config["title_text_color"] ); ?>" >
											<?php echo get_woocommerce_currency_symbol().$_post->sale_price; ?>
										</div> 
										<div class='ik-product-sale-btn-price' style="color:<?php echo esc_attr( $this->_config["title_text_color"] ); ?>" >
											<?php echo do_shortcode("[add_to_cart show_price='false' style='' id = '".$_post->post_id."']"); ?> 
										</div>		
									</div>	
								<?php } ?> 
							</div> 
							<?php 
						}
						
						if( $_total_posts > sanitize_text_field( $this->_config["number_of_post_display"] ) ) { ?>
								<div class="clr"></div>
								<div class='ik-post-load-more'  align="center" onclick='WCPT_loadMoreProducts( "<?php echo esc_js( $category_id ); ?>", "<?php echo esc_js( $_limit_start+$_limit_end ); ?>", "<?php echo esc_js( $this->_config["vcode"]."-".$category_id ); ?>", "<?php echo esc_js( $_total_posts ); ?>", request_obj_<?php echo esc_js( $this->_config["vcode"] ); ?> )'>
									<?php echo __('Load More', 'wccategorytab' ); ?>
								</div>
							<?php  
						} else {
							?><div class="clr"></div><?php
						}
					
					} 
					// End Default category opened.
			?> 
		</div>
		<div class="clr"></div>
	</div>
</div>