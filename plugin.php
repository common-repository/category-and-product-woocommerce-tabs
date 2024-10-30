<?php 
/*
  Plugin Name: Category and Product Woocommerce Tabs
  Description: Category and Product Woocommerce Tabs
  Author: ikhodal team
  Plugin URI: http://www.ikhodal.com/category-and-product-woocommerce-tabs/
  Author URI: http://www.ikhodal.com/category-and-product-woocommerce-tabs/
  Version: 1.0
  License: GNU General Public License v2.0
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/ 
  
  
//////////////////////////////////////////////////////
// Defines the constants for use within the plugin. //
////////////////////////////////////////////////////// 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  
/**
* Widget/Block Title
*/
define( 'wcpt_widget_title', __( 'Category & Product Tab View', 'wccategorytab') );
 
/**
* Default category selection for fist post load in widget
*/
define( 'wcpt_category', '0' );

/**
* Number of posts per next loading result
*/
define( 'wcpt_number_of_post_display', '2' ); 
 
/**
* Category tab text color 
*/
define( 'wcpt_category_tab_text_color', '#000' );

/**
* Product title text color
*/
define( 'wcpt_title_text_color', '#000' );

/**
* Category tab background color
*/
define( 'wcpt_category_tab_background_color', '#f7f7f7' );

/**
* Widget/block header text color
*/
define( 'wcpt_header_text_color', '#fff' );

/**
* Widget/block header text background color
*/
define( 'wcpt_header_background_color', '#00bc65' );

/**
* Display post title and text over post image
*/
define( 'wcpt_display_title_over_image', 'no' );

/**
* Widget/block width
*/
define( 'wcpt_widget_width', '100%' );  

/**
* Hide/Show widget title
*/
define( 'wcpt_hide_widget_title', 'no' );
 
/**
* Template for widget/block
*/
define( 'wcpt_template', 'pane_style_1' ); 

/**
* Hide/Show post title
*/
define( 'wcpt_hide_post_title', 'no' );

/**
* Security key for block id
*/
define( 'wcpt_security_key', 'WCPT_#9s@R$@ASI#TA(!@@21M3' );
 
/**
*  Assets for tab for category and posts
*/
$wcpt_plugins_url = plugins_url( "/assets/", __FILE__ );

define( 'WCPT_MEDIA', $wcpt_plugins_url );  
 

/**
*  Plugin DIR
*/
$wcpt_plugin_DIR = plugin_basename(dirname(__FILE__));

define( 'wcpt_plugin_DIR', $wcpt_plugin_DIR );

/**
 * Include abstract class for common methods
 */
require_once 'include/abstract.php';


///////////////////////////////////////////////////////
// Include files for widget and shortcode management //
///////////////////////////////////////////////////////

/**
 * Admin panel widget configuration
 */ 
require_once 'include/admin.php';
 
/**
 * Load Category and Product Tab on frontent pages
 */
require_once 'include/wccategorytab.php';  
 