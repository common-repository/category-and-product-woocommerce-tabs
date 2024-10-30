<?php  
/**
 * Register shortcode and render post data as per shortcode configuration. 
 */ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   
if ( ! class_exists( 'categoryProductTabWidget' ) ) { 
	class categoryProductTabWidget extends categoryProductTabLib {
	 
	   /**
		* PHP5 constructor method.
		*
		* Run the following methods when this class is loaded
		*
		* @access  public
		* @since   1.0
		*
		* @return  void
		*/ 
		public function __construct() {
		
			add_action( 'init', array( &$this, 'init' ) ); 
			parent::__construct();
			
		}  
		
	   /**
		* Load required methods on wordpress init action 
		*
		* @access  public
		* @since   1.0
		*
		* @return  void
		*/ 
		public function init() {
		
			add_action( 'wp_ajax_getTotalProducts',array( &$this, 'getTotalProducts' ) );
			add_action( 'wp_ajax_getProducts',array( &$this, 'getProducts' ) ); 
			add_action( 'wp_ajax_getMoreProducts',array( &$this, 'getMoreProducts' ) );
			
			add_action( 'wp_ajax_nopriv_getTotalProducts', array( &$this, 'getTotalProducts' ) );
			add_action( 'wp_ajax_nopriv_getProducts', array( &$this, 'getProducts' ) ); 
			add_action( 'wp_ajax_nopriv_getMoreProducts', array( &$this, 'getMoreProducts' ) ); 
			
			add_shortcode( 'wccategorytab', array( &$this, 'categoryProductTab' ) ); 
			
		} 
		
	   /**
		* Get the total numbers of posts
		*
		* @access  public
		* @since   1.0
		* 
		* @param   int    $category_id  		Category ID  
		* @param   int    $c_flg  				Whether to fetch whether posts by category id or prevent for searching
		* @param   int    $is_default_category_with_hidden  To check settings of default category If it's value is '1'. Default value is '0'
		* @return  int	  Total number of posts  	
		*/  
		public function getTotalProducts( $category_id, $c_flg, $is_default_category_with_hidden ) { 
		
			global $wpdb;   
			
		   /**
			* Check security token from ajax request
			*/
			check_ajax_referer( $this->_config["security_key"], 'security' );

		   /**
			* Fetch posts as per search filter
			*/	
			$_res_total = $this->getSqlResult( $category_id, 0, 0, $c_flg, $is_default_category_with_hidden, 1 );
			
			return $_res_total[0]->total_val;
			 
		}	

		 
	   /**
		* Render tab for category and posts shortcode
		*
		* @access  public
		* @since   1.0
		*
		* @param   array   $params  Shortcode configuration options from admin settings
		* @return  string  Render tab for category and posts HTML
		*/
		public function categoryProductTab( $params = array() ) { 	
		
			$wccategorytab_id = $params["id"]; 
			$wcpt_shortcode = get_post_meta( $wccategorytab_id ); 
			
			foreach ( $wcpt_shortcode as $sc_key => $sc_val ) {			
				$wcpt_shortcode[$sc_key] = $sc_val[0];			
			} 
			
			if(!isset($wcpt_shortcode["number_of_post_display"]))	
				$wcpt_shortcode["number_of_post_display"] = 0;
			if(!isset($wcpt_shortcode["category_id"]))	
				$wcpt_shortcode["category_id"] = 0;
				
			$this->_config = shortcode_atts( $this->_config, $wcpt_shortcode ); 
			
		   /**
			* Load template according to admin settings
			*/
			ob_start();
			
			require( $this->getcategoryProductTabTemplate( "template_" . $this->_config["template"] . ".php" ) ); 
			
			return ob_get_clean();
		
		}   
		
	   /**
		* Load more post via ajax request
		*
		* @access  public
		* @since   1.0
		* 
		* @return  void Displays searched posts HTML to load more pagination
		*/	
		public function getMoreProducts() {
		
			global $wpdb, $wp_query; 
			
		   /**
			* Check security token from ajax request
			*/
			check_ajax_referer($this->_config["security_key"], 'security' );
			
			$_total = ( isset( $_REQUEST["total"] )?esc_attr( $_REQUEST["total"] ):0 );
			$category_id = ( isset( $_REQUEST["category_id"] )?esc_attr( $_REQUEST["category_id"] ):0 );  
			$_limit_start = ( isset( $_REQUEST["limit_start"])?esc_attr( $_REQUEST["limit_start"] ):0 );
			$_limit_end = ( isset( $_REQUEST["number_of_post_display"])?esc_attr( $_REQUEST["number_of_post_display"] ):wcpt_number_of_post_display ); 
			
		   /**
			* Fetch posts as per search filter
			*/	
			$_result_items = $this->getSqlResult( $category_id, $_limit_start, $_limit_end );
		  
			require( $this->getcategoryProductTabTemplate( 'ajax_load_more_posts.php' ) );	
			
			wp_die();
		}    
		
	   /**
		* Load more posts via ajax request
		*
		* @access  public
		* @since   1.0
		* 
		* @return  object Displays searched posts HTML
		*/
		public function getProducts() {
		
		   global $wpdb; 
			
		   /**
			* Check security token from ajax request
			*/	
		   check_ajax_referer( $this->_config["security_key"], 'security' );	   
		   
		   require( $this->getcategoryProductTabTemplate( 'ajax_load_posts.php' ) );	
		   
  		   wp_die();
		
		}
		 
	   /**
		* Get post list with specified limit and filtered by category and search text
		*
		* @access  public
		* @since   1.0 
		*
		* @param   int     $category_id 		 Selected category ID  
		* @param   int     $_limit_end			 Limit to fetch post ending to given position
		* @return  object  Set of searched post data
		*/
		public function getProductList( $category_id, $_limit_end ) {
			
		   /**
			* Check security token from ajax request
			*/	
			check_ajax_referer( $this->_config["security_key"], 'security' );		
			
		   /**
			* Fetch data from database
			*/
			return $this->getSqlResult( $category_id, 0, $_limit_end );
			 
		} 
		
	}
	
}
new categoryProductTabWidget();