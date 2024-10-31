<?php
/**
Plugin Name: RW Recent Post
Description: User friendly RW Recent Post Plugin is all set to making your digital life much easier. The dynamic, user-friendly RW Recent Post plugin gives you the leverage to publish your post directly on your WordPress web application in a very visually appealing manner.
Author: Ramweb
Version: 1.1.2
Author URI: http://ramweb.in/
Contributors: webdevramyash
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once(dirname(__FILE__) . '/classes/Mainclass.php');
require_once(dirname(__FILE__) . '/classes/AdminView.php');
use Rwrpt\Main\rwrpt_recent_post_class as RwrptAll;
new RwrptAll();
define( 'RWRPT_VERSION', '1.1.2' );
/**
 * 
 */
if ( !class_exists( 'rwrptEnque' ) ) {
class rwrptEnque
{
    
    function __construct()
    {
         add_action('wp_enqueue_scripts', array(
            $this,
            'rwrpt_enqueue_callback'
        ));
    }
    public function rwrpt_enqueue_callback()
    {
        wp_enqueue_style('rwrpt_style', plugin_dir_url(__FILE__) . 'css/rwstyle.css', array() , RWRPT_VERSION, 'all');
    }
}
new rwrptEnque();
}
?>