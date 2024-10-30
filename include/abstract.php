<?php 
/** 
 * Abstract class  has been designed to use common functions.
 * This is file is responsible to add custom logic needed by all templates and classes.  
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   
if ( ! class_exists( 'categoryProductTabLib' ) ) { 
	abstract class categoryProductTabLib extends WP_Widget {
		
	   /**
		* Default values can be stored
		*
		* @access    public
		* @since     1.0
		*
		* @var       array
		*/
		public $_config = array();

		/**
		 * PHP5 constructor method.
		 *
		 * Run the following methods when this class is loaded.
		 * 
		 * @access    public
		 * @since     1.0
		 *
		 * @return    void
		 */ 
		public function __construct() {  
		
			/**
			 * Default values configuration 
			 */
			$this->_config = array(
				'widget_title'=>wcpt_widget_title,
				'number_of_post_display'=>wcpt_number_of_post_display, 
				'title_text_color'=>wcpt_title_text_color,
				'category_tab_text_color'=>wcpt_category_tab_text_color,
				'category_tab_background_color'=>wcpt_category_tab_background_color,
				'header_text_color'=>wcpt_header_text_color,
				'header_background_color'=>wcpt_header_background_color,
				'display_title_over_image'=>wcpt_display_title_over_image, 
				'hide_widget_title'=>wcpt_hide_widget_title,   
				'hide_post_title'=>wcpt_hide_post_title,
				'template'=>wcpt_template, 
				'vcode'=>$this->getUCode(), 
				'category_id'=>wcpt_category,
				'security_key'=>wcpt_security_key,
				'tp_widget_width'=>wcpt_widget_width 
			); 
			
			
			/**
			 * Load text domain
			 */
			add_action( 'plugins_loaded', array( $this, 'wccategorytab_text_domain' ) );
		 
			
			parent::__construct( 'wccategorytab', __( 'Category and Product Tab', 'wccategorytab' ) ); 
			
			/**
			 * Widget initialization for tab category and posts
			 */
			add_action( 'widgets_init', array( &$this, 'initcategoryProductTab' ) ); 
			
			/**
			 * Load the CSS/JS scripts
			 */
			add_action( 'init',  array( $this, 'wccategorytab_scripts' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'wcpt_admin_enqueue' ) ); 
			
		}
		
	
	   /**
		* Validate widget or shortcode post type page
		*
		* @access  private
		* @since   1.0
		*
		* @return  bool It returns true if page is post.php or widget otherwise returns false
		*/ 
		private function validate_page() {

			if ( ( isset( $_GET['post_type'] )  && $_GET['post_type'] == 'wcpt_tabs' ) || strpos($_SERVER["REQUEST_URI"],"widgets.php") > 0  || strpos($_SERVER["REQUEST_URI"],"post.php" ) > 0 || strpos($_SERVER["REQUEST_URI"], "wccategorytab_settings" ) > 0  )
				return TRUE;
		
		} 	
		
		
	   /**
		* Register and load JS/CSS for admin widget configuration 
		*
		* @access  private
		* @since   1.0
		*
		* @return  bool|void It returns false if not valid page or display HTML for JS/CSS
		*/  
		public function wcpt_admin_enqueue() {

			if ( ! $this->validate_page() )
				return FALSE;
			
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'admin-wccategorytab.css', WCPT_MEDIA."css/admin-wccategorytab.css" );
			wp_enqueue_script( 'admin-wccategorytab.js', WCPT_MEDIA."js/admin-wccategorytab.js" ); 
			
		}	
		
		/**
		 * Load the CSS/JS scripts
		 *
		 * @return  void
		 *
		 * @access  public
		 * @since   1.0
		 */
		function wccategorytab_scripts() {

			$dependencies = array( 'jquery' );
			 
			/**
			 * Include Category and Product Tab JS/CSS 
			 */
			wp_enqueue_style( 'wccategorytab', WCPT_MEDIA."css/wccategorytab.css" );
			 
			wp_enqueue_script( 'wccategorytab', WCPT_MEDIA."js/wccategorytab.js", $dependencies  );
			
			/**
			 * Define global javascript variable
			 */
			wp_localize_script( 'wccategorytab', 'wccategorytab', array(
				'wcpt_ajax_url' => admin_url( 'admin-ajax.php' ),
				'wcpt_security'  =>  wp_create_nonce(wcpt_security_key),
				'wcpt_media'  => WCPT_MEDIA,
				'wcpt_all'  => __( 'All', 'wccategorytab' ),
				'wcpt_plugin_url' => plugins_url( '/', __FILE__ ),
			)); 
		}	
		
		
		/**
		 * Loads categories as per taxonomy 
		 *
 		 * @access  public
		 * @since   1.0
		 *
		 * @param   string  $taxonomy  Type of category
		 * @return  object  Returns categories object
		 */ 
		 public function getCategoryDataByTaxonomy( $taxonomy = "" ) {
				 
			global $wpdb;
			
			if( !$taxonomy || trim( $taxonomy ) == "" )
				$taxonomy = "product_cat";
					  
			/**
			 * Fetch all the categories from database of the provided type
			 */   
			$_cats = get_terms( $taxonomy, array('hide_empty'=>false,'order'=>'ASC') ); 
			
			$this->sort_terms_hierarchy($_cats);
			
			return	$_cats;
		}
		
		/**
		 * Loads the text domain
		 *
		 * @access  private
		 * @since   1.0
		 *
		 * @return  void
		 */
		public function wccategorytab_text_domain() {

		  /**
		   * Load text domain
		   */
		   load_plugin_textdomain( 'wccategorytab', false, wcpt_plugin_DIR . '/languages' );
			
		}
		 
		/**
		 * Load and register widget settings
		 *
		 * @access  private
		 * @since   1.0
		 *
		 * @return  void
		 */ 
		public function initcategoryProductTab() { 
			
		  /**
		   * Widget registration
		   */ 
		  register_widget( 'categoryProductTabWidget_Admin' );
			
		}     
		 
		/**
		 * Get post image by given image attachment id
		 *
 		 * @access  public
		 * @since   1.0
		 *
		 * @param   int   $img  Image attachment ID
		 * @return  string  Returns image html from post attachment
		 */
		 public function getProductImage( $img ) {
		 
			$image_link = wp_get_attachment_url( $img ); 
			if( $image_link ) {
				$image_title = esc_attr( get_the_title( $img ) );  
				return wp_get_attachment_image( $img , array(180,180), 0, $attr = array(
									'title'	=> $image_title,
									'alt'	=> $image_title
								) );
			}else{
				return "<img src='".WCPT_MEDIA."images/no-img.png' />";
			}
			
		 }
		 
	
		/**
		 * Get all the categories
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @return  object It contains all the categories for shop
		 */
		public function getCategories($category_ids = "") {

			global $wpdb;  
			$_short_order = "ASC";  

			$__category_type = "product_cat"; 
			 
			if(trim($category_ids) != "")
				$_cats = get_terms( $__category_type, array('include'=>$category_ids,'hide_empty'=>false,'order'=>$_short_order) );
			else	
				$_cats = get_terms( $__category_type, array('hide_empty'=>false,'order'=>$_short_order) );  
			
			
			$this->sort_terms_hierarchy($_cats);  
			return $_cats;
		}
		
		/**
		 * Short terms hierarchy order
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @param   array $terms terms array to make hierarchy
		 * @return  object It contains all the hierarchy terms for shop
		 */
		function sort_terms_hierarchy(Array &$terms) {
			$result = array();
			$parent = 0;
			$depth = 0;
			$i = 0;
			do {
				$temp = array();
				foreach($terms as $j => $term) {
					if ($term->parent == $parent) {
						$term->depth = $depth;  
						array_push($temp, $term);
						unset($terms[$j]);
					} 
					$term->category = $term->name;
					$term->id = $term->term_taxonomy_id;
				}
				array_splice($result, $i, 0, $temp);
				if(isset($result[$i])){
					$parent = $result[$i]->term_id;
					$depth = $result[$i]->depth + 1;
				}
			} while ($i++ < count($result));
			$terms = $result;
		} 
		
		/**
		 * Get the number of dash string
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @param   number $depth numberic value that indicates the depth of term
		 * @return  string It returns dash string.
		 */
		function get_hierarchy_dash($depth) {
			$_dash = "";
			for( $i = 0; $i < $depth; $i++ ) {
				$_dash .= "--"; 
			} 
			return $_dash." ";
		}
		

		/**
		* Fetch post data from database by category, search text and item limit
		*
		* @access  public
		* @since   1.0 
		* 
		* @param   int    $category_id  		Category ID  
		* @param   int    $_limit_start  		Limit to fetch post starting from given position
		* @param   int    $_limit_end  			Limit to fetch post ending to given position
		* @param   int    $category_flg  		Whether to fetch whether posts by category id or prevent for searching
		* @param   int    $is_default_category_with_hidden  To check settings of default category If it's value is '1'. Default value is '0'
		* @param   int    $is_count  			Whether to fetch only number of products from database as count of items 
		* @param   int    $_is_last_updated  	Whether to fetch only last updated post or not
		* @return  object Set of searched post data
		*/
		function getSqlResult( $category_id, $_limit_start, $_limit_end, $category_flg = 0, $is_default_category_with_hidden = 0, $is_count = 0, $_is_last_updated = 0 ) {
			
			global $wpdb; 
			$_category_filter_query = "";
			$_post_text_filter_query = "";
			$_fetch_fields = "";
			$_limit = ""; 
			$category_type = "product_cat"; 	
			
		   /**
			* Prepare safe mysql database query
			*/
			if( trim( $category_id ) == "all" ) { 
			
				$_category_filter_query .=  $wpdb->prepare( "INNER JOIN {$wpdb->prefix}term_taxonomy as wtt on wtt.taxonomy = %s  INNER JOIN {$wpdb->prefix}term_relationships as wtr on  wtr.term_taxonomy_id = wtt.term_taxonomy_id and wtr.object_id = wp.ID ", $category_type );  
			
			}
			
			if( $is_count == 1 ) {
				if( intval( $category_id ) > 0 && ( $category_flg == 1 || $is_default_category_with_hidden == 1 ) ) {
					$_category_filter_query .= $wpdb->prepare( " INNER JOIN {$wpdb->prefix}term_relationships as wtr on wtr.term_taxonomy_id = %d and wtr.object_id = wp.ID ", $category_id );
				} 
				$_fetch_fields = " count(*) as total_val ";
			} else { 
				if( intval( $category_id ) > 0 ) {
					$_category_filter_query .= $wpdb->prepare( " INNER JOIN {$wpdb->prefix}term_relationships as wtr on wtr.term_taxonomy_id = %d and wtr.object_id = wp.ID ", $category_id );
				} 
				$_fetch_fields = " wp.post_type, pm.meta_value as sale_price, pm_image.meta_value as post_image, wp.ID as post_id, wp.post_title as post_title, wp.post_date ";
				
				if( $_is_last_updated == 1 )
					$_limit = $wpdb->prepare( " order by CONVERT(sale_price, UNSIGNED INTEGER) ASC limit  %d, %d ", 0, 1 );
				else
					$_limit = $wpdb->prepare( " order by CONVERT(sale_price, UNSIGNED INTEGER) ASC limit  %d, %d ", $_limit_start, $_limit_end );
			} 
			$_post_text_filter_query .=  " and wp.post_type = 'product' ";  
		   /**
			* Fetch post data from database
			*/
			$_result_items = $wpdb->get_results( " select $_fetch_fields from {$wpdb->prefix}posts as wp  
				INNER JOIN {$wpdb->prefix}postmeta as pm on pm.post_id = wp.ID and pm.meta_key = '_price' 
				INNER JOIN {$wpdb->prefix}postmeta as pm_stock on pm_stock.post_id = wp.ID and pm_stock.meta_key = '_stock_status' 
				INNER JOIN {$wpdb->prefix}postmeta as pm_backorders on pm_backorders.post_id = wp.ID and pm_backorders.meta_key = '_backorders' 
				$_category_filter_query LEFT JOIN {$wpdb->prefix}postmeta as pm_image on pm_image.post_id = wp.ID and pm_image.meta_key = '_thumbnail_id'
				where wp.post_status = 'publish' $_post_text_filter_query $_limit " );			
				
				  
			return $_result_items;

		}
		
		/**
		 * Get all the categories types
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @return  object It contains all the types of categories
		 */
		public function wccategorytab_getCategoryTypes() {
		
			global $wpdb;
			 
			return $wpdb->get_results( "select taxonomy from {$wpdb->prefix}term_taxonomy group by taxonomy" );
		
		} 
		
		 
		/**
		 * Get Unique Block ID
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @return  string 
		 */
		public function getUCode() { 
			
			return 'uid_'.md5( "TABULARPANE32@#RPSDD@SQSITARAM@A$".time() );
		
		} 
		
		/**
		 * Get Category and Product Tab Template
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @param   string $file Template file name
		 * @return  string Returns template file path
		 */
		public function getcategoryProductTabTemplate( $file ) {
			
			if( locate_template( $file ) != "" ){
				return locate_template( $file );
			}else{
				return plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' . $file ;
			} 
				
	   }
   }
}