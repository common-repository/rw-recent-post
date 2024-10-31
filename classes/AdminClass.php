<?php
namespace Rwrpt\Admin;
if ( !class_exists( 'RwrptAdminClass' ) ) {
class RwrptAdminClass {

    public function __construct() {
           add_action('admin_menu', array($this,'rwrpt_admin_menu'));
    }

    public function rwrpt_admin_menu() { 
	  add_menu_page('RW Recent Post','RW Recent Post','edit_posts','rwrpt-all-shortcode',array($this,'shortcodePageCallback'),'dashicons-list-view','10');
	  add_submenu_page("rwrpt-all-shortcode", "All Shortcode", "All Shortcode", "manage_options", "rwrpt-all-shortcode", array($this,"shortcodePageCallback"));
	  add_submenu_page("rwrpt-all-shortcode", "Add shortcode", "Add shortcode", "manage_options", "rwrpt-add-shortcode", array($this,"addShortcodePageCallback"));	  
	}

	protected function shortcodePageCallback(){}

	protected function addShortcodePageCallback(){}
}

}


?>